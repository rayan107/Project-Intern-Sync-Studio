<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EventImage;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    //display all the events in admin panel 
    public function index()
    {
        $events = Event::with('images')->withCount('users')->latest()->paginate(15);

        // Calculate total revenue from all registrations
        $totalRevenue = DB::table('event_user')
            ->join('events', 'event_user.event_id', '=', 'events.id')
            ->sum('events.price');

        $stats = [
            'totalEvents'        => Event::count(),
            'totalRegistrations' => DB::table('event_user')->count(),
            'upcomingEvents'     => Event::where('event_date', '>', now())->count(),
            'totalRevenue'       => $totalRevenue,
            'mostRegistered'     => Event::withCount('users')->orderBy('users_count','desc')->first()->users_count ?? 0,
            'maxUsers'           => Event::withCount('users')->orderBy('users_count','desc')->first()->users_count ?? 1,
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    public function show($id)
    {
        $event = Event::with('images', 'users')->withCount('users')->findOrFail($id);
        return view('admin.events.index', compact('event'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $event = Event::create([
            'admin_id'    => auth()->guard('admin')->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'location'    => $request->location,
            'price'       => $request->price ?? 0,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                if ($file->isValid()) {
                    $path = $file->store('events', 'public');
                    EventImage::create([
                        'event_id'   => $event->id,
                        'image_path' => $path,
                        'sort_order' => $i,
                    ]);
                }
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'      => 'required',
            'event_date' => 'required|date',
            'price'      => 'required|numeric',
            'start_time' => 'required',
            'end_time'   => 'required',
            'location'   => 'required',
            'images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $event->update($request->only([
            'title','description','event_date','start_time','end_time','location','price'
        ]));

        // Delete images marked for removal
        if ($request->delete_images) {
            foreach ($request->delete_images as $imgId) {
                $img = EventImage::find($imgId);
                if ($img && $img->event_id == $event->id) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            $currentMax = $event->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $i => $file) {
                if ($file->isValid()) {
                    $path = $file->store('events', 'public');
                    EventImage::create([
                        'event_id'   => $event->id,
                        'image_path' => $path,
                        'sort_order' => $currentMax + $i + 1,
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function getImages($id)
    {
        $event = Event::with('images')->findOrFail($id);
        return response()->json($event->images);
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            
            // Delete registrations
            DB::table('event_user')->where('event_id', $id)->delete();
            
            // Delete images
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
            
            $event->delete();
            
            if (request()->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('admin.events.index')
                ->with('success', 'Event deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Error deleting event: ' . $e->getMessage());
        }
    }

    // ============ FIXED: Get registrations with user data AND STATUS ============
    // ============ FIXED: Get registrations with user data AND STATUS ============
// ============ FIXED: Get registrations with user data ============
// ============ FIXED: Get registrations with fallback for missing columns ============
// ============ FIXED: Get registrations with check-in status ============
public function registrations(Event $event)
{
    try {
        // ✅ جلب البيانات مباشرة من event_user مع users
        $registrations = DB::table('event_user')
            ->where('event_id', $event->id)
            ->join('users', 'event_user.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'event_user.registered_at',
                'event_user.checked_in_at',
                'event_user.status',
                'event_user.cancelled_at'
            )
            ->get();

        // ✅ Log للتحقق
        \Log::info('Registrations fetched', [
            'event_id' => $event->id,
            'count' => $registrations->count(),
            'data' => $registrations->toArray()
        ]);

        return response()->json($registrations);
        
    } catch (\Exception $e) {
        \Log::error('Registrations error: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to load registrations',
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function cancel($id)
    {
        $user = auth('web')->user(); 
        $event = Event::findOrFail($id);

        if (!$event->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Not registered'], 400);
        }

        $event->users()->detach($user->id);

        return response()->json(['message' => 'Cancelled successfully']);
    }

    public function getRegistrations($eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            $registrations = $event->users()->get();
            return response()->json($registrations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ============ QR SCANNER: GET EVENT DETAILS ============
    public function getEventDetails($id)
    {
        $event = Event::withCount('users')->findOrFail($id);
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
            'location' => $event->location,
            'price' => $event->price,
            'registrations_count' => $event->users_count ?? 0,
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at,
        ]);
    }
}