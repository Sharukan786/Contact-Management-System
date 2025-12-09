<?php
include 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=users.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Username', 'Email']);

$result = $conn->query("SELECT id, username, email FROM users");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit();
