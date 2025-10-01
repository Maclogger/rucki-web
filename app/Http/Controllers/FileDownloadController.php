<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use ZipArchive;

class FileDownloadController extends Controller
{
    public function download(string $fileId)
    {
        $file = File::find($fileId);
        if ($file === null) {
            Log::error("File not found: $fileId");
            return back()->withErrors([
                'message' => 'File not found in DB',
            ]);
        }

        $directory = 'files';
        $filePath = $directory . '/' . $file->file_name;

        $fileExists = Storage::disk('local')->exists($filePath);
        if (!$fileExists) {
            Log::error("Requested file $filePath does not exist.");
            return back()->withErrors([
                'message' => "Requested file $filePath does not exist.",
            ]);
        }

        return Storage::download($filePath, $file->original_name);
    }

    public function downloadFilesInZip(Request $request)
    {
        $request->validate([
            'fileIds' => ['required', 'array'],
            'fileIds.*' => 'int|exists:files,id',
        ]);
        $fileIds = $request->get('fileIds');
        $files = File::whereIn('id', $fileIds)->get();
        if ($files->isEmpty()) {
            return back()->withErrors([
                'message' => "No files found in DB.",
            ]);
        }

        $zip = new ZipArchive();
        $zipFileName = 'zip_download' . time() . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        if (!$zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            Log::error("Could not create ZIP archive. $zipFilePath");
            return back()->withErrors(['message' => "Could not create ZIP archive."]);
        }

        foreach ($files as $file) {
            $filePath = 'files/' . $file->file_name;

            if (Storage::disk('local')->exists($filePath)) {
                $zip->addFromString(
                    $file->original_name,
                    Storage::disk('local')->get($filePath)
                );
            }
        }

        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend();
    }

}


