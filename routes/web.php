<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('projects', ProjectController::class);
    Route::get('tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');

    Route::resource('tickets', TicketController::class);
});
