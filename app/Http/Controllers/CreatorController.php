<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Creator;

class CreatorController extends Controller
{
    public function update(Request $request, Creator $creator)
    {
        $validated = $request->validate([
            'instagram' => 'nullable|url',
        ]);

        $creator->update($validated);

        return redirect()->back()->with('success', 'Creator updated!');
    }
}