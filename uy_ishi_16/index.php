<?php

declare(strict_types=1);

require 'vendor/autoload.php';
require 'bot.php';

use GuzzleHttp\Client;

$info = new Convertor("root","Valijon9601!","telegram_bot","localhost");
$info->connect();

$token = "7436426976:AAGeFKgV5JLIEAoXj2sBIIQtHxx5R45CIII";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);
$currency = new Client(['base_uri' => 'https://cbu.uz/oz/arkhiv-kursov-valyut/json/']);

$update = json_decode(file_get_contents('php://input'), true);

if(isset($update)){
    if(isset($update['message'])){
        $message = $update['message'];
        $chat_id = $message['chat']['id'];
        $text = $message['text'];

        $exp = explode(':', $text);
        $data = json_decode($currency->get('')->getBody()->getContents(), true);

        $currencies = [];

        foreach ($data as $item) {
            $currencies[strtolower($item['Ccy'])] = $item['Rate'];
        }
        
        $info->insertData($chat_id, $exp[0] . ":" . $exp[1], (string)(round((float)($exp[2]) / $currencies[strtolower($exp[1])],2)));

        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => round($exp[2] / $currencies[strtolower($exp[1])],2) . " " . $exp[1],
            ],
        ]);

    }
}

$rows = $info->fetchAllRows();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1, h3 {
            display: inline;
            margin-right: 20px;
        }

        h1 {
            font-size: 2em;
            color: #007bff;
        }

        h3 {
            font-size: 1.2em;
            color: #6c757d;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h3 {
            display: inline;
            margin-right: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Money Converter</h1><h3>1 USD = 12765 UZS</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ChatId</th>
                    <th>Conversion Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
                <?php foreach($rows as $row):?>
                    <tr>
                        <th><?php echo $row['id'] ?></th>
                        <th><?php echo $row['UserId'] ?></th>
                        <th><?php echo $row['convertation'] ?></th>
                        <th><?php echo $row['amount'] ?></th>
                        <th><?php echo $row['date'] ?></th>
                    </tr>
                <?php endforeach?>
                
            </thead>
            
        </table>
    </div>
</body>
</html>
