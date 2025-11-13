<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
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
    <title>Dashboard | Computer Laboratory Maintenance and Monitoring System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="background-slider">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>

    <div class="container">
        <div class="login-box" style="width: 700px; text-align: left;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <img src="lclogo.png" alt="LC Logo" class="logo" style="width: 100px; height: 90px;">
                <div>
                    <h2 style="margin-bottom: 5px;">Computer Laboratory Maintenance and Monitoring System</h2>
                </div>
            </div>

            <hr style="margin: 20px 0; border: 1px solid rgba(255,255,255,0.3);">

            <h3 style="color: rgba(5, 5, 5, 1); margin-bottom: 10px;">Welcome, <?php echo htmlspecialchars($username); ?>!</h3>
            <p style="color: #080808ff; margin-bottom: 25px;">Here’s an overview of your laboratory maintenance dashboard.</p>

            <div class="dashboard-grid">
    <a href="equip.php" class="card-link">
        <div class="card">
            <i class="fas fa-desktop"></i> 
            <h4>Lab Equipment</h4>
            <p>View and manage computer units, peripherals, and hardware details.</p>
        </div>
    </a>

    <a href="maintenance_admin.php" class="card-link">
        <div class="card">
            <i class="fas fa-wrench"></i>
            <h4>Maintenance Requests</h4>
            <p>Track and update equipment repair and maintenance requests.</p>
        </div>
    </a>

    <a href="users.php" class="card-link">
        <div class="card">
            <i class="fas fa-users"></i>
            <h4>Users</h4>
            <p>Manage accounts and access levels for system administrators and staff.</p>
        </div>
    </a>

    <a href="reports.php" class="card-link">
        <div class="card">
            <i class="fas fa-chart-bar"></i>
            <h4>Reports</h4>
            <p>Generate and view reports for maintenance logs and system activity.</p>
        </div>
    </a>
</div>
            <div style="text-align: right; margin-top: 25px;">
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    <script src="dashboard.js"></script>
</body>
</html>
