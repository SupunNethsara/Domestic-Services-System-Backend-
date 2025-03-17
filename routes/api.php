<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/ClientRegister', [\App\Http\Controllers\RegisterController::class, 'clientregister']);
