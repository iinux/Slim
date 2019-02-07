<?php
/**
 * Created by PhpStorm.
 * User: qzhang
 * Date: 2018/4/17
 * Time: 9:17
 */

namespace App\Libs;

class Curl
{
    protected $optArray = [];
    protected $ch;

    function __construct($curlSocks5 = true)
    {
        $this->ch = curl_init();
        if (empty(ini_get('open_basedir'))) {
            $this->addOpt(CURLOPT_FOLLOWLOCATION, true);
        }

        $this->curlSocks5 = env('CURL_SOCKS5');
        if ($curlSocks5 && $this->curlSocks5) {
            $this->optArray[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5_HOSTNAME;
            $this->optArray[CURLOPT_PROXY] = $this->curlSocks5;
        }
    }

    function addOpt($option, $value)
    {
        $this->optArray[$option] = $value;
    }

    function setOptArray($optArray)
    {
        $this->optArray = $optArray;
    }

    function close()
    {
        curl_close($this->ch);
    }

    function getError()
    {
        return curl_error($this->ch);
    }

    function getInfo($opt = null)
    {
        return curl_getinfo($this->ch, $opt);
    }

    function getInfoOptNull()
    {
        return curl_getinfo($this->ch);
    }

    function exec()
    {
        foreach ($this->optArray as $option => $value) {
            curl_setopt($this->ch, $option, $value);
        }
        return curl_exec($this->ch);
    }
}
