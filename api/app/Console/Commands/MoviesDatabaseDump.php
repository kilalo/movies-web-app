<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;


class MoviesDatabaseDump extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with Movies Database Data by using API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api_key = '582acb8d2d4796d3f4008fbed3d12104';

        $response_genres = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key='. $api_key);
        $genres = json_decode($response_genres, true);

        foreach($genres['genres'] as $el) {
            $genre = new \App\Models\Genre();
            $genre->id = $el['id'];
            $genre->name = $el['name'];
            $genre->save();
        }
        
        for($i=1;$i<500;$i++) {
            $response_movies = Http::get('https://api.themoviedb.org/3/discover/movie?api_key='. $api_key .'&page='. $i .'&language=fr&vote_count.gte=100');
            $movies = json_decode($response_movies, true);
            foreach($movies['results'] as $el) {
                $movie = new \App\Models\Movie();
                $movie->title = $el['title'];
                $movie->slug = \Str::slug($el['title']);
                $movie->release_date = $el['release_date'];
                $movie->poster_path = $el['poster_path'];
                $movie->vote_average = $el['vote_average'];
                $movie->overview = $el['overview'];
                $movie->save();
                foreach($el['genre_ids'] as $current_genre_id) {
                    $movie->genres()->attach($current_genre_id);
                }
                
            }
            \Log::info($i);
        }

    }
}