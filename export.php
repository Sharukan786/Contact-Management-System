<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=contacts.csv");

$output = fopen("php://output", "w");
fputcsv($output, ["Name", "Phone", "Email", "Address", "Category"]);

$sql = "SELECT name, phone, email, address, category FROM contacts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
