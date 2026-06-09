<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // FIXED: Use hasRole() and hasPermissionTo() instead
        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('view_users')) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Unauthorized access.');
        }
        
        // Clean orphaned registrations
        DB::table('event_user')
            ->whereNotIn('event_id', DB::table('events')->pluck('id'))
            ->delete();
        
        $users = User::withCount('events')->latest()->paginate(15);
        
        $stats = [
            'totalUsers' => User::count(),
            'newThisMonth' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'newToday' => User::whereDate('created_at', today())->count(),
            'usersWithEvents' => DB::table('event_user')->distinct('user_id')->count('user_id'),
        ];
        
        $usersJson = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'joined' => $user->created_at->format('M d, Y'),
                'since' => $user->created_at->diffForHumans(),
                'events' => $user->events()->get()->map(function($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'date' => $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : 'No date',
                        'status' => $event->event_date && \Carbon\Carbon::parse($event->event_date)->isFuture() ? 'upcoming' : 'completed',
                        'tag' => $event->event_date && \Carbon\Carbon::parse($event->event_date)->isFuture() ? 'up' : 'co',
                    ];
                })->toArray()
            ];
        })->toJson();
        
        return view('admin.users.index', compact('users', 'stats', 'usersJson'));
    }

    public function show(User $user)
    {
        $admin = Auth::guard('admin')->user();

        // FIXED: Add permission check here too
        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('view_users')) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Unauthorized access.');
        }

        $stats = [
            'totalUsers' => User::count(),
            'newThisMonth' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'newToday' => User::whereDate('created_at', today())->count(),
            'usersWithEvents' => DB::table('event_user')->distinct('user_id')->count('user_id'),
        ];

        $users = User::latest()->paginate(15); 

        return view('admin.users.show', compact('user', 'stats', 'users'));
    }

    public function unregisterEvent($userId, $eventId)
    {
        $admin = Auth::guard('admin')->user();

        // FIXED: Add permission check
        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('manage_users')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $deleted = DB::table('event_user')
                ->where('user_id', $userId)
                ->where('event_id', $eventId)
                ->delete();
            
            if ($deleted) {
                Event::where('id', $eventId)->decrement('attendees');
                
                return response()->json([
                    'success' => true,
                    'message' => 'User unregistered from event successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unregister: ' . $e->getMessage()
            ], 500);
        }
    }
        
    public function register(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:8'
            ]);

            $existing = DB::table('event_user')
                        ->where('event_id', $request->event_id)
                        ->where('email', $request->email)
                        ->exists();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already registered for this event!'
                ], 400);
            }

            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now()
                ]
            );

            DB::table('event_user')->insert([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'registered_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Event::where('id', $request->event_id)->increment('attendees');

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome ' . $request->name,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();

        // FIXED: Add permission check
        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('manage_users')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $user = User::findOrFail($id);
            
            // Delete user's registrations from pivot table
            DB::table('event_user')->where('user_id', $id)->delete();
            
            // Delete the user
            $user->delete();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function cleanupEmpty()
    {
        $admin = Auth::guard('admin')->user();

        // FIXED: Add permission check
        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('manage_users')) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Unauthorized access.');
        }

        $deleted = User::doesntHave('events')->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', "$deleted users without events were deleted.");
    }

    public function getUserRegistrations($id)
{
    $user = User::findOrFail($id);
    $registrations = $user->events()->pluck('events.id')->toArray();
    return response()->json(['registrations' => $registrations]);
}
}