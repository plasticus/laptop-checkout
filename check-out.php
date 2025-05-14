<?php
require 'db.php';

$equipmentID = $_POST['equipment_id'] ?? null;
$staffName = trim($_POST['staff_name'] ?? '');
$checkoutDate = $_POST['checkout_date'] ?? null;
$returnDate = $_POST['return_date'] ?? null;

if (!$equipmentID || !$staffName || !$checkoutDate || !$returnDate) {
    die("Missing required fields.");
}

// 1. Find or insert staff
$stmt = $pdo->prepare("SELECT staffID FROM staff WHERE name = ?");
$stmt->execute([$staffName]);
$staff = $stmt->fetch();

if ($staff) {
    $staffID = $staff['staffID'];
} else {
    $stmt = $pdo->prepare("INSERT INTO staff (name) VALUES (?)");
    $stmt->execute([$staffName]);
    $staffID = $pdo->lastInsertId();
}

// 2. Insert into checkouts
$stmt = $pdo->prepare("
    INSERT INTO checkouts (staffID, equipmentID, checkoutDate, anticipatedReturnDate)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$staffID, $equipmentID, $checkoutDate, $returnDate]);

// 3. Update equipment availability
$stmt = $pdo->prepare("UPDATE equipment SET available = 0 WHERE equipmentID = ?");
$stmt->execute([$equipmentID]);

// 4. Redirect back to list
header("Location: checkout.php");
exit;
