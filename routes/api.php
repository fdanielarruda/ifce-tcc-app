<?php

use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', fn() => response()->json(['message' => 'Bem-vindo a API Sistema Financeiro']));

Route::get('/users', [UserApiController::class, 'index']);
Route::post('/users', [UserApiController::class, 'store']);
Route::delete('/users', [UserApiController::class, 'delete']);

Route::get('/transactions/summary/{type}', [TransactionApiController::class, 'summary']);
Route::post('/transactions', [TransactionApiController::class, 'store']);
Route::delete('/transactions/{id}', [TransactionApiController::class, 'delete']);