<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
    } else {
        $error = "Database error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_GET['register_success'])): ?>
            <p style="color: green;">Registration successful! Please log in.</p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
