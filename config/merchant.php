<?php

return [

    /*
     * Laravel-merchant name.
     */
    'name' => 'Laravel-merchant',

    /*
     * Logo in merchant panel header.
     */
    'logo' => '<b>Laravel</b> merchant',

    /*
     * Mini-logo in merchant panel header.
     */
    'logo-mini' => '<b>La</b>',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'merchant',

        'namespace' => 'App\\Http\\Controllers\\Merchant',

        'middleware' => ['web', 'merchant'],
    ],

    /*
     * Laravel-merchant install directory.
     */
    'directory' => app_path('Http/Merchant'),

    /*
     * Laravel-merchant html title.
     */
    'title' => 'Admin',

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * Laravel-merchant auth setting.
     */
    'auth' => [
        'guards' => [
            'merchant' => [
                'driver'   => 'session',
                'provider' => 'merchant',
            ],
        ],

        'providers' => [
            'merchant' => [
                'driver' => 'eloquent',
                'model'  => ShaoZeMing\Merchant\Auth\Database\Administrator::class,
            ],
        ],
    ],

    /*
     * Laravel-merchant upload setting.
     */
    'upload' => [

        'disk' => 'merchant',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * Laravel-merchant database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'merchant_users',
        'users_model' => ShaoZeMing\Merchant\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'merchant_roles',
        'roles_model' => ShaoZeMing\Merchant\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'merchant_permissions',
        'permissions_model' => ShaoZeMing\Merchant\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'merchant_menu',
        'menu_model' => ShaoZeMing\Merchant\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'merchant_operation_log',
        'user_permissions_table' => 'merchant_user_permissions',
        'role_users_table'       => 'merchant_role_users',
        'role_permissions_table' => 'merchant_role_permissions',
        'role_menu_table'        => 'merchant_role_menu',
    ],

    /*
     * By setting this option to open or close operation log in laravel-merchant.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: merchant/auth/logs
         * or specific method to path like: get:merchant/auth/logs
         */
        'except' => [
            'merchant/auth/logs*',
        ],
    ],

    /*
     * @see https://adminlte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.5.x-dev',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],
];
