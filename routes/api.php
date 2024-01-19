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


// <-- Auth routes -->
Route::get('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('authAdmin', [\App\Http\Controllers\AuthController::class, 'authAdmin']);

Route::middleware('auth:sanctum')->group(function() {
Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']); // get user
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']); // logout


// <-- User routes -->
Route::post('authUser', [\App\Http\Controllers\AuthController::class, 'authUser']); // create user
Route::put('updateUser/{id}', [\App\Http\Controllers\AuthController::class, 'updateUser']); // update user
Route::delete('deleteUser/{id}', [\App\Http\Controllers\AuthController::class, 'deleteUser']); // delete user


// <-- Application routes -->
Route::post('createApp', [\App\Http\Controllers\FrontController::class, 'createApp']); // create app
Route::put('updateApp/{id}', [\App\Http\Controllers\FrontController::class, 'updateApp'])->middleware('back-office'); // update app
Route::delete('deleteApp/{id}', [\App\Http\Controllers\FrontController::class, 'deleteApp']); // delete app
Route::get('getAllApps', [\App\Http\Controllers\FrontController::class, 'getAllApps']); // get all apps
Route::post('searchApps', [\App\Http\Controllers\FrontController::class, 'searchApps']); // search apps
});


});