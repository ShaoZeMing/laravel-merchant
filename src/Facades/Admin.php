<?php

namespace ShaoZeMing\Merchant\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Admin.
 *
 * @method static \ShaoZeMing\Merchant\Grid grid($model, \Closure $callable)
 * @method static \ShaoZeMing\Merchant\Form form($model, \Closure $callable)
 * @method static \ShaoZeMing\Merchant\Tree tree($model, \Closure $callable = null)
 * @method static \ShaoZeMing\Merchant\Layout\Content content(\Closure $callable = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void css($css = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void js($js = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void script($script = '')
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null user()
 * @method static string title()
 * @method static void navbar(\Closure $builder = null)
 * @method static void registerAuthRoutes()
 * @method static void extend($name, $class)
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ShaoZeMing\Merchant\Merchant::class;
    }
}
