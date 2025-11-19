<?php
session_start();
require 'db_config.php'; // Contains $pdo connection

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

// ==========================
//  Check if user is logged in
// ==========================
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// ==========================
//  Determine if user is admin
// ==========================
$isAdmin = ($_SESSION['role'] === 'admin');

// ==========================
//  Handle status update (Admin only)
// ==========================
if ($isAdmin && isset($_GET['update'], $_GET['status'])) {
    $id = intval($_GET['update']);
    $status = $_GET['status'];

    $stmt = $pdo->prepare("UPDATE maintenance_requests SET status = :status WHERE id = :id");
    $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);

    // Redirect to refresh the page after update
    header("Location: maintenance_admin.php");
    exit();
}

// ==========================
//  Fetch all maintenance requests
// ==========================
$stmt = $pdo->query("SELECT * FROM maintenance_requests ORDER BY request_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
//  Determine Dashboard link
// ==========================
$dashboardLink = $isAdmin ? 'dashboard.php' : 'dashboard_staff.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests</title>
    <!-- Main CSS -->
    <link rel="stylesheet" href="equip.css">
    <link rel="stylesheet" href="maintenance.css">

    <!-- Favicon -->
    <link rel="icon" href="lclogo.png" type="image/png">
    
</head>
<body>

<div class="container">
    <div class="card login-box">

        <!-- Page Title -->
        <h2 class="card-title">🛠️ Maintenance Requests</h2>
        <hr>

        <!-- ==========================
             Submit Maintenance Request Form
             ========================== -->
        <div class="card-section request-form">
            <h3 class="section-title">Submit Maintenance Request</h3>
            <form method="post" action="submit_request.php" class="form">
                <div class="form-group">
                    <label>Equipment Name</label>
                    <input type="text" name="equipment_name" required>
                </div>

                <div class="form-group">
                    <label>Issue Description</label>
                    <textarea name="issue_description" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn-primary">Submit Request</button>
            </form>
        </div>

        <!-- ==========================
             Maintenance Requests Table
             ========================== -->
        <div class="card-section table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff Name</th>
                        <th>Equipment</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['staff_name']) ?></td>
                            <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                            <td><?= htmlspecialchars($row['issue_description']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['request_date']) ?></td>
                            <td class="actions">
                                <?php if ($isAdmin): ?>
                                    <!-- Admin can update status -->
                                    <a href="?update=<?= $row['id'] ?>&status=Resolved" class="btn-success">Resolved</a>
                                    <a href="?update=<?= $row['id'] ?>&status=In Progress" class="btn-warning">In Progress</a>
                                <?php else: ?>
                                    <!-- Staff only sees status -->
                                    <?= htmlspecialchars($row['status']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Return Button -->
        <div class="return-btn">
            <a href="<?= $dashboardLink ?>" class="btn-link">&larr; Return to Dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
