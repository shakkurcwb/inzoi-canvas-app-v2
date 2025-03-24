<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Scraper;
use App\Services\GalleryParser;

use App\Models\Gallery;
use App\Models\Creator;

class HomeController extends Controller
{
    public function index()
    {
        $query = Gallery::query();

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        $query->orderBy('created_at', 'desc');

        return view('home', [
            'galleries' => $query->paginate(10),
        ]);
    }
}