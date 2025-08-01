<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['folder'])) {
    header('Location: dashboard.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$folder_id = $_GET['folder'];

// Fetch folder name
$folder_query = "SELECT folder_name FROM folders WHERE id='$folder_id' AND user_id='$user_id'";
$folder_result = $conn->query($folder_query);
if ($folder_result->num_rows == 0) {
    die("Folder not found!");
}
$folder = $folder_result->fetch_assoc();

// Fetch files
$sql = "SELECT * FROM files WHERE folder_id='$folder_id'";
$files = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Files</title>
    <link rel="stylesheet" href="styles.css">
    <script src="bluetooth.js"></script>

</head>
<body>

    <div class="container">
        <h2>Folder: <?php echo $folder['folder_name']; ?></h2>
        
        <form action="upload.php?folder=<?php echo $folder_id; ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit">Upload File</button>
        </form>

        <h3>Your Files:</h3>
        <ul>
            <?php while ($file = $files->fetch_assoc()) { ?>
                <li>
                   <b><?php echo $file['file_name']; ?></b> - 
                   <a href="uploads/<?php echo $file['file_path']; ?>" target="_blank">Open</a> | 
                   <a href="delete.php?file=<?php echo $file['id']; ?>">Delete</a> | 
                   <a href="share.php?file=<?php echo $file['id']; ?>">Share Link</a> | 
                    <button class="bluetooth-share" data-file="uploads/<?php echo $file['file_path']; ?>">
                  Share via Bluetooth
                </button>
              </li>

            <?php } ?>
        </ul>

        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>

</body>
</html>
