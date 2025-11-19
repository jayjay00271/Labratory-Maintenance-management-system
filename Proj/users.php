<?php
session_start();
require 'db_config.php';

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

// ==========================
//  Allow only admin users
// ==========================
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ==========================
//  Initialize variables
// ==========================
$success = $error = "";
$edit_id = null;
$username = $password = $role = "";

/* =============================
      DELETE USER
============================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        $success = "🗑️ User deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting user.";
    }
}

/* =============================
      LOAD USER FOR EDIT
============================= */
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$edit_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $username = $user['username'];
            $role = $user['role'];
            // Password is not auto-filled for security
        }
    } catch (PDOException $e) {
        $error = "Error loading user.";
    }
}

/* =============================
      ADD / UPDATE USER
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $edit_id = !empty($_POST['edit_id']) ? (int)$_POST['edit_id'] : null;

    if ($username && $role) {
        try {
            if ($edit_id) {
                // Update user
                if ($password) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username=?, role=?, password=? WHERE id=?");
                    $stmt->execute([$username, $role, $hashed, $edit_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username=?, role=? WHERE id=?");
                    $stmt->execute([$username, $role, $edit_id]);
                }
                $success = "✅ User updated successfully!";
            } else {
                // Add new user
                if (!$password) {
                    $error = "Password is required.";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $hashed, $role]);
                    $success = "🎉 New user added!";
                }
            }

            // Reset form
            $username = $password = $role = "";
            $edit_id = null;

        } catch (PDOException $e) {
            $error = "Database error.";
        }
    } else {
        $error = "⚠️ Username and role are required.";
    }
}

/* =============================
      FETCH ALL USERS
============================= */
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Failed to load users.";
    $users = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Management</title>
<link rel="stylesheet" href="users.css?v=<?= time() ?>">

<!-- Favicon -->
    <link rel="icon" href="lclogo.png" type="image/png">

</head>
<body>

<div class="container">
    <div class="login-box">

        <!-- Page Title -->
        <h2>User Management</h2>
        <hr>

        <!-- Messages -->
        <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <!-- ==========================
             Add / Edit User Form
        ========================== -->
        <form method="POST">
            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">

            <label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>">

            <label>Password <?= $edit_id ? "(leave blank to keep current)" : "" ?></label>
            <input type="password" name="password">

            <label>Role</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="admin" <?= $role === "admin" ? "selected" : "" ?>>Admin</option>
                <option value="staff" <?= $role === "staff" ? "selected" : "" ?>>Staff</option>
            </select>

            <button type="submit"><?= $edit_id ? "Update User" : "Add User" ?></button>

            <?php if ($edit_id): ?>
                <a href="users.php" style="margin-left:10px;">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <!-- ==========================
             Existing Users Table
        ========================== -->
        <h3>Existing Users</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users): ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['role']) ?></td>
                            <td><?= htmlspecialchars($u['created_at']) ?></td>
                            <td class="actions">
                                <a class="edit-btn" href="?edit=<?= $u['id'] ?>">Edit</a>
                                <a class="delete-btn" href="?delete=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Return to Dashboard -->
        <div class="return-btn">
            <a href="dashboard.php">&larr; Return to Dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
