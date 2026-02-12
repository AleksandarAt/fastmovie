<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - FastMovie Renesse</title>
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('admin.movies.index') }}" class="text-gray-600 hover:text-secondary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-secondary">Edit Movie</h1>
                    <p class="text-gray-600 mt-2">Update movie details</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('admin.movies.update', $movie) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $movie->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Genre -->
                    <div>
                        <label for="genre" class="block text-sm font-medium text-gray-700 mb-2">Genre *</label>
                        <select name="genre" id="genre" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('genre') border-red-500 @enderror">
                            <option value="">Select Genre</option>
                            <option value="Action" {{ old('genre', $movie->genre) == 'Action' ? 'selected' : '' }}>Action</option>
                            <option value="Comedy" {{ old('genre', $movie->genre) == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                            <option value="Drama" {{ old('genre', $movie->genre) == 'Drama' ? 'selected' : '' }}>Drama</option>
                            <option value="Horror" {{ old('genre', $movie->genre) == 'Horror' ? 'selected' : '' }}>Horror</option>
                            <option value="Romance" {{ old('genre', $movie->genre) == 'Romance' ? 'selected' : '' }}>Romance</option>
                            <option value="Sci-Fi" {{ old('genre', $movie->genre) == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
                            <option value="Thriller" {{ old('genre', $movie->genre) == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                            <option value="Animation" {{ old('genre', $movie->genre) == 'Animation' ? 'selected' : '' }}>Animation</option>
                            <option value="Documentary" {{ old('genre', $movie->genre) == 'Documentary' ? 'selected' : '' }}>Documentary</option>
                            <option value="Fantasy" {{ old('genre', $movie->genre) == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                        </select>
                        @error('genre')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Age Rating -->
                    <div>
                        <label for="age_rating" class="block text-sm font-medium text-gray-700 mb-2">Age Rating *</label>
                        <select name="age_rating" id="age_rating" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('age_rating') border-red-500 @enderror">
                            <option value="">Select Age Rating</option>
                            <option value="All" {{ old('age_rating', $movie->age_rating) == 'All' ? 'selected' : '' }}>All</option>
                            <option value="6" {{ old('age_rating', $movie->age_rating) == '6' ? 'selected' : '' }}>6+</option>
                            <option value="9" {{ old('age_rating', $movie->age_rating) == '9' ? 'selected' : '' }}>9+</option>
                            <option value="12" {{ old('age_rating', $movie->age_rating) == '12' ? 'selected' : '' }}>12+</option>
                            <option value="16" {{ old('age_rating', $movie->age_rating) == '16' ? 'selected' : '' }}>16+</option>
                            <option value="18" {{ old('age_rating', $movie->age_rating) == '18' ? 'selected' : '' }}>18+</option>
                        </select>
                        @error('age_rating')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Language *</label>
                        <select name="language" id="language" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('language') border-red-500 @enderror">
                            <option value="">Select Language</option>
                            <option value="English" {{ old('language', $movie->language) == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Dutch" {{ old('language', $movie->language) == 'Dutch' ? 'selected' : '' }}>Dutch</option>
                            <option value="German" {{ old('language', $movie->language) == 'German' ? 'selected' : '' }}>German</option>
                            <option value="French" {{ old('language', $movie->language) == 'French' ? 'selected' : '' }}>French</option>
                            <option value="Spanish" {{ old('language', $movie->language) == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="Italian" {{ old('language', $movie->language) == 'Italian' ? 'selected' : '' }}>Italian</option>
                        </select>
                        @error('language')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <input type="text" name="country" id="country" value="{{ old('country', $movie->country) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $movie->duration) }}" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('duration') border-red-500 @enderror">
                        @error('duration')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Release Date -->
                    <div>
                        <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">Release Date *</label>
                        <input type="date" name="release_date" id="release_date" value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('release_date') border-red-500 @enderror">
                        @error('release_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Poster -->
                    @if($movie->poster_image)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Poster</label>
                        <img src="{{ asset('storage/' . $movie->poster_image) }}" alt="{{ $movie->title }}" class="w-32 h-48 object-cover rounded-lg shadow-md">
                    </div>
                    @endif

                    <!-- Poster Image -->
                    <div class="md:col-span-2">
                        <label for="poster_image" class="block text-sm font-medium text-gray-700 mb-2">Update Poster Image</label>
                        <input type="file" name="poster_image" id="poster_image" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('poster_image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Recommended size: 300x450px (JPG, PNG). Leave empty to keep current poster.</p>
                        @error('poster_image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trailer URL -->
                    <div class="md:col-span-2">
                        <label for="trailer_url" class="block text-sm font-medium text-gray-700 mb-2">Trailer URL</label>
                        <input type="url" name="trailer_url" id="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}"
                            placeholder="https://www.youtube.com/watch?v=..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('trailer_url') border-red-500 @enderror">
                        @error('trailer_url')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.movies.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-accent text-white rounded-lg transition font-semibold shadow-md">
                        Update Movie
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
