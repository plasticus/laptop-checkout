<?php
require 'db.php';
include 'nav.php';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteID'])) {
    $deleteID = $_POST['deleteID'];
    $stmt = $pdo->prepare("DELETE FROM equipment WHERE equipmentID = ?");
    $stmt->execute([$deleteID]);
}

// Handle add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && !isset($_POST['deleteID'])) {
    $equipmentID = $_POST['equipmentID'] ?? '';
    $name = $_POST['name'];
    $type = $_POST['type'];
    $serialNumber = $_POST['serialNumber'];
    $notes = $_POST['notes'];
    $neroScore = isset($_POST['neroScore']) && $_POST['neroScore'] !== '' ? (int)$_POST['neroScore'] : null;
    $operatingSystem = $_POST['operatingSystem'] ?? null;

    if ($equipmentID) {
        $stmt = $pdo->prepare("UPDATE equipment SET name=?, type=?, serialNumber=?, notes=?, neroScore=?, operatingSystem=? WHERE equipmentID=?");
        $stmt->execute([$name, $type, $serialNumber, $notes, $neroScore, $operatingSystem, $equipmentID]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO equipment (name, type, serialNumber, notes, neroScore, operatingSystem) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $serialNumber, $notes, $neroScore, $operatingSystem]);
    }
}

// Fetch all equipment
$rows = $pdo->query("SELECT * FROM equipment ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Equipment Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div style="padding: 2rem;">
    <h1>Equipment</h1>

    <table>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Serial #</th>
            <th>Notes</th>
            <th>Nero Score</th>
            <th>OS</th>
            <th>Available?</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['serialNumber'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['neroScore'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['operatingSystem'] ?? '') ?></td>
            <td><?= ($row['available'] ?? true) ? 'Yes' : 'No' ?></td>
            <td>
                <button class="button-small button-edit" onclick="editRow(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this record?');">
                    <input type="hidden" name="deleteID" value="<?= $row['equipmentID'] ?>">
                    <button type="submit" class="button-small button-delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="form-box">
        <h2 class="form-subheader">Add Equipment:</h2>
        <form method="POST">
            <input type="hidden" name="equipmentID" id="equipmentID">
            <label>Name: <input type="text" name="name" id="name" required></label><br>
            <label>Type: <input type="text" name="type" id="type"></label><br>
            <label>Serial #: <input type="text" name="serialNumber" id="serialNumber"></label><br>
            <label>Notes: <textarea name="notes" id="notes"></textarea></label><br>
            <label>Nero Score: <input type="number" name="neroScore" id="neroScore" min="0" max="99999"></label><br>
            <label>Operating System: <input type="text" name="operatingSystem" id="operatingSystem"></label><br>
            <button type="submit" class="button-small button-save">Save</button>
        </form>
    </div>
</div>

<script>
    function editRow(row) {
        document.getElementById('equipmentID').value = row.equipmentID;
        document.getElementById('name').value = row.name ?? '';
        document.getElementById('type').value = row.type ?? '';
        document.getElementById('serialNumber').value = row.serialNumber ?? '';
        document.getElementById('notes').value = row.notes ?? '';
        document.getElementById('neroScore').value = row.neroScore ?? '';
        document.getElementById('operatingSystem').value = row.operatingSystem ?? '';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }
</script>

</body>
</html>
