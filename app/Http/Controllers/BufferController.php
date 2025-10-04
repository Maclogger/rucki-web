<?php

namespace App\Http\Controllers;

use App\Models\BufferCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BufferController extends Controller
{

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'bufferCode' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'max:200000',
        ]);

        if (!$this->checkCode($request->bufferCode)) {
            return back()->withErrors([
                'message' => "Nesprávny kód!",
            ]);
        }

        return new FilesController()->uploadFiles($request);
    }

    private function checkCode(string $bufferCode): bool
    {
        return BufferCode::where('code', $bufferCode)
            ->where('enabled', true)
            ->exists();
    }
}
