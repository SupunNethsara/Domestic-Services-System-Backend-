<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestControll extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'requested_date' => 'required|date',
            'special_requirements' => 'nullable|string'
        ]);

        $request = ServiceRequest::create([
            'client_id' => Auth::id(),
            'worker_id' => $validated['worker_id'],
            'message' => $validated['message'] ?? null,
            'requested_date' => $validated['requested_date'],
            'special_requirements' => $validated['special_requirements'] ?? null,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Request sent successfully',
            'request' => $request
        ], 201);
    }
    public function respondToClient(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|integer',
        ]);
        $statuses = ServiceRequest::where('worker_id', $request->worker_id)
            ->pluck('status');

        return response()->json($statuses);
    }
}
