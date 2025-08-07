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

   // get all "users"
    public function getAllUsers()
    {
        $users = User::where('role', '!=', 'admin')
            ->where('status', 'approved')
            ->with('profile')
            ->orderBy('created_at', 'desc') // <-- This line ensures newest users are on top
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    
    // Get all users with status "pending"
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

    // Approve a user
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

    // Get all users with status "approved"
  public function getApprovedUsers()
{
    $approvedUsers = User::where('status', 'approved')
        ->where('role', '!=', 'admin') // Exclude admin users
        ->with('profile')
        ->get();

    return response()->json([
        'success' => true,
        'users' => $approvedUsers
    ]);
}


    // Reject a user
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

    public function getRejectedUsers()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rejectedUsers = User::where('status', 'rejected')->with('profile')->get();

        return response()->json([
            'success' => true,
            'users' => $rejectedUsers
        ]);
    }

    // Get platform statistics
    public function getStats()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_users'     => User::count(),
            'pending_users'   => User::where('status', 'pending')->count(),
            'approved_users'  => User::where('status', 'approved')->count(),
            'rejected_users'  => User::where('status', 'rejected')->count(),
            'total_posts'     => Post::count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
