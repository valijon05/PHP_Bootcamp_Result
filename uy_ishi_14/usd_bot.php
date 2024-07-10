<?php
declare(strict_types=1);

require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'http://google.com']);

$response = $client->request('GET', '/');

var_dump($response->getBody()->getContents());

?>
