<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($userId = null)
    {
        $userId = $userId ?? auth()->id();
        $user = User::with('profile')->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'current_status' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'socials' => 'nullable|array',
            'socials.*' => 'nullable|string|url|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user basic info
        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
        ]);

        // Prepare profile fields
        $profileData = $request->only([
            'bio',
            'location',
            'batch',
            'course',
            'current_status',
            'skills',
            'socials',
            'phone',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->profile && $user->profile->photo) {
                \Storage::disk('public')->delete($user->profile->photo);
            }
            $profileData['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Update or create profile
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user->load('profile')
        ]);
    }

    public function getAllUsers()
    {
        $currentUserId = auth()->id(); // currently logged-in user ID

        $users = User::with('profile')
            ->where('status', 'approved')              // only approved users
            ->where('id', '!=', $currentUserId)        // exclude current user
            ->where('role', '!=', 'admin')             // exclude admins
            ->orderBy('created_at', 'desc') 
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

}
