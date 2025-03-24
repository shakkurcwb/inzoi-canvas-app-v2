<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Scraper;
use App\Services\GalleryParser;
use App\Models\Gallery;
use App\Models\Creator;
use App\Models\Image;

class ScrapeController extends Controller
{
    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->input('url');

        $scraper = new Scraper(true, true);

        $html = $scraper->scrape($url);

        $parser = new GalleryParser();

        $data = $parser->parse($html);

        $creator = Creator::firstOrCreate([
            'profile_id' => $data['creator_id']
        ]);

        $creator->update([
            'name' => $data['creator_name'],
            'avatar_url' => $data['creator_avatar_url'],
        ]);

        if (Gallery::where('url', $url)->exists()) {
            return redirect()->route('home')
                ->with('failure', 'Gallery already exists');
        }

        $gallery = Gallery::create([
            'creator_id' => $creator->id,
            'url' => $url,
            'title' => $data['title'],
            'description' => $data['description'],
            'downloads' => $data['downloads'],
            'likes' => $data['likes'],
            'creation_date' => $data['creation_date'],
        ]);

        foreach ($data['images'] as $image) {
            Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Gallery scraped');
    }
}
