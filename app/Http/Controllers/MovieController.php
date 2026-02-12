<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('shows');

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        // Filter by language
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Filter by age rating
        if ($request->filled('age')) {
            $query->where('age_rating', '<=', $request->age);
        }

        // Filter by show date
        if ($request->filled('date')) {
            $query->whereHas('shows', function ($q) use ($request) {
                $q->where('show_date', $request->date);
            });
        }

        $movies = $query->latest('release_date')->get();

        // Get unique values for filters
        $genres = Movie::distinct()->pluck('genre')->sort()->values();
        $languages = Movie::distinct()->pluck('language')->sort()->values();
        $ageRatings = [6, 9, 12, 16, 18];

        return view('movies.index', compact('movies', 'genres', 'languages', 'ageRatings'));
    }

    public function show($id)
    {
        $movie = Movie::with(['shows' => function ($query) {
            $query->where('show_date', '>=', today())
                  ->orderBy('show_date')
                  ->orderBy('show_time');
        }])->findOrFail($id);
        
        return view('movies.show', compact('movie'));
    }
}

