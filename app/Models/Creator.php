<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    protected $fillable = [
        'profile_id',
        'name',
        'instagram',
        'avatar_url',
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}