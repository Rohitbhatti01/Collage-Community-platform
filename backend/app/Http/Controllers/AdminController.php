<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getPendingUsers()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::where('status', 'pending')
            ->with('profile')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function approveUser(Request $request, $userId)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);
        $user->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'User approved successfully',
            'user' => $user
        ]);
    }

    public function rejectUser(Request $request, $userId)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);
        $user->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'User rejected successfully',
            'user' => $user
        ]);
    }

    public function getStats()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_users' => User::count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'approved_users' => User::where('status', 'approved')->count(),
            'rejected_users' => User::where('status', 'rejected')->count(),
            'total_posts' => Post::count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
