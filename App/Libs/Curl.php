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

    function __construct()
    {
        $this->ch = curl_init();
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
