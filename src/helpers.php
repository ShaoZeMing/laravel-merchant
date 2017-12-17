<?php

if (!function_exists('merchant_path')) {

    /**
     * Get merchant path.
     *
     * @param string $path
     *
     * @return string
     */
    function merchant_path($path = '')
    {
        return ucfirst(config('merchant.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('merchant_url')) {
    /**
     * Get merchant url.
     *
     * @param string $path
     *
     * @return string
     */
    function merchant_url($path = '')
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        return url(merchant_base_path($path));
    }
}

if (!function_exists('merchant_base_path')) {
    /**
     * Get merchant url.
     *
     * @param string $path
     *
     * @return string
     */
    function merchant_base_path($path = '')
    {
        $prefix = '/'.trim(config('merchant.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix.'/'.trim($path, '/');
    }
}

if (!function_exists('merchant_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function merchant_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('merchant_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function merchant_asset($path)
    {
        return asset($path, config('merchant.secure'));
    }
}
