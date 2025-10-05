<?php

namespace App\Http\Controllers;

use App\Models\BufferCode;
use Illuminate\Http\Request;
use function Symfony\Component\String\s;

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
        $code = BufferCode::where('code', $bufferCode)
            ->where('enabled', true)->first();

        if ($code) {
            $code->number_of_usages++;
            $code->save();
            return true;
        }

        return false;
    }

    public function getBufferCodes()
    {
        return BufferCode::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:buffer_codes',
            'enabled' => 'sometimes|boolean',
        ]);

        $bufferCode = new BufferCode();
        $bufferCode->code = $data['code'];
        $bufferCode->enabled = $data['enabled'];
        $bufferCode->save();

        return redirect()->back();
    }

    public function delete(Request $request) {
        $data = $request->validate([
            'id' => "required|int|exists:buffer_codes,id",
        ]);

        BufferCode::where('id', $data['id'])->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int|exists:buffer_codes,id',
            'code' => 'required|string|unique:buffer_codes,code,' . $request->id,
            'enabled' => 'sometimes|boolean',
        ]);

        $bufferCode = BufferCode::findOrFail($data['id']);
        $bufferCode->code = $data['code'];
        $bufferCode->enabled = $data['enabled'] ?? $bufferCode->enabled;
        $bufferCode->save();

        return redirect()->back();
    }
}
