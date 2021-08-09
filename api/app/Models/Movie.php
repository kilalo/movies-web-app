<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{

    protected $fillable = [
        'title',
        'release_date',
        'poster_path',
        'overview',
        'vote_average',
    ];
    

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre', 'movies_genres')->withTimestamps();
    }


}
