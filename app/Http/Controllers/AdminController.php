<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminloginRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class AdminController extends Controller
{
    public function Adminregister(AdminloginRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'worker',
        ]);

        try {
            $admin = Admin::create([
                'user_id' => $user->id,
                'name' => $validated['fname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
            ]);

            return response()->json([
                'success' => true,
                'data' => $admin,
                'message' => 'Admin registered successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
