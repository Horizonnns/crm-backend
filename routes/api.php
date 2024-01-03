<?php

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

// User routes
Route::get('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('authAdmin', [\App\Http\Controllers\AuthController::class, 'authAdmin']);


Route::middleware('auth:sanctum')->group(function() {
Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']);
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);


// User routes
Route::put('updateUser/{id}', [\App\Http\Controllers\AuthController::class, 'updateUser']);
Route::delete('deleteUser/{id}', [\App\Http\Controllers\AuthController::class, 'deleteUser']);
Route::post('authUser', [\App\Http\Controllers\AuthController::class, 'authUser']);


// Application routes
Route::get('getAllApps', [\App\Http\Controllers\FrontController::class, 'getAllApps']);
Route::post('createApp', [\App\Http\Controllers\FrontController::class, 'createApp']);
Route::delete('deleteApp/{id}', [\App\Http\Controllers\FrontController::class, 'deleteApp']);
Route::post('searchApps', [\App\Http\Controllers\FrontController::class, 'searchApps']);
Route::put('updateApp/{id}', [\App\Http\Controllers\FrontController::class, 'updateApp']);
});