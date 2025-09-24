<?php

namespace App\Http\Controllers;

use App\Events\FileDeleted;
use App\Events\FileUploaded;
use App\Models\File;
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
     * Display a private photo.
     * Accessible only by authenticated users.
     */
    public function show($fileName)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $photo = File::where('file_name', $fileName)
            ->where('id_user', Auth::id())
            ->first();

        if (!$photo) {
            Log::warning("Access attempt to non-existent or unauthorized photo: '" . $fileName . "' by user ID: " . Auth::id());
            abort(404);
        }

        $path = 'photos/' . $photo->file_name;

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
     * Upload one or more new photos.
     * Accessible only by authenticated users.
     *
     * @param Request $request
     * @return \Illuminate\Http->JsonResponse
     */
    public function uploadFiles(Request $request)
    {
        if (!Auth::check()) {
            return back()->withErrors([
                'message' => "Attempt to upload photos by unauthenticated user.",
            ]);
        }

        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        Log::info($request);

        try {
            $files = $request->file('photos');
            $countOfValidFiles = 0;

            foreach ($files as $file) {
                $fileIsValid = $file && $file->isValid();
                if (!$fileIsValid) {
                    Log::warning("Invalid file encountered during upload for user " . Auth::id());
                    continue;
                }

                $photo = $this->createFile($file);
                $countOfValidFiles++;

                Log::info("File uploaded and metadata saved by user " . Auth::id() . ": " . $photo->file_name);
            }

            if ($countOfValidFiles <= 0) {
                return back()->withErrors([
                    'mesage' => "No valid photos were uploaded."
                ]);
            }

            return back();
        } catch (\Exception $e) {
            Log::error('File upload failed for user ' . Auth::id() . ': ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', "Failed to upload photos.");
        }
    }

    private function createFile(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $uniqueFileName = md5(time() . $originalName . uniqid()) . '.' . $extension;
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $file = new File();
        $file->id_user = Auth::id();
        $file->file_name = $uniqueFileName;
        $file->original_name = $originalName;
        $file->mime_type = $mimeType;
        $file->size = $size;
        $file->save();
        FileUploaded::dispatch($file);

        $directory = 'photos';
        Storage::disk('local')->putFileAs($directory, $file, $uniqueFileName);

        return $file;
    }

    public function getFiles()
    {
        $photos = File::orderBy('created_at', 'desc')->get();

        return [
            'files' => $photos,
        ];
    }

    public function deleteSingleFile(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:photos,id',
        ]);

        $this->deleteFileById($request->id);

        Log::info("Fotografia záznam zmazaný z DB: ID {$request->id}");

        return back();
    }

    public function deleteMultipleFiles(Request $request)
    {
        $request->validate([
            'ids' => "required|array",
            'ids.*' => "required|integer|exists:photos,id"
        ]);

        $idsToDelete = $request->ids;
        foreach ($idsToDelete as $id) {
            $this->deleteFileById($id);
        }
    }

    private function deleteFileById(int $id)
    {
        $photo = File::find($id);

        if (!$photo) {
            throw ValidationException::withMessages([
                'id' => "Fotografia s ID {$id} nebola nájdená.",
            ])->status(Response::HTTP_NOT_FOUND);
        }

        $directory = 'photos';
        $filePath = $directory . '/' . $photo->file_name;

        if (Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            Log::info("Fotografia súbor zmazaný: {$filePath}"); // Voliteľné: logovanie
        } else {
            Log::warning("Fotografia súbor nenájdený na zmazanie: {$filePath}"); // Voliteľné: logovanie
        }

        $photo->delete();
        FileDeleted::dispatch($photo->id, $photo->id_user);
    }

    public function debugButtonPressed() {}
}
