<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Tashkent');

// SQL to create the `daily` table:
/*
CREATE TABLE `daily` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `arrived_at` DATETIME NOT NULL,
    `leaved_at` DATETIME NOT NULL,
    `required_work_off` TIME NOT NULL,
    `worked_off` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);
*/

class PersonalWorkOffTracker {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO('mysql:host=localhost;dbname=count', 'root', 'Valijon9601!');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Ulanish muvaffaqiyatsiz tugadi: " . $e->getMessage();
            exit();
        }
    }

    public function addRecord($arrived_at, $leaved_at) {
        $arrived_at_dt = new DateTime($arrived_at);
        $leaved_at_dt = new DateTime($leaved_at);
    
        $interval = $arrived_at_dt->diff($leaved_at_dt);
        $hours = $interval->h + ($interval->days * 24);
        $minutes = $interval->i;
        $required_work_off = sprintf('%02d:%02d:00', $hours, $minutes);
    
        $sql = "INSERT INTO daily (arrived_at, leaved_at, required_work_off) VALUES (:arrived_at, :leaved_at, :required_work_off)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':arrived_at', $arrived_at);
        $stmt->bindParam(':leaved_at', $leaved_at);
        $stmt->bindParam(':required_work_off', $required_work_off);
    
        if ($stmt->execute()) {
            echo "Malumotlar bazaga qo'shildi.<br>";
        } else {
            echo "Malumotlar bazaga qo'shilmadi.<br>";
        }
    }

    public function fetchRecords() {
        $sql = "SELECT * FROM daily";
        $result = $this->conn->query($sql);
        $total_hours = 0;
        $total_minutes = 0;

        if ($result->rowCount() > 0) {
            echo '<form action="" method="post">';
            echo '<table class="table">';
            echo '<thead><tr><th>#</th><th>Yetkazilgan vaqti</th><th>Chiqib ketgan vaqti</th><th>Kerakli ish qilish</th><th>Ishlangan</th><th>Amal</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row["id"] . '</td>';
                echo '<td>' . $row["arrived_at"] . '</td>';
                echo '<td>' . $row["leaved_at"] . '</td>';
                echo '<td>' . $row["required_work_off"] . '</td>';
                echo '<td><input type="checkbox" name="worked_off[]" value="' . $row["id"] . '"' . ($row["worked_off"] ? ' checked' : '') . ' disabled></td>';
                echo '<td>';
                if (!$row["worked_off"]) {
                    echo '<button type="submit" name="done" value="' . $row["id"] . '" class="btn btn-success">Done</button>';
                }
                echo '</td>';
                echo '</tr>';

                if (!$row["worked_off"]) {
                    list($hours, $minutes, $seconds) = explode(':', $row["required_work_off"]);
                    $total_hours += (int)$hours;
                    $total_minutes += (int)$minutes;
                }
            }

            $total_hours += floor($total_minutes / 60);
            $total_minutes = $total_minutes % 60;

            echo '<tr><td colspan="4" style="text-align: right;">Jami ish utkazish soatlar</td><td>' . $total_hours . ' soat va ' . $total_minutes . ' daqiqa.</td></tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</form>';
        }
    }

    public function updateWorkedOff($id) {
        $sql = "UPDATE daily SET worked_off = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}

$tracker = new PersonalWorkOffTracker();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["arrived_at"]) && isset($_POST["leaved_at"])) {
        if (!empty($_POST["arrived_at"]) && !empty($_POST["leaved_at"])) {
            $tracker->addRecord($_POST["arrived_at"], $_POST["leaved_at"]);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p style='color: red;'>Iltimos ma'lumotlarni kiriting.</p>";
        }
    } elseif (isset($_POST["done"])) {
        $tracker->updateWorkedOff($_POST["done"]);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'z shaxsiy ish o'tkazish nazorati</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1 class="text-center mt-5 mb-4">O'z shaxsiy ish o'tkazish nazorati</h1>

    <div class="form-container mb-4">
        <form action="" method="post">
            <label for="arrived_at">Yetkazilgan vaqti</label>
            <input type="datetime-local" id="arrived_at" name="arrived_at" required>
            <label for="leaved_at">Chiqib ketgan vaqti</label>
            <input type="datetime-local" id="leaved_at" name="leaved_at" required>
            <button type="submit" class="btn btn-primary">Jo'natish</button>
        </form>
    </div>

    <?php
    $tracker->fetchRecords();
    ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-LD/2A+w6oNjqK+0PAIG7RrZ/7lXV6Mkg5J3V1hmczt6m2WB0z6ZT9mL0Y5jQG4s" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-6pZiVB+N1aiV3pZTPT1A4a58l/x1FZGQDp6Jjc3g+NtTGJ8z+iZyKkRyNlnG4wUf" crossorigin="anonymous"></script>
</body>
</html>
