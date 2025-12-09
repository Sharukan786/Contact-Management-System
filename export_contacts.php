<?php
include 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=contacts.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'User', 'Name', 'Phone', 'Email']);

$result = $conn->query("SELECT contacts.*, users.username FROM contacts JOIN users ON contacts.user_id = users.id");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['username'], $row['name'], $row['phone'], $row['email']]);
}
fclose($output);
exit();
