<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|integer|exists:users,id'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message']
        ]);

        event(new Chat([
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'timestamp' => $message->created_at->toISOString()
        ]));

        return response()->json($message, 201);
    }

    public function getMessages($workerId)
    {
        $userId = Auth::id();

        $messages = Message::where(function($query) use ($userId, $workerId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $workerId);
        })->orWhere(function($query) use ($userId, $workerId) {
            $query->where('sender_id', $workerId)
                ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
