<?php

namespace App\Http\Controllers;

use App\Events\FileDeleted;
use App\Events\FileUploaded;
use App\Models\File;
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
        $photo = File::where('file_name', $fileName)
            ->where('id_user', Auth::id())
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
            $file->id_user = Auth::id();
            $file->file_name = $uniqueFileName;
            $file->original_name = $originalName;
            $file->mime_type = $mimeType;
            $file->size = $size;
            $file->save();            // Cleanup stored file to avoid orphaned files

        } catch (Exception $e) {
            // Cleanup stored file to avoid orphaned files
            try {
                if (Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                }
            } catch (Exception $cleanupEx) {
                Log::error("Failed to cleanup stored file after DB failure: $filePath", ['exception' => $cleanupEx]);
            }

            Log::error("Failed to save file metadata for user " . Auth::id() . ": " . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }

        // 3) Both storage and DB succeeded; dispatch event and return the model
        FileUploaded::dispatch($file);
        return $file;
    }

    public function getFiles()
    {
        $files = File::orderBy('created_at', 'desc')->get();

        return [
            'files' => $files,
        ];
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

        $file->delete();
        FileDeleted::dispatch($file->id, $file->id_user);
    }

    public function debugButtonPressed()
    {
    }
}
