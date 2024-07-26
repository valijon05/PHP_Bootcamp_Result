<?php

declare(strict_types=1);

$router = new Router();
$task   = new Task();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($router->getResourceId()) {
        $router->sendResponse(
            $task->getTask(
                $router->getResourceId()
            )
        );
        return;
    }
    if($router->getUpdates()-> userId){
        $user_task = $router->sendResponse($taks->getTaksByUser($router->getUpdates()-> userId));
        $router -> sendResponse($user_task);
    }
    $router->sendResponse($task->getAll());
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($router->getUpdate()->text)){
        $router->sendResponse([
            'message' => 'text is not found',
            'code' => 403
        ]);
        return;
    }

    $newTask      = $task->add($router->getUpdates()->text, 35);
    $responseText = $newTask ? 'New task has been added' : 'Something went wrong';
    $router->sendResponse($responseText);


    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    if(!isset($router->getUpdate()->todoId)){
        $router->sendResponse([
            'message' => 'todoId is not found',
            'code' => 403
        ]);
        return;
    
    }

    $task_id = $router->getUpdate()->todoId;
    $single_task = $task->getTask($task_id);
    if($single_task->status){
        $task->uncompleted($task_id);
        $router->sendResponse([
            'message' => 'task is uncompleted',
            'code' => 200
        ]);
        return;
    }

    $task->complete($task_id);
    $router->sendResponse([
        'message' => 'task is completed',
        'code' => 200
    ]);
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if(!isset($router->getUpdate()->todoId)){
        $router->sendResponse([
            'message' => 'todoId is not found',
            'code' => 403
        ]);
        return;
    }

    $task_id = $router->getUpdate()->todoId;
    $task->delete($task_id);
    $router->sendResponse([
        'message' => 'task  deleted',
        'code' => 200
    ]);
    return;
}