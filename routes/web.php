<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;
// Admin - Painel geral de viagens dos motoristas
Route::get('/admin/viagens-motoristas', [App\Http\Controllers\AdminController::class, 'allDriversDashboard'])->name('admin.allDriversDashboard');

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


// API endpoint for drivers by route
Route::get('/api/rotas/{routeId}/motoristas', function($routeId) {
    $date = request('date');
    $time = request('time');
    $query = \App\Models\DriverSchedule::with('driver')
        ->where('route_id', $routeId)
        ->where('is_active', true);
    if ($date) {
        $query->where('date', $date);
    }
    if ($time) {
        // Considerar horários a partir da hora cheia anterior
        $timeBase = substr($time, 0, 2) . ':00';
        $query->where('departure_time', '>=', $timeBase);
    }
    $schedules = $query->get();
    $result = $schedules->map(function($schedule) {
        return [
            'schedule_id' => $schedule->id,
            'driver_id' => $schedule->driver->id,
            'driver_name' => $schedule->driver->name,
            'date' => $schedule->date ? $schedule->date->format('Y-m-d') : null,
            'departure_time' => $schedule->departure_time,
            'return_time' => $schedule->return_time,
        ];
    });
    return response()->json($result);
});

Route::get('/api/rotas/{routeId}/paradas', function($routeId) {
    $stops = \App\Models\Stop::where('route_id', $routeId)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
    return response()->json($stops);
});

// Legacy route for backwards compatibility
Route::get('/passageiro/sucesso/{id}', [PassengerController::class, 'success'])->name('passenger.success');

// Driver routes
Route::get('/motorista', function() {
    if (session('driver_id')) {
        return redirect()->route('driver.dashboard');
    }
    return view('driver.welcome');
})->name('driver.index');
Route::get('/motorista/cadastro', [DriverController::class, 'registerForm'])->name('driver.register');
Route::post('/motorista/cadastro', [DriverController::class, 'register'])->name('driver.store');
Route::get('/motorista/login', [DriverController::class, 'login'])->name('driver.login');
Route::post('/motorista/login', [DriverController::class, 'authenticate'])->name('driver.authenticate');
Route::get('/motorista/logout', [DriverController::class, 'logout'])->name('driver.logout');
Route::get('/motorista/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
Route::get('/motorista/comprovante/{id}', [DriverController::class, 'viewReceipt'])->name('driver.receipt');
Route::post('/motorista/iniciar-retorno', [DriverController::class, 'startReturn'])->name('driver.start.return');
Route::post('/motorista/confirmar-parada', [DriverController::class, 'confirmStop'])->name('driver.confirm.stop');

// Admin routes
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.index');
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin/primeiro-acesso', [App\Http\Controllers\AdminController::class, 'firstAccess'])->name('admin.first.access');
Route::post('/admin/primeiro-acesso', [App\Http\Controllers\AdminController::class, 'updateFirstAccess'])->name('admin.update.first.access');
Route::get('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

// Admin - Users
Route::get('/admin/usuarios', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
Route::post('/admin/usuarios/{id}/reset', [App\Http\Controllers\AdminController::class, 'resetUserAccess'])->name('admin.users.reset');

// Admin - Access Keys
Route::get('/admin/chaves', [App\Http\Controllers\AdminController::class, 'accessKeys'])->name('admin.access.keys');
Route::post('/admin/chaves', [App\Http\Controllers\AdminController::class, 'storeAccessKey'])->name('admin.access.keys.store');
Route::post('/admin/chaves/{id}/toggle', [App\Http\Controllers\AdminController::class, 'toggleAccessKey'])->name('admin.access.keys.toggle');
Route::delete('/admin/chaves/{id}', [App\Http\Controllers\AdminController::class, 'deleteAccessKey'])->name('admin.access.keys.delete');

// Admin - Routes
Route::get('/admin/rotas', [App\Http\Controllers\AdminController::class, 'routes'])->name('admin.routes');
Route::post('/admin/rotas', [App\Http\Controllers\AdminController::class, 'storeRoute'])->name('admin.routes.store');
Route::post('/admin/rotas/{id}/toggle', [App\Http\Controllers\AdminController::class, 'toggleRoute'])->name('admin.routes.toggle');
Route::delete('/admin/rotas/{id}', [App\Http\Controllers\AdminController::class, 'deleteRoute'])->name('admin.routes.delete');

// Admin - Stops
Route::get('/admin/rotas/{routeId}/paradas', [App\Http\Controllers\AdminController::class, 'stops'])->name('admin.stops');
Route::post('/admin/rotas/{routeId}/paradas', [App\Http\Controllers\AdminController::class, 'storeStop'])->name('admin.stops.store');
Route::post('/admin/rotas/{routeId}/paradas/ordem', [App\Http\Controllers\AdminController::class, 'updateStopOrder'])->name('admin.stops.order');
Route::post('/admin/rotas/{routeId}/paradas/{id}/toggle', [App\Http\Controllers\AdminController::class, 'toggleStop'])->name('admin.stops.toggle');
Route::delete('/admin/rotas/{routeId}/paradas/{id}', [App\Http\Controllers\AdminController::class, 'deleteStop'])->name('admin.stops.delete');

// Admin - Drivers & Cars
Route::get('/admin/motoristas', [App\Http\Controllers\AdminController::class, 'drivers'])->name('admin.drivers');
Route::delete('/admin/motoristas/{id}', [App\Http\Controllers\AdminController::class, 'deleteDriver'])->name('admin.drivers.delete');
Route::get('/admin/motoristas/{driverId}/editar-horario', [App\Http\Controllers\AdminController::class, 'editDriverSchedule'])->name('admin.drivers.editSchedule');
Route::post('/admin/motoristas/{driverId}/editar-horario', [App\Http\Controllers\AdminController::class, 'updateDriverSchedule'])->name('admin.drivers.updateSchedule');
Route::post('/admin/motoristas/{id}/reset', [App\Http\Controllers\AdminController::class, 'resetDriverAccess'])->name('admin.drivers.reset');

// First access (password reset) for passenger
require __DIR__.'/passenger_first_access.php';

