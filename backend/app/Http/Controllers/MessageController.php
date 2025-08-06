<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getConversations()
    {
        $userId = auth()->id();
        
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) use ($userId) {
                $latestMessage = $messages->first();
                $otherUser = $latestMessage->sender_id === $userId ? $latestMessage->receiver : $latestMessage->sender;
                
                return [
                    'id' => $otherUser->id,
                    'sender_id' => $latestMessage->sender_id,
                    'receiver_id' => $latestMessage->receiver_id,
                    'sender' => $latestMessage->sender,
                    'receiver' => $latestMessage->receiver,
                    'message' => $latestMessage->message,
                    'created_at' => $latestMessage->created_at,
                    'unread_count' => $messages->where('receiver_id', $userId)->where('is_read', false)->count()
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    public function getMessages($userId)
    {
        $currentUserId = auth()->id();
        
        $messages = Message::where(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $currentUserId)
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $currentUserId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver'])
        ], 201);
    }
}
