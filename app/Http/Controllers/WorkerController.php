<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkersAvailabilityRequest;
use App\Models\AvailableJobs;
use App\Models\Profile;
use App\Models\WorkersAvailability;
use http\Env\Response;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

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
    public function deleteAvailableData($user_id)
    {
        $availability = WorkersAvailability::findOrFail($user_id);
        $availability->delete();

        return response()->json([
            'success' => true,
            'message' => 'Availability deleted successfully'
        ]);
    }
    public function getAvailableDatatoClients(request $request)
    {
        $availability = WorkersAvailability::select('workers_availability.*', 'profiles.profile_image', 'profiles.about')
            ->join('profiles', 'workers_availability.worker_id', '=', 'profiles.user_id')
            ->whereNotNull('profiles.profile_image')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Availability fetched successfully with profile data'
        ]);
    }
    public function makeRequestToClient (request $request){
        try {

            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'title'     => 'required|string|max:255',
                'category'  => 'required|string|max:100',
                'message'   => 'required|string',
            ]);
            $user = auth()->user();
            $requestJobs = AvailableJobs::create([
                'client_id' => $request->client_id,
                'worker_id' => $user->id,
                'title'     => $request->title,
                'category'  => $request->category,
                'message'   => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'data' => $requestJobs,
                'message' => 'Request sent successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed save Request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getRequestToClient(Request $request)
    {
        $user = auth()->user();

        $requestJobs = AvailableJobs::with('workerProfile')
            ->where('client_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requestJobs,
            'message' => 'Request fetched successfully with worker profiles'
        ]);
    }

}
