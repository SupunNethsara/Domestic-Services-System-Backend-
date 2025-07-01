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

    public function getSendRequestToWorkers(Request $request)
    {
        $workerId = $request->user_id;
        $serviceRequests = ServiceRequest::where('worker_id', $workerId)
            ->with(['client.profile'])
            ->get();
        $data = $serviceRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'message' => $request->message,
                'status' => $request->status,
                'requested_date' => $request->requested_date,
                'special_requirements' => $request->special_requirements,
                'client' => [
                    'id' => $request->client->id,
                    'name' => $request->client->name ?? null,
                    'profile' => $request->client->profile ?? null
                ]
            ];
        }
        );

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    public function updateSendRequestToWorkers(Request $request){
        $validated = $request->validate([
            'request_id' => 'required|exists:service_requests,id',
            'status' => 'required|in:pending,accepted,rejected',
            'worker_message' => 'nullable|string '
        ]);
        $serviceRequest = ServiceRequest::where('id', $validated['request_id'])
            ->where('worker_id', Auth::id())
            ->firstOrFail();
        $updateData = ['status' => $validated['status']];
        if (isset($validated['worker_message'])) {
            $updateData['worker_message'] = $validated['worker_message'];
        }
        $serviceRequest->update($updateData);
        return response()->json([
            'success' => true,
            'message' => 'Request status updated successfully',
            'request' => $serviceRequest
        ]);
    }

}
