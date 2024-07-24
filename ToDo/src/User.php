<?php

class User extends DB
{
    public function SendAllUsers()
    {
        $query = "SELECT * FROM todos";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;
    }

    // add
    public function addTask(string $text)
    {
        $query = "INSERT INTO users (`add`) VALUES (:add)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':add', $text);
        $stmt->execute();
    }

    public function getAdd()
    {
        $query = "SELECT `add` FROM users";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveAdd(string $text)
    {
        $completed = 0;
        $query = "INSERT INTO todos (title, completed) VALUES (:title, :completed)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':title', $text);
        $stmt->bindParam(':completed', $completed);
        $stmt->execute();
    }

    public function deleteAdd()
    {
        $value = 'add';
        $query = "DELETE FROM users WHERE `add` = :value";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    //check
    public function checkTask(string $text)
    {
        $query = "INSERT INTO users (`check`) VALUES (:check)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':check', $text);
        $stmt->execute();
    }

    public function getCheck()
    {
        $query = "SELECT `check` FROM users";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveCheck(int $text)
    {
        $completed = 1;
        $offset = 0;

        $stmtSub = $this->connect()->prepare("
            SELECT id
            FROM todos
            ORDER BY id
            LIMIT 1 OFFSET :offset
        ");
        $stmtSub->bindParam(':offset', $offset, PDO::PARAM_INT); // Integer sifatida bog'laymiz
        $stmtSub->execute();
        $result = $stmtSub->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id = $result['id'] + 1;

            $stmt = $this->connect()->prepare("
                UPDATE todos
                SET completed = :completed
                WHERE id = :id
            ");
            $stmt->bindParam(':completed', $completed, PDO::PARAM_INT); // Integer sifatida bog'laymiz
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Integer sifatida bog'laymiz
            $stmt->execute();
        }
    }


    public function deleteCheck()
    {
        $value = 'check';
        $query = "DELETE FROM users WHERE `check` = :value";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    //uncheck

    public function uncheckTask(string $text)
    {
        $query = "INSERT INTO users (`uncheck`) VALUES (:uncheck)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':uncheck', $text);
        $stmt->execute();
    }

    public function getUncheck()
    {
        $query = "SELECT `uncheck` FROM users";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveUncheck(int $text)
    {
        $completed = 0;
        $offset = 0;

        $stmtSub = $this->connect()->prepare("
            SELECT id
            FROM todos
            ORDER BY id
            LIMIT 1 OFFSET :offset
        ");
        $stmtSub->bindParam(':offset', $offset, PDO::PARAM_INT); // Integer sifatida bog'laymiz
        $stmtSub->execute();
        $result = $stmtSub->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id = $result['id'] + 1;

            $stmt = $this->connect()->prepare("
                UPDATE todos
                SET completed = :completed
                WHERE id = :id
            ");
            $stmt->bindParam(':completed', $completed, PDO::PARAM_INT); // Integer sifatida bog'laymiz
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Integer sifatida bog'laymiz
            $stmt->execute();
        }
    }

    public function deleteUncheck()
    {
        $value = 'uncheck';
        $query = "DELETE FROM users WHERE `uncheck` = :value";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    //delete

    public function writeDelete(string $text)
    {
        $query = "INSERT INTO users (`delete`) VALUES (:delete)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':delete', $text);
        $stmt->execute();
    }

    public function getDelete()
    {
        $query = "SELECT `delete` FROM users";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveDelete(int $offset)
    {
        $stmtSub = $this->connect()->prepare("
        SELECT id
        FROM todos
        ORDER BY id
        LIMIT 1 OFFSET :offset
    ");
        $stmtSub->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmtSub->execute();
        $result = $stmtSub->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id = $result['id'];

            $stmt = $this->connect()->prepare("
            DELETE FROM todos
            WHERE id = :id
        ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function dropDelete()
    {
        $value = 'delete';
        $query = "DELETE FROM users WHERE `delete` = :value";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }
}
