<?php

use Spatie\Permission\DefaultTeamResolver;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */

    'models' => [

        'permission' => Permission::class,

        'role' => Role::class,

        'team' => null,

    ],

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    */

    'table_names' => [

        'roles' => 'roles',

        'permissions' => 'permissions',

        'model_has_permissions' => 'model_has_permissions',

        'model_has_roles' => 'model_has_roles',

        'role_has_permissions' => 'role_has_permissions',

    ],

    /*
    |--------------------------------------------------------------------------
    | Column Names
    |--------------------------------------------------------------------------
    */

    'column_names' => [

        'role_pivot_key' => null,

        'permission_pivot_key' => null,

        'model_morph_key' => 'model_id',

        'team_foreign_key' => 'team_id',

    ],

    /*
    |--------------------------------------------------------------------------
    | Teams
    |--------------------------------------------------------------------------
    */

    'teams' => false,

    'team_resolver' => DefaultTeamResolver::class,

    /*
    |--------------------------------------------------------------------------
    | Passport
    |--------------------------------------------------------------------------
    */

    'use_passport_client_credentials' => false,

    /*
    |--------------------------------------------------------------------------
    | Permission Check Method
    |--------------------------------------------------------------------------
    */

    'register_permission_check_method' => true,

    /*
    |--------------------------------------------------------------------------
    | Octane
    |--------------------------------------------------------------------------
    */

    'register_octane_reset_listener' => false,

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    'events_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Default Guard (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    'defaults' => [

        'guard' => 'admin',

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    'cache' => [

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        'key' => 'spatie.permission.cache',

        'store' => 'default',

    ],

];