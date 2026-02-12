<h1>{{ $movie->title }}</h1>
<p>{{ $movie->description }}</p>

<iframe width="560" height="315"
src="{{ $movie->trailer_url }}"
frameborder="0" allowfullscreen></iframe>

<h3>Speeltijden:</h3>
@foreach($movie->shows as $show)
<form method="POST" action="/reserve/{{ $show->id }}">
@csrf
{{ $show->date }} - {{ $show->time }}
<button type="submit">Reserveer</button>
</form>
@endforeach
