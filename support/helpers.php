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

if (!function_exists('gencrypt')) {
    function gencrypt($plaintext, $key)
    {
        $key = hash('sha512', $key);
        $ivLen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivLen);
        $cipherTextRaw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipherTextRaw, $key, $as_binary = true);
        $cipherText = base64_encode($iv . $hmac . $cipherTextRaw);
        return $cipherText;
    }
}

if (!function_exists('gdecrypt')) {
    function gdecrypt($cipherText, $key)
    {
        $key = hash('sha512', $key);
        $c = base64_decode($cipherText);
        $ivLen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivLen);
        $hmac = substr($c, $ivLen, $sha2len = 32);
        $cipherTextRaw = substr($c, $ivLen + $sha2len);
        $originalPlaintext = openssl_decrypt($cipherTextRaw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcHmac = hash_hmac('sha256', $cipherTextRaw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcHmac))//PHP 5.6+ timing attack safe comparison
        {
            return $originalPlaintext;
        } else {
            return 'hash error';
        }
    }
}

if (!function_exists('gpack')) {
    function gpack($data, $key = 'php')
    {
        $data = json_encode($data);
        if ($data === false) {
            return 'json_encode error(' . json_last_error() . '):' . json_last_error_msg();
        }
        $data = gzencode($data, 9);
        if ($data === false) {
            return 'gzencode error';
        }
        $data = gencrypt($data, $key);
        return $data;
    }
}

if (!function_exists('gunpack')) {
    function gunpack($data, $key = 'php')
    {
        $data = gdecrypt($data, $key);
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
