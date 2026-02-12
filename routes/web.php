<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MovieAdminController;
use App\Http\Controllers\ScanController;

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
        return redirect()->route('reservations.index');
    })->name('dashboard');

    // Profiel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reserveringen
    Route::get('/my-reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])
        ->name('reservations.show');
    Route::post('/reservations/{id}/snacks', [ReservationController::class, 'addSnacks'])
        ->name('reservations.addSnacks');
    Route::post('/reservations/{id}/pay', [ReservationController::class, 'pay'])
        ->name('reservations.pay');
    Route::get('/reservations/{id}/download', [ReservationController::class, 'download'])
        ->name('reservations.download');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin dashboard
    Route::get('/', [MovieAdminController::class, 'dashboard'])->name('dashboard');

    // CRUD films
    Route::resource('movies', MovieAdminController::class);
    
    // Reserveringen per film
    Route::get('/movies/{movie}/reservations', [MovieAdminController::class, 'showReservations'])
        ->name('movies.reservations');

    // Ticket scanner
    Route::get('/scan', [ScanController::class, 'scan'])->name('scan');
    Route::get('/scan/{ticketCode}', [ScanController::class, 'verify'])->name('scan.verify');

});


/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
