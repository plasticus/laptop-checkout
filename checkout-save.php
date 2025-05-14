<?php
require 'db.php';

$equipmentID = $_POST['equipment_id'] ?? null;
$staffName = trim($_POST['staff_name'] ?? '');
$checkoutDate = $_POST['checkout_date'] ?? null;
$returnDate = $_POST['return_date'] ?? null;

if (!$equipmentID || !$staffName || !$checkoutDate || !$returnDate) {
    die("Missing required fields.");
}

$stmt = $pdo->prepare("
    INSERT INTO checkouts (equipment_id, staff_name, checkout_date, return_date)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$equipmentID, $staffName, $checkoutDate, $returnDate]);

header("Location: checkout.php?id=" . urlencode($equipmentID));
exit;
