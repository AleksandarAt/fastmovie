<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $movies = Movie::query()
            ->when($request->genre, fn($q) => $q->where('genre', $request->genre))
            ->when($request->language, fn($q) => $q->where('language', $request->language))
            ->when($request->age, fn($q) => $q->where('age_rating', $request->age))
            ->get();

        return view('movies.index', compact('movies'));
    }

    public function show($id)
    {
        $movie = Movie::with('shows')->findOrFail($id);
        return view('movies.show', compact('movie'));
    }
}
