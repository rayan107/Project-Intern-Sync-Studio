<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserEventController extends Controller
{
    public function index()
    {
        
        $events = Event::with('images')
                      ->orderBy('event_date', 'asc')
                      ->get();
        
        return view('userregistereventpage', compact('events'));
    }
    
public function register(Request $request)
{
    try {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:8'  // Keep as nullable
        ]);

        // Check if already registered
        $existing = DB::table('event_user')
                    ->where('event_id', $request->event_id)
                    ->where('email', $request->email)
                    ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already registered for this event!'
            ], 400);
        }

        // Try to find existing user by email
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            // Create new user with a default password if none provided
            // For logged-in users from frontend, we still need to create a user record
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => !empty($request->password) ? Hash::make($request->password) : Hash::make('default_password_' . rand(100000, 999999)),
            ]);
        } else {
            // Update name if different
            if ($user->name !== $request->name) {
                $user->update(['name' => $request->name]);
            }
        }

        // Register for event
        DB::table('event_user')->insert([
            'event_id' => $request->event_id,
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'registered_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Welcome ' . $request->name,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Registration error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    
    public function cancel(Request $request)
    {
        $eventId = $request->event_id;
        $email = $request->email;

        $exists = DB::table('event_user')
            ->where('event_id', $eventId)
            ->where('email', $email)
            ->exists();

        if (!$exists) {
            return response()->json(['success' => false, 'message' => 'Not registered']);
        }

        DB::table('event_user')
            ->where('event_id', $eventId)
            ->where('email', $email)
            ->delete();

        return response()->json(['success' => true]);
    }
    

    public function guard()
    {
        if (!auth('admin')->check()) {
            return redirect('/admin/login')->with('error', 'Please login first');
        }

        return null;
    }
}