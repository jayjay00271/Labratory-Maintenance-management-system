<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_name = $_SESSION['user'];
    $equipment_name = $_POST['equipment_name'];
    $issue_description = $_POST['issue_description'];
    $status = 'Pending';

    $stmt = $pdo->prepare("INSERT INTO maintenance_requests (staff_name, equipment_name, issue_description, status, request_date) 
                           VALUES (:staff, :equipment, :issue, :status, NOW())");
    $stmt->execute([
        'staff' => $staff_name,
        'equipment' => $equipment_name,
        'issue' => $issue_description,
        'status' => $status
    ]);

    header("Location: maintenance_admin.php");
    exit();
}
?>
