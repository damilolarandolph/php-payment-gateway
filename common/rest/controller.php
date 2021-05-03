<?php

namespace Gateway\REST;

use Exception;

abstract class Controller
{

    public function get()
    {
        throw new Exception("METHOD NOT ALLOWED");
    }

    public function post()
    {
        throw new Exception("METHOD NOT ALLOWED");
    }
}
