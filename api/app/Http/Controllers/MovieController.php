<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class MovieController extends Controller
{
    public function index( Request $request ) {

        $movies = \App\Models\Movie::select();

        if(isset($request->sort_type) && isset($request->sort_by) && $request->sort_by != '' && Schema::hasColumn('movies', $request->sort_by)) {
            if($request->sort_type != 'ASC' && $request->sort_type != 'DESC') {
                $movies->orderBy($request->sort_by);
            } else {
                $movies->orderBy($request->sort_by, $request->sort_type);
            }
        } else {
            if($request->sort_type != 'ASC' && $request->sort_type != 'DESC') {
                $movies->orderBy('title');
            } else {
                $movies->orderBy('title', $request->sort_type);
            }
        }

        if(isset($request->q) && $request->q != '') {
            $movies->where('title', 'LIKE', '%' . $request->q .'%');
        }
        if(isset($request->genre_id)) {
            $movies->whereHas('genres', function($q) use ($request) {
                $q->where('movies_genres.genre_id', $request->genre_id);
            });
        }
        if(isset($request->vote_average)) {
            $movies->where('vote_average', '>', $request->vote_average)->where('vote_average', '<', $request->vote_average + 1);
        }

        return response()->json([
            'datas' => $movies->paginate(20)
        ]);
    }

    public function show( Request $request, String $slug) {

        $movie = \App\Models\Movie::with('genres')->where('slug', $slug)->firstOrFail();

        return response()->json([
            'movie' => $movie
        ]);
    }
  
}
