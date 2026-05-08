<?php

use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientEspaceController;
use App\Http\Controllers\ReservationPubliqueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReservationPubliqueController::class, 'index'])->name('home');
Route::get('/chambres', [ReservationPubliqueController::class, 'chambres'])->name('chambres');
Route::get('/chambres/{chambre}', [ReservationPubliqueController::class, 'chambreDetail'])->name('chambre.detail');
Route::get('/reserver/{chambre}', [ReservationPubliqueController::class, 'reservationForm'])->name('reservation.form');
Route::post('/reserver', [ReservationPubliqueController::class, 'reservationStore'])->name('reservation.store');
Route::get('/reservation/confirmation/{reservation}', [ReservationPubliqueController::class, 'confirmation'])->name('reservation.confirmation');
Route::get('/a-propos', [ReservationPubliqueController::class, 'aPropos'])->name('a-propos');
Route::get('/contact', [ReservationPubliqueController::class, 'contact'])->name('contact');

// Espace client
Route::prefix('mon-compte')->name('client.')->group(function () {
    Route::get('/connexion', [ClientAuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [ClientAuthController::class, 'login'])->name('login.post');
    Route::post('/deconnexion', [ClientAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', \App\Http\Middleware\EnsureClientRole::class])->group(function () {
        Route::get('/', [ClientEspaceController::class, 'dashboard'])->name('dashboard');
        Route::get('/reservations', [ClientEspaceController::class, 'reservations'])->name('reservations');
        Route::get('/factures', [ClientEspaceController::class, 'factures'])->name('factures');
        Route::get('/factures/{facture}/pdf', [ClientEspaceController::class, 'downloadFacture'])->name('factures.pdf');
    });
});
