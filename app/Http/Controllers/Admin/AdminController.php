<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Event;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    // ===== Dashboard =====
    public function dashboard()
    {
        $admin = auth('admin')->user();
        
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Please login first to access this page.');
        }

        if (!$admin->hasRole('super_admin') && !$admin->hasPermissionTo('view_dashboard')) {
            if ($admin->hasPermissionTo('view_events')) {
                return redirect()->route('admin.events.index');
            }
            return response()->view('admin.unauthorized', [], 403);
        }

        $totalUsers     = User::count();
        $totalEvents    = Event::count();
        $upcomingEvents = Event::where('event_date', '>=', now())->count();
        $registrations  = DB::table('event_user')->count();

        $superAdmins  = $admin->hasRole('super_admin') ? Admin::role('super_admin')->count() : null;
        $normalAdmins = $admin->hasRole('super_admin') ? Admin::role('manager')->count() : null;
        $revenue      = $admin->hasRole('super_admin') ? (DB::table('event_user')
                            ->join('events', 'events.id', '=', 'event_user.event_id')
                            ->selectRaw('SUM(COALESCE(events.price,0)) as total')
                            ->value('total') ?? 0) : null;

        $admins = Admin::with('roles', 'permissions')->orderBy('created_at', 'desc')->get();
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'superAdmins', 'normalAdmins',
            'upcomingEvents', 'registrations', 'revenue', 'admins', 'events'
        ));
    }

    // ===== Index =====
    public function index()
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You should login first');
        }

        if (!$admin->hasRole('super_admin')) {
            return redirect('/admin/events')->with('error', 'Unauthorized access');
        }

        $admins = Admin::with('roles', 'permissions')->orderBy('created_at', 'desc')->get();
        
        $adminsData = [];
        foreach ($admins as $adminItem) {
            $role = $adminItem->roles->first();
            $roleName = $role ? $role->name : 'viewer';
            
            $permissions = [];
            
            if ($role && $role->permissions) {
                foreach ($role->permissions as $perm) {
                    $permissions[] = $perm->name;
                }
            }
            
            if ($adminItem->permissions && $adminItem->permissions->count() > 0) {
                foreach ($adminItem->permissions as $perm) {
                    if (!in_array($perm->name, $permissions)) {
                        $permissions[] = $perm->name;
                    }
                }
            }
            
            $adminsData[] = (object) [
                'id' => $adminItem->id,
                'name' => $adminItem->name,
                'email' => $adminItem->email,
                'created_at' => $adminItem->created_at,
                'role_name' => $roleName,
                'permissions' => $permissions,
            ];
        }
        
        $currentCanCreate = $admin->hasRole('super_admin');

        return view('admin.admins.index', compact('adminsData', 'currentCanCreate'));
    }

    // ===== Store =====
    public function store(Request $request)
    {
        $currentAdmin = auth('admin')->user();

        if (!$currentAdmin->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:admins,email',
            'password'         => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role'             => 'required|string|max:100',
            'permissions'      => 'nullable|array',
            'permissions.*'    => 'string',
            'custom_role_name' => 'nullable|string|max:100',
        ]);

        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = $this->resolveRole($request->role, $request->custom_role_name);
        $admin->assignRole($role);

        $permissions = $this->resolvePermissions($request->role, $request->permissions ?? []);
        $admin->syncPermissions($permissions);

        return response()->json(['success' => true]);
    }

    // ===== Update =====
    public function update(Request $request, $id)
    {
        $currentAdmin = auth('admin')->user();

        if (!$currentAdmin->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:admins,email,' . $id,
            'password'         => ['nullable', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role'             => 'required|string|max:100',
            'permissions'      => 'nullable|array',
            'permissions.*'    => 'string',
            'custom_role_name' => 'nullable|string|max:100',
        ]);

        $admin->update([
            'name'  => $request->name,
            'email' => $request->email,
            ...($request->filled('password') ? ['password' => Hash::make($request->password)] : []),
        ]);

        $role = $this->resolveRole($request->role, $request->custom_role_name);
        $admin->syncRoles([$role]);

        $permissions = $this->resolvePermissions($request->role, $request->permissions ?? []);
        $admin->syncPermissions($permissions);

        return response()->json(['success' => true]);
    }

    // ===== Update Permissions =====
    public function updatePermissions(Request $request, $id)
    {
        $currentAdmin = auth('admin')->user();

        if (!$currentAdmin->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $admin = Admin::findOrFail($id);

        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $admin->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true]);
    }

    // ===== Destroy =====
    public function destroy($id)
    {
        $currentAdmin = auth('admin')->user();

        if (!$currentAdmin->hasRole('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        if ($admin->id === auth('admin')->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete yourself'], 403);
        }

        if ($admin->hasRole('super_admin') && Admin::role('super_admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'Cannot delete last Super Admin'], 403);
        }

        $admin->roles()->detach();
        $admin->permissions()->detach();
        $admin->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    // ===== Edit (get admin data for edit form) =====
    public function edit($id)
    {
        $admin = Admin::with('roles', 'permissions')->find($id);
        
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Admin not found'], 404);
        }
        
        $role = $admin->roles->first();
        $roleName = $role ? $role->name : 'viewer';
        
        $permissions = [];
        if ($role && $role->permissions) {
            foreach ($role->permissions as $perm) {
                $permissions[] = $perm->name;
            }
        }
        
        if ($admin->permissions && $admin->permissions->count() > 0) {
            foreach ($admin->permissions as $perm) {
                if (!in_array($perm->name, $permissions)) {
                    $permissions[] = $perm->name;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $roleName,
            'permissions' => $permissions
        ]);
    }

    // ===== Get Permissions for view modal =====
    public function getPermissions($id)
    {
        $admin = Admin::with('roles', 'permissions')->find($id);
        
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Admin not found'], 404);
        }
        
        $role = $admin->roles->first();
        $permissions = [];
        
        if ($role && $role->permissions) {
            foreach ($role->permissions as $perm) {
                $permissions[] = $perm->name;
            }
        }
        
        if ($admin->permissions && $admin->permissions->count() > 0) {
            foreach ($admin->permissions as $perm) {
                if (!in_array($perm->name, $permissions)) {
                    $permissions[] = $perm->name;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }

    // =============================================
    // Helper: Role
    // =============================================
    private function resolveRole(string $roleInput, ?string $customRoleName): Role
    {
        $predefined = ['super_admin', 'manager', 'events_manager', 'viewer'];

        if (in_array($roleInput, $predefined)) {
            return Role::where('name', $roleInput)
                       ->where('guard_name', 'admin')
                       ->firstOrFail();
        }

        $name = $customRoleName ?: $roleInput;
        $name = strtolower(trim(str_replace(' ', '_', $name)));

        return Role::firstOrCreate([
            'name'       => $name,
            'guard_name' => 'admin',
        ]);
    }

    // =============================================
    // Helper: Permissions
    // =============================================
    private function resolvePermissions(string $roleInput, array $requestPermissions): array
    {
        $predefined = ['super_admin', 'manager', 'events_manager', 'viewer'];

        if (in_array($roleInput, $predefined)) {
            $role = Role::where('name', $roleInput)
                        ->where('guard_name', 'admin')
                        ->first();

            return $role ? $role->permissions->pluck('name')->toArray() : [];
        }

        $groupMapping = [
            'manage_events' => ['view_events', 'create_events', 'edit_events', 'delete_events'],
            'manage_users'  => ['view_users', 'manage_users'],
            'manage_admins' => ['view_admins', 'manage_admins']
        ];
        
        $finalPermissions = [];
        
        foreach ($requestPermissions as $perm) {
            if (isset($groupMapping[$perm])) {
                foreach ($groupMapping[$perm] as $individualPerm) {
                    if (!in_array($individualPerm, $finalPermissions)) {
                        $finalPermissions[] = $individualPerm;
                    }
                }
            } else {
                if (!in_array($perm, $finalPermissions)) {
                    $finalPermissions[] = $perm;
                }
            }
        }
        
        $requiredPermissions = ['view_dashboard'];
        
        foreach ($requiredPermissions as $required) {
            if (!in_array($required, $finalPermissions)) {
                $finalPermissions[] = $required;
            }
        }

        return $finalPermissions;
    }
}