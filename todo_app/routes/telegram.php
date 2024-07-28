<?php

declare(strict_types=1);

require 'config.php';

$bot = new Bot($token);

$router = new Router();

if (isset($router->getUpdate()->message)) {
    $message = $router->getUpdate()->message;
    $chatId  = $message->chat->id;
    $text    = $message->text;

    if ($text === "/start") {
        $bot->handleStartCommand($chatId);
        return;
    }

    if ($text === "/add") {
        $bot->handleAddCommand($chatId);
        return;
    }

    if ($text === "/all") {
        $bot->getAllTasks($chatId);
        return;
    }

    $bot->addTask($chatId, $text);
}

if (isset($router->getUpdate()->callback_query)) {
    $callbackQuery = $router->getUpdate()->callback_query;
    $callbackData  = (int) $callbackQuery->data;
    $chatId        = $callbackQuery->message->chat->id;
    $messageId     = $callbackQuery->message->message_id;

    $bot->handleInlineButton($chatId, $callbackData);
}
