<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $folder_name = trim($_POST['folder_name']);

    if (!empty($folder_name)) {
        // Insert folder into database
        $sql = "INSERT INTO folders (user_id, folder_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $folder_name);

        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error creating folder: " . $conn->error;
        }
    } else {
        echo "Folder name cannot be empty!";
    }
}
?>
