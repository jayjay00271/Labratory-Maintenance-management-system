<?php
session_start();
require 'db_config.php';

// logo and title
$logo = "lclogo.png"; // Path to logo
$title = "Monitoring System for Computer Studies";

// ==========================
//  Allow only logged-in users (admin or staff)
// ==========================
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

// ==========================
//  Pagination settings
// ==========================
$limit = 7; // records per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// ==========================
//  Get total number of reports
// ==========================
$stmt = $pdo->query("SELECT COUNT(*) FROM maintenance_requests");
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// ==========================
//  Fetch paginated report data
// ==========================
$stmt = $pdo->prepare("
    SELECT id, staff_name, equipment_name, issue_description, status, request_date
    FROM maintenance_requests
    ORDER BY id DESC
    LIMIT :offset, :limit
");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determine dashboard link
$dashboardLink = ($_SESSION['role'] === 'admin') ? 'dashboard.php' : 'dashboard_staff.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Maintenance Reports</title>
<link rel="stylesheet" href="equip.css?v=<?= time() ?>">

<!-- Favicon -->
    <link rel="icon" href="lclogo.png" type="image/png">
    
<style>

/* h2 */
h2{
    margin-top: 5px;
}
/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 0.95rem;
    background: rgba(255,255,255,0.9); /* add this */
}

table tbody tr {
    background: rgba(255,255,255,0.85); /* light row background */
}

table tbody tr:nth-child(even) {
    background: rgba(245,245,245,0.85); /* subtle zebra stripes */
}


table thead {
    background: #f0f4f7;
}

table th, table td {
    padding: 10px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    font-weight: 600;
    color: #333;
}

table tr:hover {
    background: #f7faff;
}


/* Print-specific styling */
@media print {
    body * {
        visibility: hidden;
    }
    #print-area, #print-area * {
        visibility: visible;
    }
    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none;
    }
}

/* Pagination buttons */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 15px;
    gap: 5px;
}

.pagination a {
    padding: 6px 12px;
    background: #00bfff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.85rem;
}

.pagination a.active {
    background: #0077aa;
    font-weight: bold;
}

.pagination a:hover {
    background: #0088cc;
}

/* Print button styling */
.print-btn {
    padding: 8px 14px;
    width: 150px;      
    height: 40px;     
    background: #0077aa;
    color: #f0f2f4ff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 10px;
    margin-top: -2px;
}
</style>
</head>
<body>

<div class="container">
    <div class="login-box">

        <h2>📄 Maintenance Reports</h2>
        <hr>

        <!-- Report Table -->
        <div id="print-area">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requester</th>
                        <th>Equipment Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date Filed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reports): ?>
                        <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['staff_name']) ?></td>
                            <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                            <td><?= htmlspecialchars($r['issue_description']) ?></td>
                            <td><?= htmlspecialchars($r['status']) ?></td>
                            <td><?= htmlspecialchars($r['request_date']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="pagination no-print">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">« Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">Next »</a>
            <?php endif; ?>
        </div>
        <!-- Print button -->
         <div style="text-align: right; margin-top: -35px;">
        <button class="print-btn no-print" onclick="window.print()">🖨️ Print Reports</button>        
        <!-- Return to Dashboard -->
         <div class="return-btn" style="margin-top:15px;">
                <a href="<?= $dashboardLink ?>" class="btn-link">&larr; Return to Dashboard</a>
            </div>
    </div>
</div>

</body>
</html>
