<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RightSideBarsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\WorkerController;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
//Register logouts
Route::post('/ClientRegister', [RegisterController::class, 'clientregister']);
Route::post('/WorkerRegister', [RegisterController::class, 'workerregister']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
Route::get('/adminlogout', [AuthController::class, 'adminlogout']);
Route::post('/admin-register', [AdminController::class, 'Adminregister']);

//users
Route::middleware('auth:sanctum')->get('/profile/{user}', [ProfileController::class, 'show']);
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'profile' => $request->user()->profile
    ]);
});
Route::get('/GetUsers', [UserController::class, 'FetchUserData']);
// Create/Update profile
Route::middleware('auth:sanctum')->put('/profile', [ProfileController::class, 'update']);
Route::middleware('auth:sanctum')->post('/profile', [ProfileController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/profile', [ProfileController::class, 'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/workpost', [PostController::class, 'workerstore']);
});
Route::get('/GetPost' ,[PostController::class,'show']);
Route::get('/GetWorkerPost', [PostController::class, 'showWorkerPosts']);
Route::post('/postAvailability', [WorkerController::class, 'postAvailableData']);
Route::get('/getAvailability/{user_id}', [WorkerController::class, 'getAvailableData']);
Route::delete('/deleteAvailability/{user_id}', [WorkerController::class, 'deleteAvailableData']);
Route::get('/getAvailabilitytoClients', [WorkerController::class, 'getAvailableDatatoClients']);
Route::get('/allposts', [PostController::class, 'getallposts']);

//chat
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/messages/{workerId}', [ChatController::class, 'getMessages']);
});

Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['auth:sanctum']);

Route::post('/messages/{clientId}/mark-read', function ($clientId) {
    $workerId = Auth::id();

    Message::where('sender_id', $clientId)
        ->where('receiver_id', $workerId)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return response()->json(['success' => true]);
})->middleware('auth:sanctum');
//Clients
Route::get('/getAllClients', [ClientController::class,'getDataToWorkersChat']);
Route::get('/getTopRatedServices', [RightSideBarsController::class,'getTopRatedServices']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/status', [UserStatusController::class, 'updateStatus']);
    Route::get('/online-users', [UserStatusController::class, 'getOnlineUsers']);
});
