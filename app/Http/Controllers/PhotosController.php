<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/** @package App\Http\Controllers */
class PhotosController extends Controller
{
    public function show($fileName)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $path = 'photos/' . $fileName;

        if (!Storage::disk('local')->exists($path)) {
            Log::warning("Photo not found at: '" . $path . "'");
            abort(404);
        }

        $file = Storage::disk('local')->get($path);
        $type = Storage::disk('local')->mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    }
}
