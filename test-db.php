<?php
require 'db.php';

$stmt = $pdo->query("SELECT COUNT(*) AS count FROM equipment");
$row = $stmt->fetch();

echo "Connected! Equipment count: " . $row['count'];
