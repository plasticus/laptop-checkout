<?php
require 'db.php';

$equipmentID = $_GET['id'] ?? null;
if (!$equipmentID) {
    echo "No equipment ID specified.";
    exit;
}

// Fetch equipment
$stmt = $pdo->prepare("SELECT * FROM equipment WHERE id = ?");
$stmt->execute([$equipmentID]);
$equipment = $stmt->fetch();
if (!$equipment) {
    echo "Equipment not found.";
    exit;
}

// Dates
$today = new DateTime();
$nextBusinessDay = clone $today;
do {
    $nextBusinessDay->modify('+1 day');
} while (in_array($nextBusinessDay->format('N'), [6, 7])); // Skip Sat/Sun
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Equipment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h2>Checkout Equipment</h2>

        <div class="equipment-info">
            <p><strong>Tag:</strong> <?= htmlspecialchars($equipment['tag']) ?></p>
            <p><strong>Model:</strong> <?= htmlspecialchars($equipment['model']) ?></p>
            <p><strong>Serial:</strong> <?= htmlspecialchars($equipment['serial']) ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($equipment['notes']) ?></p>
        </div>

        <form action="checkout-save.php" method="POST">
            <input type="hidden" name="equipment_id" value="<?= $equipment['id'] ?>">

            <label for="staff_name">Staff Name:</label>
            <input type="text" id="staff_name" name="staff_name" required>

            <label for="checkout_date">Checkout Date:</label>
            <input type="date" id="checkout_date" name="checkout_date" value="<?= $today->format('Y-m-d') ?>" readonly>

            <label for="return_date">Anticipated Return Date:</label>
            <input type="date" id="return_date" name="return_date" value="<?= $nextBusinessDay->format('Y-m-d') ?>" required>

            <button type="submit">Submit Checkout</button>
        </form>
    </div>
</body>
</html>
