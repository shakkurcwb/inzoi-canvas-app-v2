<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'creator_id',
        'url',
        'title',
        'description',
        'downloads',
        'likes',
        'images',
        'creation_date',
        'published_at',
    ];

    protected $casts = [
        'downloads' => 'integer',
        'likes' => 'integer',
        'creation_date' => 'date',
        'published_at' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}