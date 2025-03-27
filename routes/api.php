<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/ClientRegister', [\App\Http\Controllers\RegisterController::class, 'clientregister']);
Route::post('/WorkerRegister', [\App\Http\Controllers\RegisterController::class, 'workerregister']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);



Route::middleware('auth:sanctum')->get('/profile/{user}', [\App\Http\Controllers\ProfileController::class, 'show']);
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'profile' => $request->user()->profile
    ]);
});

// Create/Update profile
Route::middleware('auth:sanctum')->put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
Route::middleware('auth:sanctum')->post('/profile', [\App\Http\Controllers\ProfileController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy']);
