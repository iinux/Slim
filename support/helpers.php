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
     * @param  string $abstract
     * @param  array $parameters
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
    function addOrdinalNumberSuffix($num)
    {
        if (!in_array(($num % 100), sarray(11, 12, 13))) {
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:
                    return $num . 'st';
                case 2:
                    return $num . 'nd';
                case 3:
                    return $num . 'rd';
            }
        }
        return $num . 'th';
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

if (!function_exists('putenv')) {
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false && isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        preg_match('/"(.*)"/', $value, $matches);
        if ($matches) {
            return $matches[1];
        }

        return $value;
    }
}

if (!function_exists('gpack')) {
    function gpack($data)
    {
        $data = json_encode($data);
        if ($data === false) {
            return 'json_encode error(' . json_last_error() . '):' . json_last_error_msg();
        }
        $data = gzencode($data, 9);
        if ($data === false) {
            return 'gzencode error';
        }
        $data = base64_encode($data);
        if ($data === false) {
            return 'base64_encode error';
        }
        return $data;
    }
}

if (!function_exists('gunpack')) {
    function gunpack($data)
    {
        $data = base64_decode($data);
        if ($data === false) {
            dd("base64_decode error ($data)");
        }
        $data = gzdecode($data);
        if ($data === false) {
            dd("gzdecode error ($data)");
        }
        $data = json_decode($data, true);
        if ($data === false) {
            dd('json_decode error(' . json_last_error() . '):' . json_last_error_msg());
        }
        return $data;
    }
}
