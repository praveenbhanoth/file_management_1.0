<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file']) && isset($_POST['folder_id'])) {
    $folder_id = $_POST['folder_id'];
    $file_name = basename($_FILES['file']['name']);
    $file_tmp = $_FILES['file']['tmp_name'];

    // Define the storage path relative to the project
    $upload_dir = "uploads/$user_id/$folder_id/";
    $relative_path = "$user_id/$folder_id/$file_name"; // for database and opening

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            die("Error: Failed to create directory. Check permissions.");
        }
    }

    // Save the file
    $full_path = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $full_path)) {
        // Save relative path to DB
        $sql = "INSERT INTO files (user_id, folder_id, file_name, file_path) 
                VALUES ('$user_id', '$folder_id', '$file_name', '$relative_path')";
        
        if ($conn->query($sql)) {
            header("Location: folders.php?folder_id=$folder_id");
            exit;
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "File upload failed!";
    }
}
?>
