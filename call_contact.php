<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $contact_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Get phone number of contact
    $stmt = $conn->prepare("SELECT phone FROM contacts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $contact_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $contact = $result->fetch_assoc();
        $phone = preg_replace('/\D/', '', $contact['phone']); // remove non-digits for WhatsApp link

        // Update last_contacted timestamp
        $updateStmt = $conn->prepare("UPDATE contacts SET last_contacted = NOW() WHERE id = ? AND user_id = ?");
        $updateStmt->bind_param("ii", $contact_id, $user_id);
        $updateStmt->execute();

        // Redirect to WhatsApp chat URL
        $waUrl = "https://wa.me/" . $phone;
        header("Location: $waUrl");
        exit();
    } else {
        // Contact not found or not authorized
        header("Location: index.php?message=Contact not found.");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
