<?php

namespace App\Http\Controllers;

use App\Models\ClientJobRequest;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestControll extends Controller
{
    public function ClientStoreRequest (Request $request)
    {
        $validated = $request->validate([
            'jobTitles' => 'required|array|min:1',
            'customJobTitle' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'salaryRange' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'jobType' => 'required|in:one-time,recurring',
            'frequency' => 'required_if:jobType,recurring|nullable|in:daily,weekly,bi-weekly,monthly',
            'hasTransportation' => 'boolean',
            'backgroundCheck' => 'boolean',
            'interviewRequired' => 'boolean',
        ]);

        $jobRequest = ClientJobRequest::create([
            'client_id' => Auth::id(),
            'job_titles' => $validated['jobTitles'],
            'custom_job_title' => $validated['customJobTitle'] ?? null,
            'location' => $validated['location'],
            'salary_range' => $validated['salaryRange'],
            'description' => $validated['description'],
            'start_date' => $validated['startDate'],
            'end_date' => $validated['endDate'] ?? null,
            'job_type' => $validated['jobType'],
            'frequency' => $validated['frequency'] ?? null,
            'has_transportation' => $validated['hasTransportation'] ?? false,
            'background_check' => $validated['backgroundCheck'] ?? false,
            'interview_required' => $validated['interviewRequired'] ?? false,
            'status' => 'open'
        ]);

        return response()->json([
            'message' => 'Job request created successfully',
            'data' => $jobRequest
        ], 201);
    }
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

        $clientId = Auth::id();

        $status = ServiceRequest::where('worker_id', $request->worker_id)
            ->where('client_id', $clientId)
            ->latest()
            ->first();

        return response()->json($status ? [$status->status] : []);
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
