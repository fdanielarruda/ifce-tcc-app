<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\GoalController;
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

Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
Route::delete('/goals/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');

require __DIR__ . '/auth.php';
