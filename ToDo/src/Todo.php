<?php

class Todo
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTodos()
    {
        $stmt = $this->pdo->query('SELECT * FROM todos');
        return $stmt->fetchAll();
    }

    public function addTodo($title)
    {
        $stmt = $this->pdo->prepare('INSERT INTO todos (title) VALUES (?)');
        $stmt->execute([$title]);
    }

    public function toggleTodoStatus($id)
    {
        $stmt = $this->pdo->prepare('SELECT completed FROM todos WHERE id = ?');
        $stmt->execute([$id]);
        $todo = $stmt->fetch();
        $newStatus = $todo['completed'] ? 0 : 1;

        $stmt = $this->pdo->prepare('UPDATE todos SET completed = ? WHERE id = ?');
        $stmt->execute([$newStatus, $id]);
    }

    public function deleteTodo($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM todos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
