<?php
require 'db.php';

// Fetch all equipment and current open checkouts
$stmt = $pdo->query("
    SELECT 
        e.*, 
        c.checkoutID, 
        c.anticipatedReturnDate,
        s.name AS staffName
    FROM equipment e
    LEFT JOIN checkouts c ON c.equipmentID = e.equipmentID AND c.returnDate IS NULL
    LEFT JOIN staff s ON c.staffID = s.staffID
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
                    <th>Serial #</th>
                    <th>Status</th>
                    <th>Staff</th>
                    <th>Return Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipmentList as $eq): ?>
                    <tr class="<?= $eq['checkoutID'] ? 'checked-out' : 'available' ?>">
                        <td><?= htmlspecialchars($eq['name']) ?></td>
                        <td><?= htmlspecialchars($eq['type']) ?></td>
                        <td><?= htmlspecialchars($eq['serialNumber']) ?></td>
                        <td><?= $eq['checkoutID'] ? 'Checked Out' : 'Available' ?></td>
                        <td>
                            <?php if (!$eq['checkoutID']): ?>
                                <form action="check-out.php" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="equipment_id" value="<?= $eq['equipmentID'] ?>">
                                    <input type="hidden" name="checkout_date" value="<?= $today->format('Y-m-d H:i:s') ?>">
                                    <input type="text" name="staff_name" placeholder="e.g. Linda" required>
                            <?php else: ?>
                                <?= htmlspecialchars($eq['staffName'] ?? 'Unknown') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$eq['checkoutID']): ?>
                                    <input type="date" name="return_date" value="<?= $returnDate->format('Y-m-d') ?>" required>
                            <?php else: ?>
                                <?= htmlspecialchars($eq['anticipatedReturnDate'] ?? '') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$eq['checkoutID']): ?>
                                    <button type="submit" class="button">Check Out</button>
                                </form>
                            <?php else: ?>
                                <form action="check-in.php" method="POST" onsubmit="return confirm('Check this item back in?');">
                                    <input type="hidden" name="equipment_id" value="<?= $eq['equipmentID'] ?>">
                                    <button type="submit" class="button button-delete">Check In</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
