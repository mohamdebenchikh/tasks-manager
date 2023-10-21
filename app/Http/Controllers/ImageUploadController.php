<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image'=> 'required|image|max:2048',
            'path'=>  'nullable|string|max:30',
        ]);

        $filePath = $request->path ?? 'images';

        $path = Storage::disk('public')->put($filePath,$request->image);
        $url = Storage::url($path);

        return redirect()->back()->with('inputs',['url'=>$url]);
    }
}
