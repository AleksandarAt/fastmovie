<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mijn Reserveringen - FastMovie Renesse</title>
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
                    <a href="{{ route('reservations.index') }}" class="text-primary px-4 py-2">
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
                </div>
            </div>
        </div>
    </nav>

    <main class="py-12">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-8">Mijn Reserveringen</h1>

            @if($reservations->isEmpty())
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🎫</div>
                    <p class="text-xl text-gray-600 mb-4">Je hebt nog geen reserveringen</p>
                    <a href="{{ route('home') }}" class="inline-block bg-primary hover:bg-orange-600 text-white px-8 py-3 rounded-lg transition">
                        Bekijk Films
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($reservations as $reservation)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="md:flex">
                                <!-- Movie Info -->
                                <div class="md:w-1/4 bg-gradient-to-br from-primary to-accent p-6 text-white">
                                    <h3 class="text-2xl font-bold mb-2">{{ $reservation->show->movie->title }}</h3>
                                    <p class="text-sm opacity-90">{{ $reservation->show->movie->genre }}</p>
                                    <div class="mt-4">
                                        <span class="bg-white text-primary px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $reservation->show->movie->age_rating }}+
                                        </span>
                                    </div>
                                </div>

                                <!-- Reservation Details -->
                                <div class="md:w-2/4 p-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 font-semibold">Datum</p>
                                            <p class="text-lg">{{ $reservation->show->show_date->format('d-m-Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 font-semibold">Tijd</p>
                                            <p class="text-lg">{{ substr($reservation->show->show_time, 0, 5) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 font-semibold">Aantal Plaatsen</p>
                                            <p class="text-lg">{{ $reservation->seats }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 font-semibold">Totaalprijs</p>
                                            <p class="text-lg font-bold text-primary">€{{ number_format($reservation->total_price, 2) }}</p>
                                        </div>
                                    </div>

                                    @if($reservation->seat_numbers)
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 font-semibold">Stoelen</p>
                                            <p class="text-sm">{{ implode(', ', $reservation->seat_numbers) }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status & Actions -->
                                <div class="md:w-1/4 p-6 bg-gray-50 flex flex-col justify-between">
                                    <div>
                                        @if($reservation->status === 'paid')
                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold mb-4">
                                                ✓ Betaald
                                            </span>
                                        @elseif($reservation->status === 'pending')
                                            <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold mb-4">
                                                ⏱ Wacht op betaling
                                            </span>
                                        @else
                                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold mb-4">
                                                ✗ Geannuleerd
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <a href="{{ route('reservations.show', $reservation->id) }}" 
                                           class="block w-full bg-primary hover:bg-orange-600 text-white text-center py-2 rounded-lg transition">
                                            Bekijk Ticket
                                        </a>

                                        @if($reservation->status === 'pending')
                                            <form method="POST" action="{{ route('reservations.pay', $reservation->id) }}">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="ideal">
                                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                                                    Betaal Nu
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
