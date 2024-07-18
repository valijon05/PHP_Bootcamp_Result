<?php

declare(strict_types=1);

class DB
{
    public static function connect(): PDO
    {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=todo_db",
            'root',
            'Valijon9601!',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
        return $pdo;
    }
}
