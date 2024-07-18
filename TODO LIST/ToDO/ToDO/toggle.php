<?php
require 'DB.php';
require 'Todo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = DB::connect();
    $todo = new Todo($pdo);
    $todo->toggleTodoStatus((int)$_POST['id']);
    header('Location: index.php');
    exit;
}
