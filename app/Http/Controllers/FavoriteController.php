<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Event $event)
    {
        $userId = $request->user_id;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->favoriteEvents()->where('event_id', $event->id)->exists()) {
            $user->favoriteEvents()->detach($event->id);
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from favorites'
            ]);
        } else {
            $user->favoriteEvents()->attach($event->id);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to favorites'
            ]);
        }
    }
    
    public function getUserFavorites(Request $request)
    {
        $userId = $request->query('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'favorites' => []
            ]);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'favorites' => []
            ]);
        }
        
        return response()->json([
            'success' => true,
            'favorites' => $user->favoriteEvents()->pluck('event_id')
        ]);
    }
}