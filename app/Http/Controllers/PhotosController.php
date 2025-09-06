<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

/** @package App->Http->Controllers */
class PhotosController extends Controller
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

        $photo = Photo::where('file_name', $fileName)
            ->where('id_user', Auth::id()) // Essential security check
            ->first();

        if (!$photo) {
            Log::warning("Access attempt to non-existent or unauthorized photo: '" . $fileName . "' by user ID: " . Auth::id());
            abort(404);
        }

        $path = 'photos/' . $photo->file_name; // Use filename from DB

        if (!Storage::disk('local')->exists($path)) {
            Log::error("Photo record exists but file not found on disk: '" . $path . "' for user ID: " . Auth::id());
            // Optionally, consider deleting the DB record if the file is truly missing
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
    public function uploadPhotos(Request $request)
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
            $countOfValidPhotos = 0;

            foreach ($files as $file) {
                $fileIsValid = $file && $file->isValid();
                if (!$fileIsValid) {
                    Log::warning("Invalid file encountered during upload for user " . Auth::id());
                    continue;
                }

                $photo = $this->createPhoto($file);
                $countOfValidPhotos++;

                Log::info("Photo uploaded and metadata saved by user " . Auth::id() . ": " . $photo->file_name);
            }

            if ($countOfValidPhotos <= 0) {
                return back()->withErrors([
                    'mesage' => "No valid photos were uploaded."
                ]);
            }

            return back();
        } catch (\Exception $e) {
            Log::error('Photos upload failed for user ' . Auth::id() . ': ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', "Failed to upload photos.");
        }
    }

    private function createPhoto(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $uniqueFileName = md5(time() . $originalName . uniqid()) . '.' . $extension;
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $photo = new Photo;
        $photo->id_user = Auth::id();
        $photo->file_name = $uniqueFileName;
        $photo->original_name = $originalName;
        $photo->mime_type = $mimeType;
        $photo->size = $size;
        $photo->save();

        $directory = 'photos';
        Storage::disk('local')->putFileAs($directory, $file, $uniqueFileName);

        return $photo;
    }

    public function getPhotos()
    {
        $photos = Photo::orderBy('created_at', 'desc')->get();

        return [
            'photos' => $photos,
        ];
    }
}
