<?php
require 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentID = $_POST['equipmentID'] ?? '';
    $name = $_POST['name'];
    $type = $_POST['type'];
    $serialNumber = $_POST['serialNumber'];
    $notes = $_POST['notes'];
    $neroScore = $_POST['neroScore'] ?? null;

    if ($equipmentID) {
        // Update
        $stmt = $pdo->prepare("UPDATE equipment SET name=?, type=?, serialNumber=?, notes=?, neroScore=? WHERE equipmentID=?");
        $stmt->execute([$name, $type, $serialNumber, $notes, $neroScore, $equipmentID]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO equipment (name, type, serialNumber, notes, neroScore) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $serialNumber, $notes, $neroScore]);
    }
}

// Fetch all equipment, sorted by name
$rows = $pdo->query("SELECT * FROM equipment ORDER BY name ASC")->fetchAll();
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
        <label>Nero Score: <input type="number" name="neroScore" id="neroScore" min="0" max="99999"></label><br>
        <button type="submit">Save</button>
    </form>

    <hr>

    <table border="1" cellpadding="5">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Serial #</th>
            <th>Notes</th>
            <th>Nero Score</th>
            <th>Available?</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['serialNumber']) ?></td>
            <td><?= htmlspecialchars($row['notes']) ?></td>
            <td><?= htmlspecialchars($row['neroScore']) ?></td>
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
        document.getElementById('neroScore').value = row.neroScore;
    }
</script>

</body>
</html>
