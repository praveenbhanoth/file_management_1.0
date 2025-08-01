<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            header('Location: ./login.php?register_success=true');
            exit();
        } else {
            echo "Error: Could not execute registration. " . $stmt->error;
        }
    } else {
        echo "Error: Could not prepare statement. " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post" action="register.php">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
