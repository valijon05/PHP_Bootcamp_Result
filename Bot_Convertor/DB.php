<?php

declare(strict_types=1);

class DB
{
    public static function connect(): PDO
    {
        return new PDO('mysql:host=localhost;dbname=telegram_bot', 'root', 'Valijon9601!');
    }
}