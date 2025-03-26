<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/ClientRegister', [\App\Http\Controllers\RegisterController::class, 'clientregister']);
Route::post('/WorkerRegister', [\App\Http\Controllers\RegisterController::class, 'workerregister']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);


Route::post('/createprofile', [\App\Http\Controllers\ProfileController::class, 'createProfile']);
