<?php

namespace App\Http\Controllers;

use App\Models\WorkersBankDetails;
use Illuminate\Http\Request;

class WorkerBankController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'branch_code' => 'required|string',
            'account_type' => 'required|string|in:checking,savings',
        ]);

        $details = WorkersBankDetails::create($validated);

        return response()->json($details, 201);
    }

    public function show($id)
    {
        $details = WorkersBankDetails::where('worker_id', $id)->first();

        if (!$details) {
            return response()->json(['message' => 'Bank details not found'], 404);
        }

        return response()->json([
            'account_holder_name' => $details->account_holder_name,
            'account_number' => $details->account_number,
            'bank_name' => $details->bank_name,
            'branch_name' => $details->branch_name,
            'branch_code' => $details->branch_code,
            'account_type' => $details->account_type
        ]);
    }
    public function destroy($id)
    {
        $details = WorkersBankDetails::where('worker_id', $id)->firstOrFail();
        $details->delete();
        return response()->json(['message' => 'Bank details deleted successfully']);
    }
}
