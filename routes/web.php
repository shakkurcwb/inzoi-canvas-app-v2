<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('scrape', [App\Http\Controllers\ScrapeController::class, 'scrape'])->name('scrape');

Route::get('gallery/{gallery}', [App\Http\Controllers\GalleryController::class, 'show'])->name('gallery.show');

Route::patch('gallery/{gallery}', [App\Http\Controllers\GalleryController::class, 'update'])->name('gallery.update');

Route::patch('creator/{creator}', [App\Http\Controllers\CreatorController::class, 'update'])->name('creator.update');
