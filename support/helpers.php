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

if (!function_exists('addOrdinalNumberSuffix')) {
    function addOrdinalNumberSuffix($num) {
        if (!in_array(($num % 100), sarray(11,12,13))){
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:    return $num.'st';
                case 2:    return $num.'nd';
                case 3:    return $num.'rd';
            }
        }
        return $num.'th';
    }
}

if (!function_exists('authUser')) {
    function authUser($refresh = false)
    {
        static $authUser = null;
        if (is_null($authUser)) {
            $authUser = unserialize($_SESSION['eloquent']);
        }
        if ($authUser === false) {
            return null;
        } else {
            if ($refresh) {
                $authUser = \App\Models\User::findOrFail($authUser->id);
                $_SESSION['eloquent'] = serialize($authUser);
            }
            return $authUser;
        }
    }
}
