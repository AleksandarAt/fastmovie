<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MovieAdminController;

/*
|--------------------------------------------------------------------------
| Publieke Routes
|--------------------------------------------------------------------------
*/

// Homepage = film overzicht + zoekfunctie
Route::get('/', [MovieController::class, 'index'])->name('home');

// Film detail pagina
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');


/*
|--------------------------------------------------------------------------
| Gebruiker (ingelogd)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reservering maken
    Route::post('/reserve/{show}', [ReservationController::class, 'store'])
        ->name('reserve.store');

    // Mijn reserveringen
    Route::get('/my-reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD films
    Route::resource('movies', MovieAdminController::class);

});


/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
