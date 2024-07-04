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
        $worked_off_hours = 0;
        $worked_off_minutes = 0;
    
        if ($result->rowCount() > 0) {
            echo '<form action="" method="post" id="recordForm">';
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>#</th><th>Yetkazilgan vaqti</th><th>Chiqib ketgan vaqti</th><th>Kerakli ish qilish</th><th>Ishlangan</th><th>Amal</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $rowStyle = $row["worked_off"] ? 'table-success' : '';
                echo "<tr id='row-{$row["id"]}' class='$rowStyle'>";
                echo '<td>' . $row["id"] . '</td>';
                echo '<td>' . $row["arrived_at"] . '</td>';
                echo '<td>' . $row["leaved_at"] . '</td>';
                echo '<td>' . $row["required_work_off"] . '</td>';
                echo '<td><input type="checkbox" name="worked_off[]" value="' . $row["id"] . '"' . ($row["worked_off"] ? ' checked' : '') . ' disabled></td>';
                echo '<td>';
                if (!$row["worked_off"]) {
                    echo '<button type="button" name="done" value="' . $row["id"] . '" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal" onclick="setModalId(' . $row["id"] . ')">Done</button>';
                }
                echo '</td>';
                echo '</tr>';
    
                list($hours, $minutes, $seconds) = explode(':', $row["required_work_off"]);
                if (!$row["worked_off"]) {
                    $total_hours += (int)$hours;
                    $total_minutes += (int)$minutes;
                } else {
                    $worked_off_hours += (int)$hours;
                    $worked_off_minutes += (int)$minutes;
                }
            }
    
            $total_hours += floor($total_minutes / 60);
            $total_minutes = $total_minutes % 60;
    
            $worked_off_hours += floor($worked_off_minutes / 60);
            $worked_off_minutes = $worked_off_minutes % 60;
    
            $REQUIRED_HOURS = 9;
            $total_worked_time_in_minutes = $total_hours * 60 + $total_minutes;
            $required_time_in_minutes = $REQUIRED_HOURS * 60;
    
            if ($total_worked_time_in_minutes < $required_time_in_minutes) {
                $remaining_time_in_minutes = $required_time_in_minutes - $total_worked_time_in_minutes;
                $remaining_hours = floor($remaining_time_in_minutes / 60);
                $remaining_minutes = $remaining_time_in_minutes % 60;
                $remaining_time_message = "Qolgan vaqt: {$remaining_hours} soat va {$remaining_minutes} daqiqa.";
            } else {
                $extra_time_in_minutes = $total_worked_time_in_minutes - $required_time_in_minutes;
                $extra_hours = floor($extra_time_in_minutes / 60);
                $extra_minutes = $extra_time_in_minutes % 60;
                $remaining_time_message = "Qo'shimcha vaqt: {$extra_hours} soat va {$extra_minutes} daqiqa.";
            }
    
            echo '<tr><td colspan="4" class="text-end">Jami ish utkazish soatlar</td><td class="table-success">' . $total_hours . ' soat va ' . $total_minutes . ' daqiqa.</td></tr>';
            echo '<tr><td colspan="4" class="text-end">Jami ishlangan soatlar</td><td class="table-info">' . $worked_off_hours . ' soat va ' . $worked_off_minutes . ' daqiqa.</td></tr>';
            echo '<tr><td colspan="5" class="text-end">' . $remaining_time_message . '</td></tr>';
            echo '</tbody>';
            echo '</table>';
            echo '<button type="submit" name="update" class="btn btn-primary">Yangilash</button>';
            echo '<button type="button" class="btn btn-secondary" onclick="exportCSV()">CSV\'ni eksport qilish</button>';
            echo '</form>';
        }
    }

    public function updateWorkedOff($id) {
        $sql = "UPDATE daily SET worked_off = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function updateMultipleWorkedOff($worked_off) {
        $sql = "UPDATE daily SET worked_off = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($worked_off as $id) {
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }

    public function exportToCSV() {
        $sql = "SELECT * FROM daily";
        $result = $this->conn->query($sql);
        $filename = 'report.csv';

        $file = fopen($filename, 'w');
        fputcsv($file, array('ID', 'Yetkazilgan vaqti', 'Chiqib ketgan vaqti', 'Kerakli ish qilish', 'Ishlangan'));

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($file, $row);
        }

        fclose($file);
        echo '<p class="text-success">Report exported as ' . $filename . '</p>';
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
    } elseif (isset($_POST["update"])) {
        if (!empty($_POST["worked_off"])) {
            $tracker->updateMultipleWorkedOff($_POST["worked_off"]);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } elseif (isset($_POST["export_csv"])) {
        $tracker->exportToCSV();
    }
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shaxsiy Ishni Bajarish Vaqtini Kuzatuvchi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function setModalId(id) {
            document.getElementById('confirmButton').value = id;
        }
        function exportCSV() {
            document.getElementById('recordForm').action = '';
            document.getElementById('recordForm').submit();
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Shaxsiy Ishni Bajarish Vaqtini Kuzatuvchi</h1>
    <form action="" method="post">
        <div class="mb-3">
            <label for="arrived_at" class="form-label">Yetkazilgan vaqti</label>
            <input type="datetime-local" name="arrived_at" id="arrived_at" class="form-control">
        </div>
        <div class="mb-3">
            <label for="leaved_at" class="form-label">Chiqib ketgan vaqti</label>
            <input type="datetime-local" name="leaved_at" id="leaved_at" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Qo'shish</button>
    </form>

    <hr>

    <?php $tracker->fetchRecords(); ?>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tasdiqlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ishni bajarilgan deb belgilamoqchimisiz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
                    <form action="" method="post">
                        <button type="submit" name="done" value="" class="btn btn-success" id="confirmButton">Tasdiqlash</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>
