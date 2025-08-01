<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['file'])) {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$file_id = intval($_GET['file']);

// Fetch the file path from DB
$sql = "SELECT file_path FROM files WHERE id='$file_id' AND user_id='$user_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
    $file = $result->fetch_assoc();
    $file_path = $file['file_path'];

    // Delete the file from the system
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete from the database
    $delete_sql = "DELETE FROM files WHERE id='$file_id' AND user_id='$user_id'";
    $conn->query($delete_sql);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
