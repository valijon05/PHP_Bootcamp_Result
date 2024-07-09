<?php
$tracker = new PersonalWorkOffTracker();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["arrived_at"]) && isset($_POST["leaved_at"])) {
        if (!empty($_POST["arrived_at"]) && !empty($_POST["leaved_at"])) {
            $tracker->addRecord($_POST["arrived_at"], $_POST["leaved_at"]);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p class='text-danger'>Iltimos ma'lumotlarni kiriting.</p>";
        }
    } elseif (isset($_POST["worked_off"])) {
        $tracker->updateWorkedOff($_POST["worked_off"]);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } elseif (isset($_POST["export"])) {
        $tracker->exportCSV();
    }
}