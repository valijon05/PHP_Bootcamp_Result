<?php
require 'vendor/autoload.php';
require 'src/DB.php';
require 'src/Todo.php';

ate_default_timezone_set("Asia/Tashkent");

$database = DB::connect();
$todo = new Todo($database);
$todos = $todo->getTodos();

$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
    if(isset($update->update_id))
    require 'bot/bot.php';
    return;
}

d($_SERVER['REQUEST_URI']);


if ($_SERVER['REQUEST_URI'] === '/add'){
    d($task->getadd());
}



if ($_SERVER['REQUEST_URI'] === '/tasks'){
    d($task->getAll());
}





require 'view/html.php';

?>
