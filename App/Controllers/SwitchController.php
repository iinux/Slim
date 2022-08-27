<?php

namespace App\Controllers;

use Predis\Client;
use Slim\Http\Request;
use Slim\Http\Response;

class SwitchController extends Controller
{
    protected $redis;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->redis = new Client();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update($request, $response, $args)
    {
        $key = $this->getRedisKey($args['id']);
        $this->redis->sadd($key, $args['content']);
        $this->redis->expire($key, 300);

        return $response->withJson(['code' => 0, 'data' => $this->redis->smembers($key)]);
    }

    protected function getRedisKey($id)
    {
        return 'switch_' . $id;
    }
}
