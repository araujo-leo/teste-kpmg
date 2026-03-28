<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TicketController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');
        Route::patch('/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    });
});
