<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Admin;

class Dashboard
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('merchant::dashboard.title');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP version',       'value' => 'PHP/'.PHP_VERSION],
            ['name' => 'Laravel version',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => array_get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver',      'value' => config('cache.default')],
            ['name' => 'Session driver',    'value' => config('session.driver')],
            ['name' => 'Queue driver',      'value' => config('queue.default')],

            ['name' => 'Timezone',          'value' => config('app.timezone')],
            ['name' => 'Locale',            'value' => config('app.locale')],
            ['name' => 'Env',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];

        return view('merchant::dashboard.environment', compact('envs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function extensions()
    {
        $extensions = [
            'helpers' => [
                'name' => 'laravel-merchant-ext/helpers',
                'link' => 'https://github.com/laravel-merchant-extensions/helpers',
                'icon' => 'gears',
            ],
            'log-viewer' => [
                'name' => 'laravel-merchant-ext/log-viewer',
                'link' => 'https://github.com/laravel-merchant-extensions/log-viewer',
                'icon' => 'database',
            ],
            'backup' => [
                'name' => 'laravel-merchant-ext/backup',
                'link' => 'https://github.com/laravel-merchant-extensions/backup',
                'icon' => 'copy',
            ],
            'config' => [
                'name' => 'laravel-merchant-ext/config',
                'link' => 'https://github.com/laravel-merchant-extensions/config',
                'icon' => 'toggle-on',
            ],
            'api-tester' => [
                'name' => 'laravel-merchant-ext/api-tester',
                'link' => 'https://github.com/laravel-merchant-extensions/api-tester',
                'icon' => 'sliders',
            ],
            'media-manager' => [
                'name' => 'laravel-merchant-ext/media-manager',
                'link' => 'https://github.com/laravel-merchant-extensions/media-manager',
                'icon' => 'file',
            ],
            'scheduling' => [
                'name' => 'laravel-merchant-ext/scheduling',
                'link' => 'https://github.com/laravel-merchant-extensions/scheduling',
                'icon' => 'clock-o',
            ],
            'reporter' => [
                'name' => 'laravel-merchant-ext/reporter',
                'link' => 'https://github.com/laravel-merchant-extensions/reporter',
                'icon' => 'bug',
            ],
            'translation' => [
                'name' => 'laravel-merchant-ext/translation',
                'link' => 'https://github.com/laravel-merchant-extensions/translation',
                'icon' => 'language',
            ],
        ];

        foreach ($extensions as &$extension) {
            $name = explode('/', $extension['name']);
            $extension['installed'] = array_key_exists(end($name), Admin::$extensions);
        }

        return view('merchant::dashboard.extensions', compact('extensions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dependencies()
    {
        $json = file_get_contents(base_path('composer.json'));

        $dependencies = json_decode($json, true)['require'];

        return view('merchant::dashboard.dependencies', compact('dependencies'));
    }
}
