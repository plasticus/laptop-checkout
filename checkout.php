<?php
require 'db.php';
require 'nav.php';

// Fetch all equipment
$stmt = $pdo->query("
    SELECT e.*, 
           (SELECT COUNT(*) FROM checkouts c WHERE c.equipment_id = e.id AND c.checkin_date IS NULL) AS is_checked_out
    FROM equipment e
    ORDER BY e.tag ASC
");
$equipmentList = $stmt->fetchAll();
?>

<link rel="stylesheet" href="styles.css">

<div class="container">
    <h2>Equipment Checkout</h2>

    <table>
        <thead>
            <tr>
                <th>Tag</th>
                <th>Model</th>
                <th>Serial</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipmentList as $eq): ?>
                <tr class="<?= $eq['is_checked_out'] ? 'checked-out' : 'available' ?>">
                    <td><?= htmlspecialchars($eq['tag']) ?></td>
                    <td><?= htmlspecialchars($eq['model']) ?></td>
                    <td><?= htmlspecialchars($eq['serial']) ?></td>
                    <td><?= $eq['is_checked_out'] ? 'Checked Out' : 'Available' ?></td>
                    <td>
                        <?php if (!$eq['is_checked_out']): ?>
                            <a href="checkout-form.php?id=<?= $eq['id'] ?>" class="button">Check Out</a>
                        <?php else: ?>
                            <span class="button disabled">Unavailable</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
