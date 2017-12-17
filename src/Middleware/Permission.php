<?php

namespace ShaoZeMing\Merchant\Middleware;

use ShaoZeMing\Merchant\Facades\Admin;
use Illuminate\Http\Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!Admin::user()) {
            return $next($request);
        }

        if (!Admin::user()->allPermissions()->first(function ($permission) use ($request) {
            return $permission->shouldPassThrough($request);
        })) {
            \ShaoZeMing\Merchant\Auth\Permission::error();
        }

        return $next($request);
    }
}
