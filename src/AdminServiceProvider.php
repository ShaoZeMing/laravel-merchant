<?php

namespace ShaoZeMing\Merchant;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'ShaoZeMing\Merchant\Console\MakeCommand',
        'ShaoZeMing\Merchant\Console\MenuCommand',
        'ShaoZeMing\Merchant\Console\InstallCommand',
        'ShaoZeMing\Merchant\Console\UninstallCommand',
        'ShaoZeMing\Merchant\Console\ImportCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'merchant.auth'       => \ShaoZeMing\Merchant\Middleware\Authenticate::class,
        'merchant.pjax'       => \ShaoZeMing\Merchant\Middleware\Pjax::class,
        'merchant.log'        => \ShaoZeMing\Merchant\Middleware\LogOperation::class,
        'merchant.permission' => \ShaoZeMing\Merchant\Middleware\Permission::class,
        'merchant.bootstrap'  => \ShaoZeMing\Merchant\Middleware\Bootstrap::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'merchant' => [
            'merchant.auth',
            'merchant.pjax',
            'merchant.log',
            'merchant.bootstrap',
            'merchant.permission',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'merchant');

        if (file_exists($routes = merchant_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'laravel-merchant-config');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'laravel-merchant-lang');
//            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/merchant')],           'laravel-merchant-views');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'laravel-merchant-migrations');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-merchant')], 'laravel-merchant-assets');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('merchant.auth', []), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
