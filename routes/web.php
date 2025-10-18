<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/transacoes', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transacoes/cadastrar', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/transacoes', [TransactionController::class, 'storeManual'])->name('transactions.store');
Route::post('/transacoes/ai', [TransactionController::class, 'storeAi'])->name('transactions.store.ai');
Route::put('/transacoes/{id}', [TransactionController::class, 'update'])->name('transactions.update');
Route::delete('/transacoes/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

require __DIR__ . '/auth.php';
