<?php
session_start();
require 'db_config.php'; // Include DB connection

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check required fields
    if ($username === '' || $password === '') {
        $message = 'Please fill in all fields.';
    } else {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validate password (supports hashed passwords)
        if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
            // Set session
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            $redirect = $user['role'] === 'admin' ? 'dashboard.php' : 'dashboard_staff.php';
            header("Location: $redirect");
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lemery Colleges - Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $logo ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <!-- Background video -->
    <video class="background-video" autoplay loop muted playsinline>
        <source src="lcvid.mp4" type="video/mp4">
    </video>
    <div class="background-overlay"></div>

    <!-- Login form container -->
    <div class="login-container">
        <img src="<?= $logo ?>" alt="Lemery Colleges Logo" class="logo">
        <div class="system-title"><?= $title ?></div>
        <h2>Login</h2>

        <!-- Display message -->
        <?php if ($message): ?>
            <div id="message" class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form id="loginForm" method="post" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="checkbox-group">
                <label><input type="checkbox" name="remember"> Remember Me</label>
                <a href="forget_password.php" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>

    <!-- Custom JS -->
    <script src="login.js"></script>
</body>
</html>
