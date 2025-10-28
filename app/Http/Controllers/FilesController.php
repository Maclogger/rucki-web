<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ImageHelper;
use App\Models\File;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class FilesController extends Controller
{
    /**
     * Display a private file.
     * Accessible only by authenticated users.
     */
    public function show($fileName)
    {
        $userIds = [
            Auth::id(),
            User::where("username", env("BUFFER_CODE_ACCOUNT_USERNAME"))->first()->id
        ];
        $photo = File::where('file_name', $fileName)
            ->whereIn('id_user',  $userIds)
            ->first();

        if (!$photo) {
            Log::warning("Access attempt to non-existent or unauthorized photo: '" . $fileName . "' by user ID: " . Auth::id());
            abort(404);
        }

        $path = 'files/' . $photo->file_name;

        if (!Storage::disk('local')->exists($path)) {
            Log::error("File record exists but file not found on disk: '" . $path . "' for user ID: " . Auth::id());
            abort(404);
        }

        $file = Storage::disk('local')->get($path);
        $type = Storage::disk('local')->mimeType($path);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Display a thumbnail for a file.
     * Accessible only by authenticated users.
     */
    public function showThumbnail($fileName)
    {
        $userIds = [
            Auth::id(),
            User::where("username", env("BUFFER_CODE_ACCOUNT_USERNAME"))->first()->id
        ];

        $file = File::where('file_name', $fileName)
            ->whereIn('id_user', $userIds)
            ->first();

        if (!$file) {
            Log::warning("Access attempt to non-existent or unauthorized file thumbnail: '" . $fileName . "' by user ID: " . Auth::id());
            abort(404);
        }

        // If thumbnail doesn't exist, return original file
        if (!$file->thumbnail_name) {
            return $this->show($fileName);
        }

        $thumbnailPath = 'thumbnails/' . $file->thumbnail_name;

        if (!Storage::disk('local')->exists($thumbnailPath)) {
            Log::warning("Thumbnail not found on disk, returning original: '" . $thumbnailPath . "'");
            return $this->show($fileName);
        }

        $thumbnail = Storage::disk('local')->get($thumbnailPath);
        $type = Storage::disk('local')->mimeType($thumbnailPath);

        return response($thumbnail, 200)
            ->header('Content-Type', $type)
            ->header('Cache-Control', 'public, max-age=31536000'); // Cache for 1 year since thumbnails don't change
    }

    /**
     * Upload one or more new files.
     * Accessible only by authenticated users.
     *
     * @param Request $request
     * @return RedirectResponse>JsonResponse
     */
    public function uploadFiles(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'max:200000',
        ]);

        Log::info($request);

        try {
            $files = $request->file('files');
            $countOfValidFiles = 0;
            $countOfFailedFiles = 0;

            foreach ($files as $uploadedFile) {
                $fileIsValid = $uploadedFile && $uploadedFile->isValid();
                if (!$fileIsValid) {
                    Log::warning("Invalid uploadedFile encountered during upload for user " . Auth::id());
                    $countOfFailedFiles++;
                    continue;
                }

                try {
                    $file = $this->createFile($uploadedFile);
                    $countOfValidFiles++;
                    Log::info("File uploaded and metadata saved by user " . Auth::id() . ": " . $file->file_name);
                } catch (Exception $e) {
                    Log::error('Failed to process one of the uploaded files for user ' . Auth::id() . ': ' . $e->getMessage(), ['exception' => $e]);
                    $countOfFailedFiles++;
                    continue;
                }
            }

            if ($countOfValidFiles <= 0) {
                return back()->withErrors([
                    'message' => "No valid files were uploaded."
                ]);
            }

            if ($countOfFailedFiles > 0) {
                return back()->with('warning', "Some files could not be uploaded (" . $countOfFailedFiles . ").");
            }

            return back();
        } catch (Exception $e) {
            Log::error('File upload failed for user ' . Auth::id() . ': ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', "Failed to upload files.");
        }
    }

    private function createFile(UploadedFile $uploadedFile)
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $uniqueFileName = md5(time() . $originalName . uniqid()) . '.' . $extension;
        $mimeType = $uploadedFile->getMimeType();
        $size = $uploadedFile->getSize();

        $directory = 'files';
        $filePath = $directory . '/' . $uniqueFileName;

        try {
            $stored = Storage::disk('local')->putFileAs($directory, $uploadedFile, $uniqueFileName);
            if ($stored === false || $stored === null) {
                // putFileAs can return null/false in some drivers; treat it as an error
                throw new Exception("Failed to store uploaded file: $filePath");
            }
        } catch (Exception $e) {
            Log::error("Failed to store uploaded file for user " . Auth::id() . ": " . $e->getMessage(), ['exception' => $e]);
            // rethrow so the calling code (uploadFiles) can handle/report the failure
            throw $e;
        }

        // 2) Try to create the DB record. If this fails, attempt to remove the stored file to avoid orphaned files.
        try {
            $file = new File();
            $author = $this->getAuthor();
            if ($author == null) {
                throw new Exception("Failed to store uploaded file. Author could not be set.");
            }
            $file->id_user = $author->id;
            $file->file_name = $uniqueFileName;
            $file->original_name = $originalName;
            $file->mime_type = $mimeType;
            $file->size = $size;

            // Generate thumbnail if it's an image
            if (ImageHelper::isImage($mimeType)) {
                $thumbnailFileName = 'thumb_' . $uniqueFileName;
                $thumbnailDirectory = 'thumbnails';
                $thumbnailPath = $thumbnailDirectory . '/' . $thumbnailFileName;

                $thumbnailGenerated = ImageHelper::generateThumbnail($filePath, $thumbnailPath);

                if ($thumbnailGenerated) {
                    $file->thumbnail_name = $thumbnailFileName;
                    Log::info("Thumbnail generated for file: $uniqueFileName");
                } else {
                    Log::warning("Failed to generate thumbnail for file: $uniqueFileName");
                }
            }

            $file->save();

        } catch (Exception $e) {
            // Cleanup stored file to avoid orphaned files
            try {
                if (Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                }
                // Also cleanup thumbnail if it was generated
                if (isset($thumbnailPath) && Storage::disk('local')->exists($thumbnailPath)) {
                    Storage::disk('local')->delete($thumbnailPath);
                }
            } catch (Exception $cleanupEx) {
                Log::error("Failed to cleanup stored file after DB failure: $filePath", ['exception' => $cleanupEx]);
            }

            Log::error("Failed to save file metadata for user " . Auth::id() . ": " . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }

        return $file;
    }


    private function getAuthor(): User | null
    {
        $loggedUser = Auth::user();
        if ($loggedUser) {
            return $loggedUser;
        }

        $bufferCodeAccountUsernameKey = "BUFFER_CODE_ACCOUNT_USERNAME";
        $bufferCodeAccountUsername = env($bufferCodeAccountUsernameKey);
        if ($bufferCodeAccountUsername == null) {
            Log::error("$bufferCodeAccountUsernameKey was not FOUND in .env file.");
            return null;
        }
        $bufferCodeAccount = User::where("username", $bufferCodeAccountUsername)->first();
        if ($bufferCodeAccount == null) {
            Log::error("BufferCodeUser was not FOUND in DB. Username=$bufferCodeAccountUsername");
            return null;
        }

        return $bufferCodeAccount;
    }

    public function getFiles(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $page = $request->input('page', 1);

        $files = File::orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'files' => $files->items(),
            'pagination' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
                'has_more' => $files->hasMorePages(),
            ]
        ]);
    }

    /**
     * Get the latest files with a timestamp check
     * Used for polling to check for new files
     */
    public function getLatestFiles(Request $request)
    {
        $request->validate([
            'since' => 'required|date',
            'limit' => 'integer|min:1|max:100'
        ]);

        $since = $request->input('since');
        $limit = $request->input('limit', 20);

        $newFiles = File::where('created_at', '>', $since)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'files' => $newFiles,
            'count' => $newFiles->count(),
            'checked_at' => now()->toISOString(),
        ]);
    }

    public function deleteSingleFile(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:files,id',
        ]);

        $this->deleteFileById($request->id);

        Log::info("Fotografia záznam zmazaný z DB: ID $request->id");

        return back();
    }

    public function deleteMultipleFiles(Request $request)
    {
        $request->validate([
            'ids' => "required|array",
            'ids.*' => "required|integer|exists:files,id"
        ]);

        $idsToDelete = $request->ids;
        foreach ($idsToDelete as $id) {
            $this->deleteFileById($id);
        }
    }

    private function deleteFileById(int $id)
    {
        $file = File::find($id);

        if (!$file) {
            throw ValidationException::withMessages([
                'id' => "Súbor s ID $id nebol nájdený.",
            ])->status(Response::HTTP_NOT_FOUND);
        }

        $directory = 'files';
        $filePath = $directory . '/' . $file->file_name;

        if (Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            Log::info("Fotografia súbor zmazaný: $filePath");
        } else {
            Log::warning("Fotografia súbor nenájdený na zmazanie: $filePath");
        }

        // Delete thumbnail if it exists
        if ($file->thumbnail_name) {
            $thumbnailPath = 'thumbnails/' . $file->thumbnail_name;
            if (Storage::disk('local')->exists($thumbnailPath)) {
                Storage::disk('local')->delete($thumbnailPath);
                Log::info("Thumbnail zmazaný: $thumbnailPath");
            }
        }

        $file->delete();
    }

    public function debugButtonPressed()
    {
    }

}
