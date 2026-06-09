<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    // GET /notifications?user_id=123
    public function index(Request $request)
{
    
    try {
       
        $allNotifications = \DB::table('notifications')->get();
        
        \Log::info('All notifications in DB:', ['count' => $allNotifications->count(), 'data' => $allNotifications]);
        
       
        $userId = $request->query('user_id', 6);
        
        $notifications = \DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        \Log::info('Notifications for user_id ' . $userId, ['count' => $notifications->count()]);
        
        return response()->json($notifications);
        
    } catch (\Exception $e) {
        \Log::error('Notification error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    // POST /notifications/{id}/read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // POST /notifications/read-all
    public function markAllAsRead(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();
        
        if (!$user) {
            $user = auth('web')->user();
        }
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        // Mark all unread notifications as read
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    // POST /admin/notifications (admin panel)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'type' => 'nullable|string',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $validated['type'] ?? 'reply',
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'notification' => $notification]);
    }

    // POST /admin/notify (simple version from anywhere)
    public function storeFromAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'nullable|string'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $request->type ?? 'reply',
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'notification' => $notification]);
    }
}