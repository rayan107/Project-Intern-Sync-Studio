<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index(Event $event)
    {
        $reviews = $event->reviews()
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(function ($review) {
                return [
                    'id'         => $review->id,
                    'rating'     => $review->rating,
                    'comment'    => $review->comment,
                    'user_id'    => $review->user_id,
                    'user_name'  => $review->user->name ?? 'Anonymous',
                    'created_at' => $review->created_at->toISOString(),
                ];
            });

        return response()->json($reviews);
    }

    public function checkUserReview(Event $event, $userId)
    {
        $reviewed = Review::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->exists();
            
        return response()->json(['reviewed' => $reviewed]);
    }

    /**
     * Store a new review - FIXED VERSION
     */
  public function store(Request $request)
{
    try {
        // 1. Get event
        $eventId = $request->input('event_id');
        $event = Event::find($eventId);
        
        if (!$event) {
            return response()->json([
                'success' => false, 
                'message' => 'Event not found'
            ], 404);
        }

        // 2. Get user (prioritize user_id from request)
        $user = null;
        
        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
        }
        
        if (!$user && auth('web')->check()) {
            $user = auth('web')->user();
        }
        
        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'Please login to submit a review'
            ], 401);
        }

        // 3. Check if user is registered for this event
        $isRegistered = $event->users()->where('user_id', $user->id)->exists();
        
        if (!$isRegistered) {
            return response()->json([
                'success' => false, 
                'message' => 'You must register for this event before leaving a review.'
            ], 403);
        }

        // 4. Check if already reviewed
        $existingReview = Review::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false, 
                'message' => 'You have already reviewed this event'
            ], 409);
        }

        // 5. Validate and create review
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $review = Review::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'user_name' => $user->name,
                'created_at' => $review->created_at->toISOString(),
            ]
        ], 201);
        
    } catch (\Exception $e) {
        \Log::error('Review error: ' . $e->getMessage());
        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    public function destroy(Request $request, Review $review)
    {
        if (Auth::guard('admin')->check()) {
            $review->delete();
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.'
            ]);
        }

        $user = Auth::user();
        if (!$user && $request->has('user_id')) {
            $user = User::find($request->user_id);
        }

        if (!$user || $user->id !== $review->user_id) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $review->delete();
        return response()->json([
            'success' => true,
            'message' => 'Your review has been deleted.'
        ]);
    }
}