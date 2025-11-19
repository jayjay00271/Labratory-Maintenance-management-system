<?php
session_start();

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

// ==========================
//  Block access if not logged in or not staff
// ==========================
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Dashboard | Computer Laboratory Monitoring</title>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Favicon -->
<link rel="icon" href="lclogo.png" type="image/png">

<!-- Staff Dashboard CSS -->
<link rel="stylesheet" href="dashboard_staff.css?v=<?= time() ?>">
</head>
<body>

<!-- Background Slider / Overlay -->
<div class="background-slider">
    <div class="background-overlay"></div>
</div>

<div class="container">
    <div class="login-box">


        <!-- Header with Logo -->
        <div class="logo-holder">
            <img src="lclogo.png" alt="LC Logo" class="logo">
            <div class="logo-text">
                <h2>Computer Laboratory Monitoring</h2>
                <p>Staff Dashboard</p>
            </div>
        </div>

        <!-- Welcome Message -->
        <h3>Welcome, <?= htmlspecialchars($username); ?>!</h3>
        <p>Here’s an overview of your laboratory maintenance dashboard.</p>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid">

            <!-- Lab Equipment Card -->
            <a href="equip.php" class="card-link">
                <div class="card">
                    <i class="fas fa-desktop"></i>
                    <h4>Lab Equipment</h4>
                    <p>View and manage computer units and peripherals.</p>
                </div>
            </a>

            <!-- Maintenance Requests Card -->
            <a href="maintenance_admin.php" class="card-link">
                <div class="card">
                    <i class="fas fa-wrench"></i>
                    <h4>Maintenance Requests</h4>
                    <p>Track and update equipment repair requests.</p>
                </div>
            </a>

            <!-- Reports Card -->
            <a href="reports.php" class="card-link">
                <div class="card">
                    <i class="fas fa-chart-bar"></i>
                    <h4>Reports</h4>
                    <p>View reports for maintenance logs.</p>
                </div>
            </a>

        </div>
        <div style="text-align: right; margin-top: 25px;">
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<!-- JS -->
<script src="dashboard_staff.js"></script>
</body>
</html>
