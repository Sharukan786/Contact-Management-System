<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['contact_id']) || !is_numeric($_GET['contact_id'])) {
    die("Invalid contact.");
}

$contact_id = intval($_GET['contact_id']);

// Get contact phone number
$stmt = $conn->prepare("SELECT phone FROM contacts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $contact_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Contact not found.");
}

$contact = $result->fetch_assoc();

// Insert call log
$insert = $conn->prepare("INSERT INTO call_logs (user_id, contact_id, called_at) VALUES (?, ?, NOW())");
$insert->bind_param("ii", $user_id, $contact_id);
$insert->execute();

$phone = $contact['phone'];

// Redirect to phone call
header("Location: tel:$phone");
exit();
?>
