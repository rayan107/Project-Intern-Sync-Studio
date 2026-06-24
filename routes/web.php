<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserAuthController;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

// ============================================
// QR CODE CHECKIN ROUTE (MUST BE FIRST!)
// ============================================
Route::get('/checkin/{token}', function ($token) {
    $parts = explode('_', $token);
    
    if (count($parts) < 2) {
        return view('checkin-result', [
            'success' => false,
            'message' => '❌ Invalid QR Code format'
        ]);
    }
    
    $userId = $parts[0];
    $eventId = $parts[1];
    
    $user = User::find($userId);
    $event = Event::find($eventId);
    
    if (!$user || !$event) {
        return view('checkin-result', [
            'success' => false,
            'message' => '❌ User or Event not found'
        ]);
    }
    
    // Check if user is registered for this event
    $isRegistered = false;
    $tableName = null;
    
    // Check all possible tables
    if (Schema::hasTable('event_user')) {
        $tableName = 'event_user';
        $isRegistered = DB::table('event_user')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    } elseif (Schema::hasTable('event_registrations')) {
        $tableName = 'event_registrations';
        $isRegistered = DB::table('event_registrations')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    } elseif (Schema::hasTable('registrations')) {
        $tableName = 'registrations';
        $isRegistered = DB::table('registrations')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    }
    
    if ($isRegistered) {
        // Get current time in Lebanon timezone
        $checkinTime = Carbon::now('Asia/Beirut');
        
        // Update check-in time
        try {
            if ($tableName && Schema::hasColumn($tableName, 'checked_in_at')) {
                DB::table($tableName)
                    ->where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->update([
                        'checked_in_at' => $checkinTime,
                        'status' => 'present'
                    ]);
            } else {
                // If column doesn't exist, try to add it
                try {
                    Schema::table($tableName, function ($table) {
                        $table->timestamp('checked_in_at')->nullable();
                        $table->string('status')->default('registered');
                    });
                    
                    // Update again after adding column
                    DB::table($tableName)
                        ->where('user_id', $userId)
                        ->where('event_id', $eventId)
                        ->update([
                            'checked_in_at' => $checkinTime,
                            'status' => 'present'
                        ]);
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        
        return view('checkin-result', [
            'success' => true,
            'message' => '✅ Check-in Successful!',
            'user' => $user,
            'event' => $event,
            'checkin_time' => $checkinTime,
        ]);
    } else {
        return view('checkin-result', [
            'success' => false,
            'message' => '❌ User is not registered for this event. Please register first.',
            'user' => $user,
            'event' => $event,
        ]);
    }
})->name('checkin');

// API endpoint for JSON verification
Route::get('/api/checkin/{token}', function ($token) {
    $parts = explode('_', $token);
    
    if (count($parts) < 2) {
        return response()->json(['success' => false, 'message' => 'Invalid QR code'], 400);
    }
    
    $userId = $parts[0];
    $eventId = $parts[1];
    
    $user = User::find($userId);
    $event = Event::find($eventId);
    
    if (!$user || !$event) {
        return response()->json(['success' => false, 'message' => 'User or event not found'], 404);
    }
    
    $isRegistered = false;
    $tableName = null;
    
    if (Schema::hasTable('event_user')) {
        $tableName = 'event_user';
        $isRegistered = DB::table('event_user')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    } elseif (Schema::hasTable('event_registrations')) {
        $tableName = 'event_registrations';
        $isRegistered = DB::table('event_registrations')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    } elseif (Schema::hasTable('registrations')) {
        $tableName = 'registrations';
        $isRegistered = DB::table('registrations')
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    }
    
    if ($isRegistered) {
        // Get current time in Lebanon timezone
        $checkinTime = Carbon::now('Asia/Beirut');
        
        // Update check-in time for API
        try {
            if ($tableName && Schema::hasColumn($tableName, 'checked_in_at')) {
                DB::table($tableName)
                    ->where('user_id', $userId)
                    ->where('event_id', $eventId)
                    ->update([
                        'checked_in_at' => $checkinTime,
                        'status' => 'present'
                    ]);
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'event' => ['id' => $event->id, 'title' => $event->title, 'date' => $event->event_date],
            'checked_in_at' => $checkinTime->toISOString()
        ]);
    } else {
        return response()->json(['success' => false, 'message' => 'User not registered for this event'], 403);
    }
});

// Scan page
Route::get('/scan', function () {
    return view('admin.scan');
})->name('admin.scan')->middleware('auth:admin');

// ============ FIX CHECK-IN DATA (MANUAL) ============
Route::get('/admin/fix-checkin/{eventId}/{userId}', function($eventId, $userId) {
    $checkinTime = Carbon::now('Asia/Beirut');
    
    // تحديث البيانات
    DB::table('event_user')
        ->where('event_id', $eventId)
        ->where('user_id', $userId)
        ->update([
            'checked_in_at' => $checkinTime,
            'status' => 'present',
            'updated_at' => now()
        ]);
    
    // جلب البيانات بعد التحديث
    $updated = DB::table('event_user')
        ->where('event_id', $eventId)
        ->where('user_id', $userId)
        ->first();
    
    // سجل في الـ Log
    \Log::info('Manual check-in fixed', [
        'event_id' => $eventId,
        'user_id' => $userId,
        'checked_in_at' => $updated->checked_in_at ?? null,
        'status' => $updated->status ?? null
    ]);
    
    return response()->json([
        'success' => true,
        'message' => '✅ Check-in data fixed!',
        'data' => $updated
    ]);
})->middleware('auth:admin');

// ============ FIX ALL CHECK-INS (Fix all users with status=present but no time) ============
Route::get('/admin/fix-all-checkins', function() {
    $count = DB::table('event_user')
        ->where('status', 'present')
        ->whereNull('checked_in_at')
        ->update([
            'checked_in_at' => DB::raw('updated_at'),
            'updated_at' => now()
        ]);
    
    return response()->json([
        'success' => true,
        'message' => "✅ Fixed {$count} check-ins!",
        'fixed_count' => $count
    ]);
})->middleware('auth:admin');

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (FOR FRONTEND USERS)
|--------------------------------------------------------------------------
*/
Route::get('/', [UserEventController::class, 'index'])->name('user.events');
Route::post('/events/register', [UserEventController::class, 'register'])->name('user.events.register');
Route::post('/events/cancel', [UserEventController::class, 'cancel']);
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/login', fn() => redirect()->route('admin.login'))->name('login');

// ============ REVIEW ROUTES ============
Route::get('/events/{event}/reviews', [ReviewController::class, 'index']);
Route::get('/events/{event}/reviews/user/{userId}', [ReviewController::class, 'checkUserReview']);
Route::post('/events/review', [ReviewController::class, 'store']);
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

// ============ NOTIFICATION ROUTES ============
Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

// ============ FAVORITE ROUTES ============
Route::post('/events/{event}/favorite', [FavoriteController::class, 'toggle'])->name('events.favorite');
Route::get('/api/user/favorites', [FavoriteController::class, 'getUserFavorites']);

/*
|--------------------------------------------------------------------------
| ADMIN FORGOT / RESET PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('/admin/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('admin.password.update');

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES 
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // ============ EVENTS ============
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/events/{event}/registrations', [EventController::class, 'registrations'])->name('events.registrations');
        Route::get('/events/{id}/images', [EventController::class, 'getImages'])->name('events.images');
        Route::get('/events/{id}/details', [EventController::class, 'getEventDetails'])->name('events.details');

        // ============ REVIEWS (admin) ============
        Route::get('/events/{event}/reviews', [ReviewController::class, 'index'])->name('events.reviews');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // ============ MESSAGES (admin) ============
        Route::get('/events/{event}/messages', [MessageController::class, 'eventMessages'])->name('events.messages');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/messages', [MessageController::class, 'allMessages'])->name('messages.all');
        Route::post('/messages/reply', [MessageController::class, 'reply'])->name('messages.reply');
        
        // ============ NOTIFICATIONS (admin) ============
        Route::post('/notify', [NotificationController::class, 'storeFromAdmin'])->name('admin.notify');

        // ============ USERS (admin) ============
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/events/{event}', [UserController::class, 'unregisterEvent'])->name('users.unregisterEvent');

        // ============ ADMINS (admin) ============
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
        Route::put('/admins/{id}/permissions', [AdminController::class, 'updatePermissions'])->name('admins.permissions');
        Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
        Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::get('/admins/{id}/permissions', [AdminController::class, 'getPermissions'])->name('admins.getPermissions');
    });

/*
|--------------------------------------------------------------------------
| USER AUTH ROUTES (FRONTEND USERS)
|--------------------------------------------------------------------------
*/
Route::post('/api/user/register', [UserAuthController::class, 'register']);
Route::post('/api/user/login', [UserAuthController::class, 'login']);
Route::post('/api/user/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/api/user/current', [UserAuthController::class, 'current'])->middleware('auth:sanctum');
Route::get('/api/user/{id}/registrations', [UserController::class, 'getUserRegistrations']);

Route::get('/api/user/session', function() {
    if (auth()->check()) {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ]);
    }
    return response()->json(['success' => false]);
});

// ============ API ROUTES FOR ATTENDEES COUNT ============
Route::get('/api/events/attendees-count', function() {
    $events = \App\Models\Event::withCount('users')->get();
    $counts = [];
    foreach ($events as $event) {
        $counts[$event->id] = $event->users_count;
    }
    return response()->json([
        'success' => true,
        'counts' => $counts
    ]);
})->middleware('auth:admin');

// ============ REAL-TIME UPDATES FOR ADMIN ============
Route::get('/api/admin/events/counts', function() {
    $events = \App\Models\Event::withCount('users')->get();
    $counts = [];
    foreach ($events as $event) {
        $counts[$event->id] = $event->users_count;
    }
    return response()->json([
        'success' => true,
        'counts' => $counts,
        'total_registrations' => \Illuminate\Support\Facades\DB::table('event_user')->count()
    ]);
})->middleware('auth:admin');

// ============ LANGUAGE SWITCH ============
Route::post('/switch-language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return response()->json(['success' => true]);
})->name('language.switch');

// ============ TEST ROUTE ============
Route::get('/test-time', function() {
    return response()->json([
        'utc' => now(),
        'beirut' => Carbon::now('Asia/Beirut'),
        'database' => DB::select('SELECT NOW() as now')
    ]);
});

/*
|--------------------------------------------------------------------------
| FALLBACK - MUST BE LAST!
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (
        request()->is('admin*') &&
        !request()->is('admin/login') &&
        !request()->is('admin/forgot-password') &&
        !request()->is('admin/reset-password*')
    ) {
        return redirect()->route('admin.login')->with('error', 'Please login first to access this page.');
    }
    return redirect('/');
});