<?php
session_start();
require 'db_config.php'; // your PDO connection

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $error = "⚠️ Please enter your username.";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Store user ID in session for password reset
            $_SESSION['reset_user_id'] = $user['id'];
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "❌ Username not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>

<!-- Favicon -->
<link rel="icon" href="lclogo.png" type="image/png">

<!-- Custom CSS -->
<link rel="stylesheet" href="maintenance.css?v=<?= time() ?>">
</head>
<body>

<div class="container">
    <div class="login-box">
        <h2>Forgot Password</h2>
        <hr>

        <!-- Success / Error Messages -->
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter your username" required>
            <button type="submit">Next</button>
        </form>

        <!-- Back link -->
        <div class="return-btn" style="margin-top:15px;">
            <a href="login.php">&larr; Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
