<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkersAvailabilityRequest;
use App\Models\WorkersAvailability;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
   public function postAvailableData(WorkersAvailabilityRequest $request)
   {
$validated = $request->validated();
       $availability = WorkersAvailability::create($validated);
       $availability->save();
       return response()->json([
           'success' => true,
           'data' => $availability,
           'message' => 'Availability added successfully'
       ]);
   }
}
