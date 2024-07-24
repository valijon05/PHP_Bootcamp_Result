<?php

declare(strict_types=1);

use GuzzleHttp\Client;

require 'vendor/autoload.php';
require 'src/User.php';

$user = new User();

$token = "7411716108:AAHie4mj97bbY6VWcUppRULe_aCOI7fCysY";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

if (isset($update->message)) {
    $message = $update->message->text;
    $chat_id = $update->message->chat->id;
    $text = $update->message->text;

    $keyboard = [
        ['Add Task', 'Get All Tasks'],
        ['Check Task', 'Uncheck Task'],
        ['Delete Task']
    ];

    $reply_markup = json_encode([
        'keyboard' => $keyboard,
        'resize_keyboard' => true,
        'one_time_keyboard' => true
    ]);

    if ($text === '/start') {
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Welcome! Choose an option:',
                'reply_markup' => $reply_markup
            ]
        ]);
        return;
    }

    if ($text === 'Add Task') {
        $user->addTask('add');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the task'
            ]
        ]);
        return;
    }

    if ($text === 'Check Task') {
        $user->checkTask('check');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the number to check'
            ]
        ]);
        return;
    }

    if ($text === 'Uncheck Task') {
        $user->uncheckTask('uncheck');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the number to uncheck'
            ]
        ]);
        return;
    }

    if ($text === 'Get All Tasks') {
        $tasks = $user->SendAllUsers();
        $responseText = '';
        $count = 1;

        foreach ($tasks as $task) {
            if ($task['completed'] == 1) {
                $responseText .= $count . ': <del>' . $task['title'] . '</del>' . "\n";
            } else {
                $responseText .= $count . ': ' . $task['title'] . "\n";
            }
            $count++;
        }

        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $responseText,
                'parse_mode' => 'HTML'
            ]
        ]);
        return;
    }

    if ($text === 'Delete Task') {
        $user->writeDelete('delete');
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Enter the number to delete'
            ]
        ]);
        return;
    }
}

if ($text) {
    $add = $user->getAdd();
    if ($add[0]['add'] == 'add') {
        $user->saveAdd($text);
        $user->deleteAdd();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task added successfully'
            ]
        ]);
        return;
    }

    $check = $user->getCheck();
    if ($check[0]['check'] == 'check') {
        $user->saveCheck((int)$text);
        $user->deleteCheck();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task checked successfully'
            ]
        ]);
        return;
    }

    $uncheck = $user->getUncheck();
    if ($uncheck[0]['uncheck'] == 'uncheck') {
        $user->saveUncheck((int)$text);
        $user->deleteUncheck();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task unchecked successfully'
            ]
        ]);
        return;
    }

    $delete = $user->getDelete();
    if ($delete[0]['delete'] == 'delete') {
        $user->saveDelete((int)$text - 1);
        $user->dropDelete();
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Task deleted successfully'
            ]
        ]);
        return;
    }
}
