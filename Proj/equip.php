<?php
session_start();
require 'db_config.php';

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

// ==========================
//  Check login
// ==========================
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$success = $error = '';
$name = $type = $status = '';
$edit_id = null;
$search = '';

// ==========================
//  DELETE
// ==========================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM lab_equipment WHERE id = ?");
        $stmt->execute([$id]);
        $success = "🗑️ Equipment deleted successfully!";
    } catch (PDOException $e) {
        $error = "Failed to delete.";
    }
}

// ==========================
//  EDIT (LOAD DATA)
// ==========================
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM lab_equipment WHERE id = ?");
    $stmt->execute([$edit_id]);
    $equipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($equipment) {
        $name = $equipment['name'];
        $type = $equipment['type'];
        $status = $equipment['status'];
    }
}

// ==========================
//  ADD / UPDATE
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $status = trim($_POST['status']);
    $edit_id = $_POST['edit_id'] ?? null;

    if ($name && $type && $status) {
        try {
            if ($edit_id) {
                $stmt = $pdo->prepare("UPDATE lab_equipment SET name=?, type=?, status=? WHERE id=?");
                $stmt->execute([$name, $type, $status, $edit_id]);
                $success = "Updated successfully!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO lab_equipment (name, type, status) VALUES (?, ?, ?)");
                $stmt->execute([$name, $type, $status]);
                $success = "Added successfully!";
            }

            $name = $type = $status = '';
        } catch (PDOException $e) {
            $error = "Failed to save.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// ==========================
//  SEARCH
// ==========================
$search = $_GET['search'] ?? '';

// ==========================
//  PAGINATION SETTINGS
// ==========================
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Count total rows
if ($search) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM lab_equipment WHERE name LIKE ? OR type LIKE ?");
    $countStmt->execute(["%$search%", "%$search%"]);
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM lab_equipment");
}
$total_items = $countStmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch paginated data
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM lab_equipment 
                           WHERE name LIKE ? OR type LIKE ? 
                           ORDER BY id DESC 
                           LIMIT $items_per_page OFFSET $offset");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM lab_equipment ORDER BY id DESC 
                         LIMIT $items_per_page OFFSET $offset");
}
$equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dashboard link
$dashboardLink = ($_SESSION['role'] === 'admin') ? 'dashboard.php' : 'dashboard_staff.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

<!-- Favicon -->
    <link rel="icon" href="lclogo.png" type="image/png">
    
    <meta charset="UTF-8">
    <title>Lab Equipment Management</title>
    <link rel="stylesheet" href="equip.css?v=<?= time() ?>">
</head>

<body>

<div class="container">
    <div class="login-box">
        <h2>📦 Lab Equipment Management</h2>
        <hr>

        <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_id ?? '') ?>">

            <label>Equipment Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($name) ?>">

            <label>Type</label>
            <input type="text" name="type" required value="<?= htmlspecialchars($type) ?>">

            <label>Status</label>
            <select name="status" required>
                <option value="">Select status</option>
                <option value="Working" <?= $status == 'Working' ? 'selected' : '' ?>>Working</option>
                <option value="Needs Repair" <?= $status == 'Needs Repair' ? 'selected' : '' ?>>Needs Repair</option>
                <option value="Out of Service" <?= $status == 'Out of Service' ? 'selected' : '' ?>>Out of Service</option>
            </select>

            <button type="submit"><?= $edit_id ? 'Update' : 'Add' ?> Equipment</button>
        </form>

        <br>
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>

            <?php if ($search): ?>
                <a href="equip.php" class="clear-btn">Clear</a>
            <?php endif; ?>
        </form>

        <h3>Existing Equipment</h3>

        <table class="full-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Added On</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($equipments)): ?>
                <?php foreach ($equipments as $eq): ?>
                    <tr>
                        <td><?= htmlspecialchars($eq['name']) ?></td>
                        <td><?= htmlspecialchars($eq['type']) ?></td>
                        <td><?= htmlspecialchars($eq['status']) ?></td>
                        <td><?= htmlspecialchars($eq['created_at']) ?></td>
                        <td>
                            <a class="edit-btn" href="?edit=<?= $eq['id'] ?>">Edit</a>
                            <a class="delete-btn" onclick="return confirm('Delete this item?');" 
                               href="?delete=<?= $eq['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No equipment found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a class="<?= $i == $page ? 'active-page' : '' ?>" href="?page=<?= $i ?>&search=<?= $search ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <div class="return-btn">
            <a href="<?= $dashboardLink ?>">&larr; Return to Dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
