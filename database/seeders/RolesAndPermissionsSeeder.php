<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // =========================
        // Permissions
        // =========================

        $permissions = [
            // Dashboard
            'view_dashboard',

            // Events
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',

            // Users
            'view_users',
            'manage_users',

            // Admins
            'view_admins',
            'manage_admins',

            // Reviews
            'view_reviews',
            'delete_reviews',

            // Messages
            'view_messages',
            'delete_messages',

            // Reports (optional)
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // =========================
        // Roles
        // =========================

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin'
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'admin'
        ]);

        $eventsManager = Role::firstOrCreate([
            'name' => 'events_manager',
            'guard_name' => 'admin'
        ]);

        $viewer = Role::firstOrCreate([
            'name' => 'viewer',
            'guard_name' => 'admin'
        ]);

        // =========================
        // Assign Permissions
        // =========================

        // Super Admin - ALL permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Manager - Can manage events, users, view reviews & messages
        $manager->givePermissionTo([
            'view_dashboard',
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'view_users',
            'manage_users',
            'view_reviews',
            'delete_reviews',
            'view_messages',
            'delete_messages',
            'view_reports',
        ]);

        // Events Manager - Can manage events, view reviews & messages
        $eventsManager->givePermissionTo([
            'view_dashboard',
            'view_events',
            'create_events',
            'edit_events',
            'delete_events',
            'view_reviews',
            'delete_reviews',
            'view_messages',
            'delete_messages',
            'view_reports',
        ]);

        // Viewer - Read-only access
        $viewer->givePermissionTo([
            'view_dashboard',
            'view_events',
            'view_users',
            'view_reviews',
            'view_messages',
            'view_reports',
        ]);
    }
}