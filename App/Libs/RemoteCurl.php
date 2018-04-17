<?php
/**
 * Created by PhpStorm.
 * User: qzhang
 * Date: 2018/4/17
 * Time: 9:18
 */

namespace App\Libs;

class RemoteCurl
{
    protected $optArray = [];
    protected $ch;
    protected $response;
    protected $error;

    protected $secretKey;
    protected $remoteCurlProxy;

    function __construct()
    {
        $this->secretKey = env('SECRET_KEY');
        $this->remoteCurlProxy = env('G_PROXY');

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip,deflate');
        if (empty(ini_get('open_basedir'))) {
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_POST, 1);
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
        if (!is_null($this->error)) {
            return $this->error;
        }
        return curl_error($this->ch);
    }

    function getInfo($opt = null)
    {
        return $this->response['headerSize'];
    }

    function getInfoOptNull()
    {
        return $this->response['responseInfo'];
    }

    protected function pack(array $data)
    {
        return gpack($data);
    }

    protected function unpack($data)
    {
        return gunpack($data);
    }

    function exec()
    {
        $data = [
            'key'  => $this->secretKey,
            'data' => $this->pack([
                'optArray'  => $this->optArray,
            ]),
        ];
        $url = "{$this->remoteCurlProxy}/api/science/beta";

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return $output;
        }

        $headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $output = substr($output, $headerSize);
        $data = json_decode($output);
        $this->response = $this->unpack($data->data);
        if ($data->code == -1) {
            $this->error = $this->response['error'];
            return false;
        }
        if ($this->response['responseBase64']) {
            $this->response['response'] = base64_decode($this->response['response']);
        }
        return $this->response['response'];
    }

}