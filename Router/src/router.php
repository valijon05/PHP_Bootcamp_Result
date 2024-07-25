<?php

declare(strict_types=1);

namespace src;

class Router{
        
    private $updates;
    public function __construct(){

        $this->updates = json_decode(file_get_contents('php://input'));

    }

    public function isApiCall(){

        $uri = $_SERVER

        if($isset($this->updates)){
            return 'api request';
        }
    }

}