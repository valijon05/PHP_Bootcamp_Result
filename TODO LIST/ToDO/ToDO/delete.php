<?php
require 'DB.php';
require 'Todo.php';

$pdo = DB::connect();
$todo = new Todo($pdo);
$todo->deleteTodo($_GET['id']);
header('Location: index.php');
exit;
