<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner - FastMovie Renesse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-secondary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <span class="text-2xl font-bold text-primary">FastMovie</span>
                        <span class="text-xl font-semibold text-white ml-2">Admin</span>
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Home</a>
                        <a href="{{ route('admin.movies.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Movies</a>
                        <a href="{{ route('admin.scan') }}" class="text-white bg-primary px-3 py-2 rounded-md text-sm font-medium">Scanner</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-primary hover:bg-accent text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-secondary">QR Code Scanner</h1>
            <p class="text-gray-600 mt-2">Scan tickets to validate and check-in reservations</p>
        </div>

        <!-- Scanner Container -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Scanner Status -->
            <div id="scanner-status" class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Click "Start Scanner" to begin scanning tickets</p>
            </div>

            <!-- Scanner Controls -->
            <div class="flex justify-center space-x-4 mb-6">
                <button id="start-scanner" class="bg-primary hover:bg-accent text-white px-6 py-3 rounded-lg font-semibold shadow-md transition inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Start Scanner</span>
                </button>
                <button id="stop-scanner" class="hidden bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    <span>Stop Scanner</span>
                </button>
            </div>

            <!-- QR Scanner Area -->
            <div id="qr-reader" class="hidden rounded-lg overflow-hidden border-4 border-primary"></div>

            <!-- Result Message -->
            <div id="result-message" class="hidden mt-6"></div>

            <!-- Scanned Tickets History -->
            <div id="scan-history" class="mt-8 hidden">
                <h3 class="text-lg font-semibold text-secondary mb-4">Recent Scans</h3>
                <div id="history-list" class="space-y-3">
                    <!-- History items will be added here -->
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-6 bg-accent-light rounded-lg p-6">
            <h3 class="text-lg font-semibold text-secondary mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Instructions
            </h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start">
                    <span class="text-primary font-bold mr-2">1.</span>
                    <span>Click "Start Scanner" and allow camera access when prompted</span>
                </li>
                <li class="flex items-start">
                    <span class="text-primary font-bold mr-2">2.</span>
                    <span>Hold the QR code from the ticket in front of your camera</span>
                </li>
                <li class="flex items-start">
                    <span class="text-primary font-bold mr-2">3.</span>
                    <span>The scanner will automatically detect and validate the ticket</span>
                </li>
                <li class="flex items-start">
                    <span class="text-primary font-bold mr-2">4.</span>
                    <span>A success or error message will be displayed after scanning</span>
                </li>
            </ul>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;
        let scanHistory = [];

        const startButton = document.getElementById('start-scanner');
        const stopButton = document.getElementById('stop-scanner');
        const readerDiv = document.getElementById('qr-reader');
        const statusDiv = document.getElementById('scanner-status');
        const resultDiv = document.getElementById('result-message');
        const historyDiv = document.getElementById('scan-history');
        const historyList = document.getElementById('history-list');

        startButton.addEventListener('click', startScanner);
        stopButton.addEventListener('click', stopScanner);

        function startScanner() {
            html5QrcodeScanner = new Html5Qrcode("qr-reader");

            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            ).then(() => {
                readerDiv.classList.remove('hidden');
                startButton.classList.add('hidden');
                stopButton.classList.remove('hidden');
                statusDiv.classList.add('hidden');
                resultDiv.classList.add('hidden');
            }).catch(err => {
                showError("Unable to start scanner. Please check camera permissions.");
                console.error(err);
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    readerDiv.classList.add('hidden');
                    stopButton.classList.add('hidden');
                    startButton.classList.remove('hidden');
                    statusDiv.classList.remove('hidden');
                }).catch(err => {
                    console.error(err);
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning temporarily
            html5QrcodeScanner.pause(true);

            // Validate ticket with backend
            fetch('/admin/validate-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ticket_code: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message, data.reservation);
                    addToHistory(decodedText, true, data.reservation);
                } else {
                    showError(data.message);
                    addToHistory(decodedText, false, null);
                }
                
                // Resume scanning after 3 seconds
                setTimeout(() => {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                    }
                }, 3000);
            })
            .catch(error => {
                showError("Error validating ticket. Please try again.");
                console.error(error);
                
                setTimeout(() => {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                    }
                }, 3000);
            });
        }

        function onScanError(errorMessage) {
            // Ignore scan errors (they happen frequently while scanning)
        }

        function showSuccess(message, reservation) {
            resultDiv.innerHTML = `
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-bold">Valid Ticket</p>
                    </div>
                    <p>${message}</p>
                    ${reservation ? `
                        <div class="mt-3 text-sm">
                            <p><strong>Movie:</strong> ${reservation.movie}</p>
                            <p><strong>Show Time:</strong> ${reservation.show_time}</p>
                            <p><strong>Seats:</strong> ${reservation.seats}</p>
                            <p><strong>Name:</strong> ${reservation.name}</p>
                        </div>
                    ` : ''}
                </div>
            `;
            resultDiv.classList.remove('hidden');
        }

        function showError(message) {
            resultDiv.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-bold">${message}</p>
                    </div>
                </div>
            `;
            resultDiv.classList.remove('hidden');
        }

        function addToHistory(code, valid, reservation) {
            const timestamp = new Date().toLocaleTimeString();
            const historyItem = {
                code: code,
                valid: valid,
                time: timestamp,
                reservation: reservation
            };
            
            scanHistory.unshift(historyItem);
            if (scanHistory.length > 10) {
                scanHistory.pop();
            }

            updateHistoryDisplay();
        }

        function updateHistoryDisplay() {
            historyDiv.classList.remove('hidden');
            historyList.innerHTML = scanHistory.map(item => `
                <div class="flex items-center justify-between p-3 rounded-lg ${item.valid ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}">
                    <div class="flex items-center space-x-3">
                        ${item.valid ? `
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        ` : `
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        `}
                        <div>
                            <p class="text-sm font-medium text-gray-900">${item.code.substring(0, 20)}...</p>
                            ${item.reservation ? `<p class="text-xs text-gray-600">${item.reservation.movie}</p>` : ''}
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">${item.time}</span>
                </div>
            `).join('');
        }
    </script>
</body>
</html>
