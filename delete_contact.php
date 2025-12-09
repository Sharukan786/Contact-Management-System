<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get the contact ID from the URL
if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];

    // Delete the contact from the database
    $sql = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contact_id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting contact.";
    }
} else {
    echo "Invalid contact ID.";
}
?>
