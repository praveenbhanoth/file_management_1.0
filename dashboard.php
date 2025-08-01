<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Create New Folder
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folder_name'])) {
    $folder_name = $conn->real_escape_string($_POST['folder_name']);
    $sql = "INSERT INTO folders (user_id, folder_name) VALUES ('$user_id', '$folder_name')";

    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error creating folder: " . $conn->error;
    }
}

// Fetch User Folders
$folders_query = "SELECT * FROM folders WHERE user_id='$user_id'";
$folders_result = $conn->query($folders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

        <!-- Create Folder Form -->
        <form method="POST" class="folder-form">
            <input type="text" name="folder_name" placeholder="Enter folder name" required>
            <button type="submit">Create Folder</button>
        </form>

        <!-- Display Folders -->
        <h3>Your Folders</h3>
        <ul class="folder-list">
            <?php 
            if ($folders_result->num_rows > 0) {
                while ($folder = $folders_result->fetch_assoc()) { 
                    echo "<li><a href='folders.php?folder_id=" . $folder['id'] . "'>" . htmlspecialchars($folder['folder_name']) . "</a></li>";
                }
            } else {
                echo "<li>No folders created yet.</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
