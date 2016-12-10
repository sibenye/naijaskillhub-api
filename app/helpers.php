<?php
/**
 * Laravel helper functions that
 * do not natively exist in lumen.
 */
if (!function_exists('public_path')) {

    /**
     * Return the path to public dir
     * @param null $path
     * @return string
     */
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}