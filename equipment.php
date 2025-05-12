<?php
require 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $asset_tag = $_POST['asset_tag'];
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $condition_notes = $_POST['condition_notes'];

    if ($id) {
        // Update
        $stmt = $pdo->prepare("UPDATE equipment SET asset_tag=?, model=?, serial_number=?, condition_notes=? WHERE id=?");
        $stmt->execute([$asset_tag, $model, $serial_number, $condition_notes, $id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO equipment (asset_tag, model, serial_number, condition_notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$asset_tag, $model, $serial_number, $condition_notes]);
    }
}

// Fetch all equipment
$rows = $pdo->query("SELECT * FROM equipment ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Equipment Management</title>
</head>
<body>
    <h1>Equipment</h1>

    <form method="POST">
        <input type="hidden" name="id" id="id">
        <label>Asset Tag: <input type="text" name="asset_tag" id="asset_tag" required></label><br>
        <label>Model: <input type="text" name="model" id="model"></label><br>
        <label>Serial #: <input type="text" name="serial_number" id="serial_number"></label><br>
        <label>Condition Notes: <textarea name="condition_notes" id="condition_notes"></textarea></label><br>
        <button type="submit">Save</button>
    </form>

    <hr>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Asset Tag</th>
            <th>Model</th>
            <th>Serial #</th>
            <th>Condition</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['asset_tag']) ?></td>
            <td><?= htmlspecialchars($row['model']) ?></td>
            <td><?= htmlspecialchars($row['serial_number']) ?></td>
            <td><?= htmlspecialchars($row['condition_notes']) ?></td>
            <td>
                <button onclick="editRow(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editRow(row) {
            document.getElementById('id').value = row.id;
            document.getElementById('asset_tag').value = row.asset_tag;
            document.getElementById('model').value = row.model;
            document.getElementById('serial_number').value = row.serial_number;
            document.getElementById('condition_notes').value = row.condition_notes;
        }
    </script>
</body>
</html>
