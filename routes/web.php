<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\GoalController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/transacoes', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transacoes/cadastrar', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transacoes', [TransactionController::class, 'storeManual'])->name('transactions.store');
    Route::post('/transacoes/ai', [TransactionController::class, 'storeAi'])->name('transactions.store.ai');
    Route::put('/transacoes/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transacoes/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/metas', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/metas', [GoalController::class, 'store'])->name('goals.store');
    Route::delete('/metas/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');

    Route::get('/relatorio', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/relatorio/exportar', [ReportController::class, 'export'])->name('reports.export');
});

Route::middleware('guest')->group(function () {
    Route::get('/primeiro-acesso', [UserController::class, 'firstAccess'])->name('first-access');
    Route::post('/primeiro-acesso', [UserController::class, 'processFirstAccess'])->name('first-access.process');
});

require __DIR__ . '/auth.php';
