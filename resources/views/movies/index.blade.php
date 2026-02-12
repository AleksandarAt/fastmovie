<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FastMovie Renesse') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-secondary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-3xl font-bold text-primary">
                        FastMovie <span class="text-white">Renesse</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-white hover:text-primary transition px-4 py-2">
                        Films
                    </a>
                    
                    @auth
                        <a href="{{ route('reservations.index') }}" class="text-white hover:text-primary transition px-4 py-2">
                            Mijn Reserveringen
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-primary transition px-4 py-2">
                                Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-primary hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition">
                                Uitloggen
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-primary transition px-4 py-2">
                            Inloggen
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition">
                            Registreren
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-secondary text-white mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-lg font-semibold text-primary">FastMovie Renesse</p>
                <p class="text-sm mt-2">De beste filmervaring in Renesse</p>
                <p class="text-xs mt-4 text-gray-400">&copy; 2026 FastMovie. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>
</body>
</html>

@section('content')
<div class="bg-gradient-to-r from-primary to-accent py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-white text-center mb-8">Nu in de bioscoop</h1>
        
        <!-- Search and Filter Form -->
        <form method="GET" class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <input type="text" name="search" placeholder="Zoek op titel..." 
                           value="{{ request('search') }}"
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <select name="genre" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Alle genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="language" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Alle talen</option>
                        @foreach($languages as $language)
                            <option value="{{ $language }}" {{ request('language') == $language ? 'selected' : '' }}>
                                {{ $language }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="age" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Alle leeftijden</option>
                        @foreach($ageRatings as $age)
                            <option value="{{ $age }}" {{ request('age') == $age ? 'selected' : '' }}>
                                {{ $age }}+
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-orange-600 text-white px-6 py-2 rounded-md transition">
                        Zoeken
                    </button>
                    <a href="{{ route('home') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md transition text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Movies Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($movies->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Geen films gevonden met de geselecteerde filters.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($movies as $movie)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="aspect-[2/3] bg-gray-200 relative">
                        @if($movie->poster_image)
                            <img src="{{ asset('storage/' . $movie->poster_image) }}" 
                                 alt="{{ $movie->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-accent">
                                <span class="text-white text-6xl font-bold opacity-50">🎬</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-2 right-2 bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $movie->age_rating }}+
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-1">{{ $movie->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            <span class="font-semibold">Genre:</span> {{ $movie->genre }}
                        </p>
                        <p class="text-sm text-gray-600 mb-4">
                            <span class="font-semibold">Taal:</span> {{ $movie->language }}
                        </p>
                        <a href="{{ route('movies.show', $movie->id) }}" 
                           class="block w-full bg-primary hover:bg-orange-600 text-white text-center py-2 rounded-lg transition font-semibold">
                            Bekijk Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
