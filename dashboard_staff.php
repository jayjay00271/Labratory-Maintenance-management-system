<?php
session_start();

// ==========================
//  Block access if not logged in or not staff
// ==========================
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="equip.css?v=<?= time() ?>"> <!-- prevent CSS cache -->

    <!-- Inline styles for dashboard -->
    <style>
        /* Dashboard container */
        .dashboard-box {
            padding: 25px;
        }

        /* Grid layout for menu cards */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        /* Individual menu card */
        .menu-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Link inside card */
        .menu-card a {
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
        }

        /* Logout button */
        .logout-btn {
            margin-top: 20px;
            display: inline-block;
            color: #fff;
            background: #dc3545;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s;
        }

        .logout-btn:hover {
            background: #b02a37;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-box dashboard-box">

        <!-- Greeting -->
        <h2>Welcome, <?= htmlspecialchars($username); ?>!</h2>
        <p style="color: #6c757d;">Staff Dashboard</p>
        <hr>

        <!-- ==========================
             Staff Menu Cards
             ========================== -->
        <div class="menu-grid">

            <!-- Equipment Page -->
            <div class="menu-card">
                <a href="equip.php">📦 Equipment List</a>
            </div>

            <!-- Maintenance Requests Page -->
            <div class="menu-card">
                <a href="maintenance_admin.php">🛠️ Maintenance Requests</a>
            </div>

            <!-- Reports Page (read-only) -->
            <div class="menu-card">
                <a href="reports.php">📄 Reports</a>
            </div>

        </div>

        <!-- Logout -->
        <a href="logout.php" class="logout-btn">Logout</a>

    </div>
</div>

</body>
</html>
