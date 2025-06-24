<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkersAvailabilityRequest;
use App\Models\WorkersAvailability;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function postAvailableData(WorkersAvailabilityRequest $request)
    {
        try {
            $validated = $request->validated();
            $availability = WorkersAvailability::create($validated);

            return response()->json([
                'success' => true,
                'data' => $availability,
                'message' => 'Availability added successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableData($user_id)
    {
        $availability = WorkersAvailability::where('worker_id', $user_id)->get();

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Availability fetched successfully'
        ]);
    }
}
