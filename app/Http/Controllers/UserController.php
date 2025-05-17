<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Workers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function FetchUserData()
    {
        try {
            $clients = Client::all();
            $workers = Workers::all();
            $users   = User::all();

            return response()->json([
                'clients' => $clients,
                'workers' => $workers,
                'users'   => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
