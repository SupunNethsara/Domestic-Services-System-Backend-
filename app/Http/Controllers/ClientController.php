<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getDataToWorkersChat(Request $request)
    {
        $clients = User::where('role', 'client')
            ->with(['profile' => function($query) {
                $query->select('user_id', 'profile_image', 'first_name', 'last_name');
            }])
            ->get(['id', 'email', 'role']);

        return response()->json([
            'success' => true,
            'data' => $clients
        ])->header('Access-Control-Allow-Origin', '*');
    }
}
