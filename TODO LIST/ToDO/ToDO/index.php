<?php
    declare(strict_types=1);
    require 'bot.php';  
    require 'DB.php';
    require 'Todo.php';
    require 'toggle.php';
    $database = DB::connect();
    $todo = new Todo($database);
    $todos = $todo->getTodos();
    

    use GuzzleHttp\Client;
    

    $bot = new Bot();

    if(isset($update->$message)){
        $message = $update->$message;
        $chatId =$message->chat->id;
        $text = $message->text;
        $bot->handleStartCommand($chatId);

        if($message->text ==='/start'){
            $client->post('sendMessage',[
                'from_params'=> [
                    'chat_id' => $chat_id,
                    'text' => 'Qonday ey',
                ]
                ]);
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>To-do List</title>
    <style>


        .completed {
            text-decoration: line-through;
        }

        <?php require 'style.css'; ?>

    </style>
</head>

<body>

<div class="container">
    <h1 class="mt-5">TODO LIST</h1>
    <form action="add.php" method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="title" class="form-control" placeholder="Enter text" required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Add</button>
            </div>
        </div>
    </form>
    <ul class="list-group">
        <?php foreach ($todos as $todo): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <form action="toggle.php" method="POST" class="mr-3">
                    <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                    <input type="checkbox"
                           onChange="this.form.submit()" <?php if ($todo['completed']) echo 'checked'; ?>>
                </form>
                <span class="<?php echo $todo['completed'] ? 'completed' : ''; ?>">
                    <?php echo htmlspecialchars($todo['title']); ?>
                </span>
                <a href="delete.php?id=<?php echo $todo['id']; ?>" class="btn btn-danger btn-sm">Remove</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>

</html>
