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

// ============ FAVORITE ROUTES (NO AUTH MIDDLEWARE) ============
Route::post('/events/{event}/favorite', [FavoriteController::class, 'toggle'])
    ->name('events.favorite');
Route::get('/api/user/favorites', [FavoriteController::class, 'getUserFavorites']);

/*
|--------------------------------------------------------------------------
| ADMIN FORGOT / RESET PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('/admin/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('admin.password.request');
Route::post('/admin/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset');
Route::post('/admin/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->name('admin.password.update');

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

        // Events
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/events/{event}/registrations', [EventController::class, 'registrations'])->name('events.registrations');
        Route::get('/events/{id}/images', [EventController::class, 'getImages'])->name('events.images');

        // Reviews (admin)
        Route::get('/events/{event}/reviews', [ReviewController::class, 'index'])->name('events.reviews');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Messages (admin)
        Route::get('/events/{event}/messages', [MessageController::class, 'eventMessages'])->name('events.messages');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/messages', [MessageController::class, 'allMessages'])->name('messages.all');
        Route::post('/messages/reply', [MessageController::class, 'reply'])->name('messages.reply');
        
        // Notifications from admin
        Route::post('/notify', [NotificationController::class, 'storeFromAdmin'])->name('admin.notify');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/events/{event}', [UserController::class, 'unregisterEvent'])->name('users.unregisterEvent');

        // Admins
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
        Route::put('/admins/{id}/permissions', [AdminController::class, 'updatePermissions'])->name('admins.permissions');
        Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
        Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
Route::get('/admins/{id}/permissions', [AdminController::class, 'getPermissions'])->name('admins.getPermissions');
Route::get('/admin/events/{event}/registrations', [EventController::class, 'getRegistrations'])->name('admin.events.registrations');
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
});

Route::post('/switch-language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return response()->json(['success' => true]);
})->name('language.switch');
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