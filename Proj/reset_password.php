<?php
session_start();
require 'db_config.php';

$success = '';
$error = '';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forget_password.php");
    exit;
}

$user_id = $_SESSION['reset_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if ($password === '' || $confirm === '') {
        $error = "⚠️ All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "❌ Passwords do not match.";
    } else {
        // Update password in DB (hashed)
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $user_id]);

        unset($_SESSION['reset_user_id']); // clear session
        $success = "✅ Password updated successfully! You can now <a href='login.php'>login</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link rel="stylesheet" href="equip.css">
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>Reset Password</h2>
        <hr>

        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
            <label>New Password</label>
            <input type="password" name="password" placeholder="Enter new password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>

            <button type="submit">Reset Password</button>
        </form>
        <?php endif; ?>

        <p style="margin-top:10px;"><a href="login.php">&larr; Back to Login</a></p>
    </div>
</div>
</body>
</html>
