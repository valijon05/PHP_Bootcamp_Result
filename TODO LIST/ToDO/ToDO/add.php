<?php
require 'DB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = DB::connect();

    $stmt = $pdo->prepare('INSERT INTO todos (title) VALUES (?)');
    $stmt->execute([$_POST['title']]);

    header('Location: index.php');
    
    exit;
}