<?php
require 'db.php';
require 'nav.php';

// Get all equipment
$stmt = $pdo->query("
    SELECT e.*, 
           (SELECT COUNT(*) FROM checkouts c WHERE c.equipmentID = e.equipmentID AND c.returnDate IS NULL) AS is_checked_out
    FROM equipment e
    ORDER BY e.name ASC
");
$equipmentList = $stmt->fetchAll();

// Date setup
$today = new DateTime();
$returnDate = clone $today;
do {
    $returnDate->modify('+1 day');
} while (in_array($returnDate->format('N'), [6, 7])); // Skip weekends
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

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Serial</th>
                    <th>Status</th>
                    <th>Staff</th>
                    <th>Return Date</th>
                    <th>Checkout</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipmentList as $eq): ?>
                    <tr class="<?= $eq['is_checked_out'] ? 'checked-out' : 'available' ?>">
                        <td><?= htmlspecialchars($eq['name']) ?></td>
                        <td><?= htmlspecialchars($eq['type']) ?></td>
                        <td><?= htmlspecialchars($eq['serialNumber']) ?></td>
                        <td><?= $eq['is_checked_out'] ? 'Checked Out' : 'Available' ?></td>
                        <td>
                            <?php if (!$eq['is_checked_out']): ?>
                                <form action="checkout-save.php" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="equipment_id" value="<?= $eq['equipmentID'] ?>">
                                    <input type="hidden" name="checkout_date" value="<?= $today->format('Y-m-d H:i:s') ?>">
                                    <input type="text" name="staff_name" placeholder="e.g. Scotty" required>
                        </td>
                        <td>
                                    <input type="date" name="return_date" value="<?= $returnDate->format('Y-m-d') ?>" required>
                        </td>
                        <td>
                                    <button type="submit" class="button">Check Out</button>
                                </form>
                            <?php else: ?>
                                <span class="button disabled">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
