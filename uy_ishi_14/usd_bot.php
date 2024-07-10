<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = '7436426976:AAGeFKgV5JLIEAoXj2sBIIQtHxx5R45CIII';

$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$response = $client->post('sendMessage', [
    'form_params' => [
        'chat_id' => '6709792443',
        'text' => 'Hello World!',
    ]
]);

$json = $response->getBody()->getContents();

print_r(json_decode($json, true));

?>
