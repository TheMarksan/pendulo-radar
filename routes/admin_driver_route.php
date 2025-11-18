<?php
use Illuminate\Support\Facades\Route;

Route::post('/admin/motoristas/{id}/update-route', [App\Http\Controllers\AdminController::class, 'updateDriverRoute'])->name('admin.drivers.updateRoute');
