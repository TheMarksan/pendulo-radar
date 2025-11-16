<?php
// ...existing code...
use App\Http\Controllers\PassengerController;
// ...existing code...

// First access (password reset) for passenger
Route::get('/passageiro/primeiro-acesso', [PassengerController::class, 'firstAccess'])->name('passenger.first.access');
Route::post('/passageiro/primeiro-acesso', [PassengerController::class, 'updateFirstAccess'])->name('passenger.update.first.access');

// ...existing code...
