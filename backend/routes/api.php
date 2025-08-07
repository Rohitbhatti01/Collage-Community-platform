<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('auth.register');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('auth.login');

/*
|--------------------------------------------------------------------------
| Debug Route: Test Token Auth (Optional)
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')->name('auth.user');

/*
|--------------------------------------------------------------------------
| Authenticated & Approved Users Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'approved'])->group(function () {

    // 🔓 Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // 👤 Current User Profile
    Route::get('/me', [ProfileController::class, 'show'])->name('profile.me');

    /*
    |--------------------------------------------------------------------------
    | 📨 Messaging Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('messages')->group(function () {
        Route::get('/conversations', [MessageController::class, 'getConversations'])->name('messages.conversations');
        Route::get('/{userId}', [MessageController::class, 'getMessages'])->name('messages.get');
        Route::post('/send', [MessageController::class, 'sendMessage'])->name('messages.send');
    });

    /*
    |--------------------------------------------------------------------------
    | 📝 Post Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    /*
    |--------------------------------------------------------------------------
    | 👤 Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/{userId?}', [ProfileController::class, 'show'])->name('profile.show');
        Route::match(['post', 'put'], '/', [ProfileController::class, 'update'])->name('profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | 👥 Community Users List
    |--------------------------------------------------------------------------
    */
    Route::get('/users', [ProfileController::class, 'getAllUsers'])->name('users.index');
});

/*
|--------------------------------------------------------------------------
| 🔒 Admin Routes (Only Approved Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'approved', 'admin'])->prefix('admin')->group(function () {

    // get all users..
    Route::get('/users', [AdminController::class, 'getAllUsers']);
    // 🟡 Get Pending Users
    Route::get('/pending-users', [AdminController::class, 'getPendingUsers'])->name('admin.pending');

    // ✅ Approve/Reject Users
    Route::post('/approve-user/{userId}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::post('/reject-user/{userId}', [AdminController::class, 'rejectUser'])->name('admin.reject');
    // ✅ Get Approved + rejected Users
    Route::get('/approved-users', [AdminController::class, 'getApprovedUsers']);
    Route::get('/rejected-users', [AdminController::class, 'getRejectedUsers'])->name('admin.rejected');



    // 📊 Admin Dashboard Stats
    Route::get('/stats', [AdminController::class, 'getStats'])->name('admin.stats');
});
