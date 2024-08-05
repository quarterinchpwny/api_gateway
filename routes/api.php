<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthenticationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::match(['get', 'post', 'put', 'delete'], '/{service}/{endpoint}', [GatewayController::class, 'forwardRequest'])
        ->where('endpoint', '.*');

    Route::resources([
        'service' => ServiceController::class,
    ]);
});
