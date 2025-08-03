<?php

use App\Http\Controllers\CashDepositController;
use App\Http\Controllers\CapitalRequestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cash Deposits
    Route::resource('cash-deposits', CashDepositController::class);
    
    // Capital Requests
    Route::resource('capital-requests', CapitalRequestController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';