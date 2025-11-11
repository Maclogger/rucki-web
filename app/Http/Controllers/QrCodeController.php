<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function show($uuid)
    {
        $qrCode = QrCode::where('uuid', $uuid)->firstOrFail();

        return inertia('QrCode/QrCodePage', [
            'questionIndex' => $qrCode->question_index,
            'content' => $qrCode->content
        ]);
    }
}

