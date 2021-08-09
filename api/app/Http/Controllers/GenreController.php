<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index( Request $request ) {

        $genres = \App\Models\Genre::all();

        return response()->json([
            'datas' => $genres
        ]);
    }

  
}
