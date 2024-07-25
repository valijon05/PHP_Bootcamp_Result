<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$router = new Router();

if($router->isApiCall()) {
    return;
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {

    if($router->getResourceId()){
        echo 'Task'.$router->getResourceId();
        return;
    }

    echo 'all tasks';
    return;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo 'Add new resource';
}



