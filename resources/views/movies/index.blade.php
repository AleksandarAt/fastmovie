@extends('layouts.app')

@section('content')
<h1>FastMovie Renesse</h1>

<form method="GET">
    <select name="genre">
        <option value="">Genre</option>
        <option>Actie</option>
        <option>Comedy</option>
    </select>
    <button type="submit">Zoeken</button>
</form>

<div class="movies">
@foreach($movies as $movie)
    <div class="card">
        <img src="{{ asset('storage/'.$movie->image) }}">
        <h2>{{ $movie->title }}</h2>
        <a href="/movie/{{ $movie->id }}">Bekijk</a>
    </div>
@endforeach
</div>
@endsection
