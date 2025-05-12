<?php
require 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentID = $_POST['equipmentID'] ?? '';
    $name = $_POST['name'];
    $type = $_POST['type'];
    $serialNumber = $_POST['serialNumber'];
    $notes = $_POST['notes'];

    if ($equipmentID) {
        // Update
        $stmt = $pdo->prepare("UPDATE equipment SET name=?, type=?, serialNumber=?, notes=? WHERE equipmentID=?");
        $stmt->execute([$name, $type, $serialNumber, $notes, $equipmentID]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO equipment (name, type, serialNumber, notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $type, $serialNumber, $notes]);
    }
}

// Fetch all equipment
$rows = $pdo->query("SELECT * FROM equipment ORDER BY equipmentID DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Equipment Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'nav.php'; ?>

<div style="padding: 2rem;">
    <h1>Equipment</h1>

    <form method="POST">
        <input type="hidden" name="equipmentID" id="equipmentID">
        <label>Name: <input type="text" name="name" id="name" required></label><br>
        <label>Type: <input type="text" name="type" id="type"></label><br>
        <label>Serial #: <input type="text" name="serialNumber" id="serialNumber"></label><br>
        <label>Notes: <textarea name="notes" id="notes"></textarea></label><br>
        <button type="submit">Save</button>
    </form>

    <hr>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Serial #</th>
            <th>Notes</th>
            <th>Available?</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['equipmentID']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['serialNumber']) ?></td>
            <td><?= htmlspecialchars($row['notes']) ?></td>
            <td><?= $row['available'] ? 'Yes' : 'No' ?></td>
            <td>
                <button onclick="editRow(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    function editRow(row) {
        document.getElementById('equipmentID').value = row.equipmentID;
        document.getElementById('name').value = row.name;
        document.getElementById('type').value = row.type;
        document.getElementById('serialNumber').value = row.serialNumber;
        document.getElementById('notes').value = row.notes;
    }
</script>

</body>
</html>
