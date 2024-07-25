<?php

declare(strict_types=1);

namespace src;

class Router{
        
    private $updates;
    public function __construct(){

        $this->updates = json_decode(file_get_contents('php://input'));

    }

    public function isApiCall(){

        $uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); 
        $path = explode('/',$uri);
        return array_search('api',$path);
        
    }

    public function getResourceId(){

        $uri        = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); 
        $path       = explode('/',$uri);
        $resourceId = $path[count($path)-1];
        return is_numeric($resourceId) ? $resourceId : false;        
    }

    

}