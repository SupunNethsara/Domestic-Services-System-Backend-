<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\WorkerRating;
use App\Models\WorkersAvailability;
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

    public function getWorkersDetailsForRequest(Request $request)
    {
        $workers = WorkersAvailability::with(['profile' => function($query) {
            $query->select('user_id', 'first_name', 'last_name', 'country', 'city', 'profile_image');
        }])->get();
        $result = $workers->map(function($worker) {

            if ($worker->profile && $worker->worker_id == $worker->profile->user_id) {
                return [
                    'worker_id' => $worker->worker_id,
                    'name' => $worker->name,
                    'services' => $worker->services,
                    'availability_type' => $worker->availability_type,
                    'expected_rate' => $worker->expected_rate,
                    'first_name' => $worker->profile->first_name,
                    'last_name' => $worker->profile->last_name,
                    'full_name' => $worker->profile->first_name . ' ' . $worker->profile->last_name,
                    'country' => $worker->profile->country,
                    'city' => $worker->profile->city,
                    'profile_image' => $worker->profile->profile_image,
                ];
            }
            return null;
        })->filter()->values();

        return response()->json($result);
    }
    public function AddRatingToWorker(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'worker_id' => 'required|exists:users,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500',
        ]);

        $rating = WorkerRating::create([
            'worker_id' => $validated['worker_id'],
            'user_id' => $validated['client_id'],
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);

        return response()->json([
            'message' => 'Rating added successfully',
            'rating' => $rating
        ]);
    }
}
