<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Store a contact form message
     */
    public function store(Request $request)
    {
        if ($request->expectsJson()) {
            // JSON request from fetch
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ]);

            Message::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject ?? 'General Inquiry',
                'message' => $request->message,
                'event_id' => $request->event_id ?? null,
            ]);

            return response()->json(['success' => true, 'message' => 'Message sent!']);
        }
        
        // Regular form submission (fallback)
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject ?? 'General Inquiry',
            'message' => $request->message,
            'event_id' => $request->event_id ?? null,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Get messages for a specific event (admin)
     */
    public function eventMessages(Event $event)
    {
        $messages = Message::where('event_id', $event->id)
            ->latest()
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'name' => $msg->name,
                    'email' => $msg->email,
                    'subject' => $msg->subject,
                    'message' => $msg->message,
                    'event_id' => $msg->event_id,
                    'created_at' => $msg->created_at->toISOString(),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Get all messages (admin)
     */
    public function allMessages()
    {
        $messages = Message::latest()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'name' => $msg->name,
                'email' => $msg->email,
                'subject' => $msg->subject,
                'message' => $msg->message,
                'event_id' => $msg->event_id,
                'created_at' => $msg->created_at->toISOString(),
            ];
        });
        return response()->json($messages);
    }

    /**
     * Delete a message (admin)
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Reply to a message from admin (sends notification to user)
     */
    
    public function reply(Request $request)
{
    try {
        $request->validate([
            'to_email' => 'required|email',
            'to_name' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'original_message_id' => 'nullable|integer',
            'event_id' => 'nullable|integer'
        ]);

        // Find the user by email
        $user = User::where('email', $request->to_email)->first();
        
        // Create notification for the user (inside website)
        if ($user) {
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->type = 'admin_reply';
            $notification->title = '📧 ' . $request->subject;
            $notification->message = "Hello " . $request->to_name . ",\n\n" . $request->message . "\n\n— Admin Team\n\n" . config('app.name', 'EventHub');
            $notification->is_read = false;
            $notification->save();
        }
        
        // Save the reply in the database (without is_reply and parent_id)
        $reply = new Message();
        $reply->name = Auth::guard('admin')->user()->name ?? 'Admin';
        $reply->email = Auth::guard('admin')->user()->email ?? 'admin@eventhub.com';
        $reply->subject = $request->subject;
        $reply->message = $request->message;
        $reply->event_id = $request->event_id;
        // Remove these two lines if columns don't exist:
        // $reply->is_reply = true;
        // $reply->parent_id = $request->original_message_id;
        $reply->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully. User will receive notification.'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}