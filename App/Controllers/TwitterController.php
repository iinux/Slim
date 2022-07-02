<?php

namespace App\Controllers;

class TwitterController extends Controller
{
    public function trump()
    {
        return $this->curl('http://twitter.com/realdonaldtrump');
    }
}
