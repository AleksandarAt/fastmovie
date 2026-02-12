<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticket - FastMovie Renesse</title>
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
                </div>
            </div>
        </div>
    </nav>

    <main class="py-12">
        @if(session('success'))
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('reservations.index') }}" class="inline-flex items-center text-primary hover:text-orange-600 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Terug naar mijn reserveringen
            </a>

            <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                <!-- Status Badge -->
                <div class="bg-gradient-to-r from-primary to-accent p-6 text-center">
                    @if($reservation->status === 'paid')
                        <div class="inline-flex items-center bg-green-500 text-white px-6 py-3 rounded-full text-lg font-bold">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            TICKET ACTIEF
                        </div>
                    @else
                        <div class="inline-flex items-center bg-yellow-500 text-white px-6 py-3 rounded-full text-lg font-bold">
                            ⏱ WACHT OP BETALING
                        </div>
                    @endif
                </div>

                <div class="p-8">
                    <!-- Movie Title -->
                    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">
                        {{ $reservation->show->movie->title }}
                    </h1>

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Left Side: Details -->
                        <div class="space-y-6">
                            <div class="border-b pb-4">
                                <p class="text-sm text-gray-600 font-semibold">Datum & Tijd</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $reservation->show->show_date->format('d-m-Y') }} om {{ substr($reservation->show->show_time, 0, 5) }}
                                </p>
                            </div>

                            <div class="border-b pb-4">
                                <p class="text-sm text-gray-600 font-semibold">Aantal Plaatsen</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $reservation->seats }} {{ $reservation->seats > 1 ? 'personen' : 'persoon' }}</p>
                            </div>

                            @if($reservation->seat_numbers)
                                <div class="border-b pb-4">
                                    <p class="text-sm text-gray-600 font-semibold">Stoelnummers</p>
                                    <p class="text-lg text-gray-800">{{ implode(', ', $reservation->seat_numbers) }}</p>
                                </div>
                            @endif

                            <div class="border-b pb-4">
                                <p class="text-sm text-gray-600 font-semibold">Ticket Code</p>
                                <p class="text-2xl font-mono font-bold text-primary">{{ $reservation->ticket_code }}</p>
                            </div>

                            @if($reservation->snacks)
                                <div class="border-b pb-4">
                                    <p class="text-sm text-gray-600 font-semibold mb-2">Snacks</p>
                                    @foreach($reservation->snacks as $snack)
                                        <div class="flex justify-between text-sm">
                                            <span>{{ $snack['quantity'] }}x {{ $snack['name'] }}</span>
                                            <span>€{{ number_format($snack['price'] * $snack['quantity'], 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold">Totaalprijs</span>
                                    <span class="text-3xl font-bold text-primary">€{{ number_format($reservation->total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: QR Code -->
                        <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-8">
                            @if($reservation->isPaid())
                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" 
                                         alt="QR Code" 
                                         class="w-64 h-64">
                                </div>
                                <p class="text-center text-sm text-gray-600 mt-4 max-w-xs">
                                    Toon deze QR-code bij de ingang van de bioscoop
                                </p>
                            @else
                                <div class="text-center">
                                    <div class="text-6xl mb-4">🔒</div>
                                    <p class="text-gray-600 mb-4">QR code wordt zichtbaar na betaling</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        @if($reservation->isPaid())
                            <a href="{{ route('reservations.download', $reservation->id) }}" 
                               class="bg-secondary hover:bg-gray-700 text-white px-8 py-3 rounded-lg transition text-center font-semibold">
                                📥 Download PDF
                            </a>
                        @else
                            <form method="POST" action="{{ route('reservations.pay', $reservation->id) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="payment_method" value="ideal">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition font-semibold">
                                    💳 Betaal Nu (€{{ number_format($reservation->total_price, 2) }})
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-bold text-blue-900 mb-2">ℹ️ Belangrijke informatie</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Wees 15 minuten voor aanvang aanwezig</li>
                    <li>• Toon je ticket (QR-code) bij de ingang</li>
                    <li>• Neem een geldig legitimatiebewijs mee</li>
                    <li>• Kinderen onder de {{ $reservation->show->movie->age_rating }} jaar hebben begeleiding nodig</li>
                </ul>
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
