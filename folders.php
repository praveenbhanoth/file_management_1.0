<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$folder_id = $_GET['folder_id'] ?? null;

if (!$folder_id) {
    echo "Folder ID not provided.";
    exit;
}

// Fetch selected folder files
$sql = "SELECT * FROM files WHERE user_id='$user_id' AND folder_id='$folder_id'";
$files = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Files</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .upload-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .upload-form input[type="file"] {
            margin-right: 10px;
        }

        .upload-form button {
            padding: 6px 12px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-list {
            list-style-type: none;
            padding: 0;
        }

        .file-list li {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .file-list b {
            font-size: 16px;
        }

        .file-list a, .file-list button {
            margin-right: 10px;
            font-size: 14px;
        }

        .file-list button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-list button:hover {
            opacity: 0.9;
        }

        .qr-section {
            margin-top: 10px;
        }

        .qr-section img {
            margin-top: 5px;
            border: 1px solid #ccc;
        }

        .qr-section .download-btn {
            margin-top: 10px;
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>

    <script>
        function confirmDelete(fileId) {
            if (confirm("Are you sure you want to delete this file?")) {
                window.location.href = "delete.php?file=" + fileId + "&folder_id=<?= $folder_id ?>";
            }
        }

        function shareFile(fileId, filePath) {
            const link = `${window.location.origin}/${filePath}`;
            const container = document.getElementById("share-link-" + fileId);
            const encodedLink = encodeURIComponent(link);
            const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodedLink}&size=200x200`;

            container.innerHTML = `
                <div class="qr-section">
                    <p>Shareable Link: <a href="${link}" target="_blank">${link}</a></p>
                    <img id="qr-${fileId}" src="${qrCodeUrl}" alt="QR Code" width="200" height="200"><br>
                    <a class="download-btn" href="${qrCodeUrl}" download="QR_Code_${fileId}.png">Download QR Code</a>
                </div>
            `;
        }

        function shareBluetooth() {
            if (navigator.bluetooth) {
                alert("Make sure Bluetooth is turned on to share via Bluetooth.");
            } else {
                alert("Bluetooth is not supported on this device or browser.");
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Your Files in Folder</h2>

        <!-- Upload Form -->
        <form action="upload.php" method="post" enctype="multipart/form-data" class="upload-form">
            <input type="hidden" name="folder_id" value="<?= $folder_id ?>">
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>

        <!-- File List -->
        <ul class="file-list">
            <?php while ($file = $files->fetch_assoc()) {
                $filePath = "uploads/$user_id/$folder_id/" . $file['file_name'];
                $fileId = $file['id'];
            ?>
                <li>
                    <b><?= htmlspecialchars($file['file_name']) ?></b><br>
                    <a href="<?= $filePath ?>" target="_blank">Open</a>
                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $fileId ?>)">Delete</a>
                    <button onclick="shareFile(<?= $fileId ?>, '<?= $filePath ?>')">Share via Link</button>
                    <button onclick="shareBluetooth()">Share via Bluetooth</button>
                    <div id="share-link-<?= $fileId ?>"></div>
                </li>
            <?php } ?>
        </ul>

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>
