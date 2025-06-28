<?php

namespace App\Http\Controllers;

use App\Events\UserStatusUpdated;
use App\Models\Profile;
use App\Models\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserStatusController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:online,offline,away'
        ]);

        $user = Auth::user();
        $status = $request->status;

        Log::info("Updating status for user {$user->id} to {$status}");

        $userStatus = UserStatus::updateOrCreate(
            ['user_id' => $user->id],
            ['status' => $status, 'last_seen_at' => now()]
        );

        broadcast(new UserStatusUpdated($user, $status))->toOthers();

        return response()->json([
            'message' => 'Status updated successfully',
            'user_id' => $user->id,
            'new_status' => $status
        ]);
    }
    public function getOnlineUsers()
    {
        $onlineUsers = UserStatus::with('user')
            ->whereIn('status', ['online', 'away'])
            ->orderBy('last_seen_at', 'desc')
            ->get()
            ->map(function ($status) {
                return [
                    'id' => $status->user_id,
                    'name' => $status->user->first_name.' '.$status->user->last_name,
                    'status' => $status->status,
                    'last_seen' => $status->last_seen_at->toISOString(),
                    'is_current' => $status->user_id === Auth::id()
                ];
            });

        Log::info("Fetched ".count($onlineUsers)." online users");

        return response()->json($onlineUsers);
    }
}
