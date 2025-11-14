<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;

// Home - Choose role
Route::get('/', [HomeController::class, 'index'])->name('home');

// Passenger routes
Route::get('/passageiro', [PassengerController::class, 'index'])->name('passenger.index');

// Registration
Route::get('/passageiro/cadastro', [PassengerController::class, 'register'])->name('passenger.register');
Route::post('/passageiro/cadastro', [PassengerController::class, 'storeAccount'])->name('passenger.store.account');

// Login/Logout
Route::get('/passageiro/login', [PassengerController::class, 'login'])->name('passenger.login');
Route::post('/passageiro/login', [PassengerController::class, 'authenticate'])->name('passenger.authenticate');
Route::get('/passageiro/logout', [PassengerController::class, 'logout'])->name('passenger.logout');

// Dashboard
Route::get('/passageiro/dashboard', [PassengerController::class, 'dashboard'])->name('passenger.dashboard');

// Reservations
Route::get('/passageiro/nova-reserva', [PassengerController::class, 'create'])->name('passenger.create');
Route::post('/passageiro/reserva', [PassengerController::class, 'store'])->name('passenger.store');
Route::get('/passageiro/reserva/{id}', [PassengerController::class, 'viewReservation'])->name('passenger.reservation.view');
Route::get('/passageiro/reserva/{id}/editar', [PassengerController::class, 'editReservation'])->name('passenger.reservation.edit');
Route::put('/passageiro/reserva/{id}', [PassengerController::class, 'updateReservation'])->name('passenger.reservation.update');
Route::delete('/passageiro/reserva/{id}', [PassengerController::class, 'deleteReservation'])->name('passenger.reservation.delete');

// Receipt upload
Route::post('/passageiro/reserva/{id}/comprovante', [PassengerController::class, 'uploadReceipt'])->name('passenger.upload.receipt');

// Boarding confirmation
Route::post('/passageiro/reserva/{id}/confirmar-embarque', [PassengerController::class, 'confirmBoarding'])->name('passenger.confirm.boarding');

// Legacy route for backwards compatibility
Route::get('/passageiro/sucesso/{id}', [PassengerController::class, 'success'])->name('passenger.success');

// Driver routes
Route::get('/motorista', [DriverController::class, 'index'])->name('driver.index');
Route::get('/motorista/comprovante/{id}', [DriverController::class, 'viewReceipt'])->name('driver.receipt');
