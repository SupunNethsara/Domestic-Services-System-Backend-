<?php

namespace App\Http\Controllers;

use App\Events\UserStatusUpdated;
use App\Models\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|String',
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        UserStatus::updateOrCreate(
            ['user_id' => $user->id],
            ['status' => 'online', 'last_seen_at' => now()]
        );
        broadcast(new UserStatusUpdated($user, 'online'))->toOthers();
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'message' => 'Login Successful',
            'role' => $user->role,
            'id'=>$user->id,
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        UserStatus::updateOrCreate(
            ['user_id' => $user->id],
            ['status' => 'offline', 'last_seen_at' => now()]
        );
        broadcast(new UserStatusUpdated($user, 'offline'))->toOthers();
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout Successful']);
    }


}
