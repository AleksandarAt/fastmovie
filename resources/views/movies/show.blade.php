<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $movie->title }} - FastMovie Renesse</title>
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
    <main class="py-12">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="inline-flex items-center text-primary hover:text-orange-600 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Terug naar overzicht
            </a>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Movie Poster -->
                    <div class="md:w-1/3">
                        <div class="aspect-[2/3] bg-gray-200">
                            @if($movie->poster_image)
                                <img src="{{ asset('storage/' . $movie->poster_image) }}" 
                                     alt="{{ $movie->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-accent">
                                    <span class="text-white text-9xl font-bold opacity-50">🎬</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Movie Details -->
                    <div class="md:w-2/3 p-8">
                        <div class="flex items-start justify-between mb-4">
                            <h1 class="text-4xl font-bold text-gray-800">{{ $movie->title }}</h1>
                            <span class="bg-primary text-white px-4 py-2 rounded-full text-lg font-semibold">
                                {{ $movie->age_rating }}+
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Genre</p>
                                <p class="text-lg">{{ $movie->genre }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Taal</p>
                                <p class="text-lg">{{ $movie->language }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Land</p>
                                <p class="text-lg">{{ $movie->country }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Speelduur</p>
                                <p class="text-lg">{{ $movie->duration }} minuten</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 font-semibold mb-2">Omschrijving</p>
                            <p class="text-gray-700 leading-relaxed">{{ $movie->description }}</p>
                        </div>

                        @if($movie->trailer_url)
                            <a href="{{ $movie->trailer_url }}" target="_blank" 
                               class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition">
                                Bekijk Trailer
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shows Section -->
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Vertoningen</h2>

                @if($movie->shows->isEmpty())
                    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                        <p class="text-gray-500">Er zijn momenteel geen vertoningen gepland voor deze film.</p>
                    </div>
                @else
                    @php
                        $showsByDate = $movie->shows->groupBy(function($show) {
                            return $show->show_date->format('Y-m-d');
                        });
                    @endphp

                    <div class="space-y-6">
                        @foreach($showsByDate as $date => $shows)
                            <div class="bg-white rounded-lg shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">
                                    {{ \Carbon\Carbon::parse($date)->locale('nl')->isoFormat('dddd D MMMM YYYY') }}
                                </h3>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($shows as $show)
                                        <form method="POST" action="{{ route('reservations.store') }}">
                                            @csrf
                                            <input type="hidden" name="show_id" value="{{ $show->id }}">
                                            <input type="hidden" name="seats" value="1">
                                            
                                            <button type="submit" 
                                                    @auth @else onclick="alert('Log in om te reserveren'); return false;" @endauth
                                                    class="w-full bg-gradient-to-r from-primary to-accent hover:from-orange-600 hover:to-orange-400 text-white p-4 rounded-lg shadow transition">
                                                <div class="text-2xl font-bold">{{ substr($show->show_time, 0, 5) }}</div>
                                                <div class="text-sm">€{{ number_format($show->price, 2) }}</div>
                                                <div class="text-xs mt-1">{{ $show->availableSeatsCount }} plaatsen</div>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
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
