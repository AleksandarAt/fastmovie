<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Management - FastMovie Renesse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <a href="{{ route('admin.movies.index') }}" class="text-white bg-primary px-3 py-2 rounded-md text-sm font-medium">Movies</a>
                        <a href="{{ route('admin.scan') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Scanner</a>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-secondary">Movies Management</h1>
                <p class="text-gray-600 mt-2">Manage all movies in the system</p>
            </div>
            <a href="{{ route('admin.movies.create') }}" class="bg-primary hover:bg-accent text-white px-6 py-3 rounded-lg font-semibold shadow-md transition flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add New Movie</span>
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Movies Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Movie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Release Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($movies as $movie)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($movie->poster_image)
                                    <img src="{{ asset('storage/' . $movie->poster_image) }}" alt="{{ $movie->title }}" class="w-12 h-16 object-cover rounded mr-4">
                                    @else
                                    <div class="w-12 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $movie->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($movie->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-accent-light text-secondary">
                                    {{ $movie->genre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movie->age_rating }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movie->language }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movie->duration }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movie->release_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.movies.edit', $movie) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md transition inline-flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this movie?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition inline-flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg">No movies found</p>
                                    <a href="{{ route('admin.movies.create') }}" class="mt-4 text-primary hover:text-accent font-medium">Add your first movie</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($movies->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $movies->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
