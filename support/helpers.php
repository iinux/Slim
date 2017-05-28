<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/28
 * Time: 17:01
 */

if (!function_exists('slim_app')) {
    /**
     * Get the available slim container instance.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     * @return mixed|\Illuminate\Foundation\Application
     */
    function slim_app($abstract = null, array $parameters = [])
    {
        global $app;
        $container = $app->getContainer();
        if (is_null($abstract)) {
            return $container;
        }

        return empty($parameters)
            ? $container->get($abstract)
            : $container->get($abstract);
    }
}