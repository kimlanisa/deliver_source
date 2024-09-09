<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class TextEditorController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        if($request->file('file')) {
            $image = upload_image($request->file('file'), 'publikasi', 'image_content');
            $url = url($image);
            return $url;
        }

        return response()->json('message', 'Gambar tidak ditemukan');
    }

    public function deletePhoto(Request $request)
    {
        if($request->src) {
            $image = str_replace(url('/'), '', $request->src);
            File::delete($image);
            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        }
    }
}
