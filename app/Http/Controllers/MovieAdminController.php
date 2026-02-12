<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieAdminController extends Controller
{
    public function index()
    {
        $movies = Movie::latest()->paginate(15);
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster_image' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url',
            'genre' => 'required|string',
            'age_rating' => 'required|integer|min:0',
            'language' => 'required|string',
            'country' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
        ]);

        if ($request->hasFile('poster_image')) {
            $validated['poster_image'] = $request->file('poster_image')->store('posters', 'public');
        }

        Movie::create($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Film succesvol toegevoegd!');
    }

    public function show(Movie $movie)
    {
        $movie->load('shows.reservations');
        return view('admin.movies.show', compact('movie'));
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'poster_image' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url',
            'genre' => 'required|string',
            'age_rating' => 'required|integer|min:0',
            'language' => 'required|string',
            'country' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
        ]);

        if ($request->hasFile('poster_image')) {
            if ($movie->poster_image) {
                Storage::disk('public')->delete($movie->poster_image);
            }
            $validated['poster_image'] = $request->file('poster_image')->store('posters', 'public');
        }

        $movie->update($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Film succesvol bijgewerkt!');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster_image) {
            Storage::disk('public')->delete($movie->poster_image);
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Film succesvol verwijderd!');
    }

    public function dashboard()
    {
        $totalMovies = Movie::count();
        $totalReservations = Reservation::where('status', '!=', 'cancelled')->count();
        $totalRevenue = Reservation::where('status', 'paid')->sum('total_price');
        $todayReservations = Reservation::whereDate('created_at', today())->count();
        
        $popularMovies = Movie::withCount(['shows as reservations_count' => function ($query) {
            $query->join('reservations', 'shows.id', '=', 'reservations.show_id')
                  ->where('reservations.status', '!=', 'cancelled');
        }])
        ->orderBy('reservations_count', 'desc')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact('totalMovies', 'totalReservations', 'totalRevenue', 'todayReservations', 'popularMovies'));
    }

    public function showReservations(Movie $movie)
    {
        $reservations = Reservation::whereHas('show', function ($query) use ($movie) {
            $query->where('movie_id', $movie->id);
        })
        ->with(['show', 'user'])
        ->latest()
        ->paginate(20);

        return view('admin.movies.reservations', compact('movie', 'reservations'));
    }
}
