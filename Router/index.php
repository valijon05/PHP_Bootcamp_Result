<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$router = new Router();

echo $router->isApiCall();


