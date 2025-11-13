<?php
session_start();
require 'db_config.php'; // connect to the database

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$success = $error = '';
$name = $type = $status = '';
$edit_id = null;

// Handle DELETE request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM lab_equipment WHERE id = ?");
        $stmt->execute([$id]);
        $success = "🗑️ Equipment deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting record: " . htmlspecialchars($e->getMessage());
    }
}

// Handle EDIT (load data into form)
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM lab_equipment WHERE id = ?");
        $stmt->execute([$edit_id]);
        $equipment = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($equipment) {
            $name = $equipment['name'];
            $type = $equipment['type'];
            $status = $equipment['status'];
        }
    } catch (PDOException $e) {
        $error = "Error loading record for edit.";
    }
}

// Handle ADD / UPDATE form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $status = trim($_POST['status']);
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : null;

    if ($name && $type && $status) {
        try {
            if ($edit_id) {
                // Update existing equipment
                $stmt = $pdo->prepare("UPDATE lab_equipment SET name=?, type=?, status=? WHERE id=?");
                $stmt->execute([$name, $type, $status, $edit_id]);
                $success = "✅ Equipment updated successfully!";
            } else {
                // Insert new equipment
                $stmt = $pdo->prepare("INSERT INTO lab_equipment (name, type, status) VALUES (?, ?, ?)");
                $stmt->execute([$name, $type, $status]);
                $success = "✅ Equipment added successfully!";
            }

            // Clear input fields
            $name = $type = $status = '';
            $edit_id = null;
        } catch (PDOException $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = "⚠️ All fields are required.";
    }
}

// Fetch all equipment
try {
    $stmt = $pdo->query("SELECT * FROM lab_equipment ORDER BY id DESC");
    $equipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $equipments = [];
    $error = "Failed to fetch equipment list.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lab Equipment Management</title>
<link rel="stylesheet" href="equip.css?v=<?php echo time(); ?>"> <!-- prevent CSS cache -->
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>Lab Equipment Management</h2>
        <hr>

        <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <!-- Add / Edit Equipment Form -->
        <form method="POST" action="">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_id ?? '') ?>">

            <label>Equipment Name</label>
            <input type="text" name="name" placeholder="Enter equipment name" 
                   value="<?= htmlspecialchars($name) ?>" required>

            <label>Type</label>
            <input type="text" name="type" placeholder="e.g., Desktop, Printer"
                   value="<?= htmlspecialchars($type) ?>" required>

            <label>Status</label>
            <select name="status" required>
                <option value="">Select status</option>
                <option value="Working" <?= $status === 'Working' ? 'selected' : '' ?>>Working</option>
                <option value="Needs Repair" <?= $status === 'Needs Repair' ? 'selected' : '' ?>>Needs Repair</option>
                <option value="Out of Service" <?= $status === 'Out of Service' ? 'selected' : '' ?>>Out of Service</option>
            </select>

            <button type="submit"><?= $edit_id ? 'Update Equipment' : 'Add Equipment' ?></button>
            <?php if ($edit_id): ?>
                <a href="equip.php" style="margin-left:10px; text-decoration:none;">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <!-- Equipment List -->
        <h3>Existing Equipment</h3>
<div class="table-wrapper">
    <table>
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
            <tbody>
    <?php if (!empty($equipments)): ?>
        <?php foreach ($equipments as $eq): ?>
            <tr>
                <td><?= htmlspecialchars($eq['name']) ?></td>
                <td><?= htmlspecialchars($eq['type']) ?></td>
                <td><?= htmlspecialchars($eq['status']) ?></td>
                <td><?= htmlspecialchars($eq['created_at']) ?></td>
                <td class="actions">
                    <a class="edit-btn" href="?edit=<?= $eq['id'] ?>">Edit</a>
                    <a class="delete-btn" href="?delete=<?= $eq['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">No equipment added yet.</td></tr>
    <?php endif; ?>
</tbody>
</table>
</div>

<div class="return-btn">
    <a href="dashboard.php">&larr; Return to Dashboard</a>
</div>
