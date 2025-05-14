<?php
require 'db.php';

$equipmentID = $_POST['equipment_id'] ?? null;

if ($equipmentID) {
    $stmt = $pdo->prepare("
        UPDATE checkouts 
        SET returnDate = NOW() 
        WHERE equipmentID = ? AND returnDate IS NULL
        LIMIT 1
    ");
    $stmt->execute([$equipmentID]);

    // Optional: mark as available again
    $pdo->prepare("UPDATE equipment SET available = 1 WHERE equipmentID = ?")->execute([$equipmentID]);
}

header("Location: checkout.php");
exit;
