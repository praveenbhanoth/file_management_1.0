<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['file'])) {
    header('Location: dashboard.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$file_id = $_GET['file'];

// Fetch file details
$sql = "SELECT file_path FROM files WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $file_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "File not found!";
    exit();
}

$file = $result->fetch_assoc();
$file_path = $file['file_path'];

// Generate shareable link
$share_link = "http://localhost/mysite/Miniproject_2/" . $file_path;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share File</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>📤 Share File</h2>
        <p>Copy the link below to share your file:</p>
        <input type="text" value="<?php echo $share_link; ?>" readonly onclick="this.select();">
        <button onclick="copyLink()">Copy Link</button>
        <br><br>
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

    <script>
        function copyLink() {
            var input = document.querySelector("input");
            input.select();
            document.execCommand("copy");
            alert("Link copied to clipboard!");
        }
    </script>
</body>
</html>
