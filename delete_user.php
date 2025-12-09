<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Optionally, also delete the user's contacts (if needed)
        $deleteContactsSql = "DELETE FROM contacts WHERE user_id = ?";
        $deleteContactsStmt = $conn->prepare($deleteContactsSql);
        $deleteContactsStmt->bind_param("i", $user_id);
        $deleteContactsStmt->execute();

        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "Invalid user ID.";
}
?>
