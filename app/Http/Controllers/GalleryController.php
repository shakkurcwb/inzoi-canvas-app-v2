<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function show(Gallery $gallery)
    {
        $gallery->load('creator', 'images');

        return view('gallery.show', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'published_at' => 'nullable|date',
        ]);

        if ($request->has('published_at')) {
            $gallery->published_at = $validated['published_at'];
        }

        $gallery->save();

        return redirect()->route('gallery.show', $gallery);
    }
}