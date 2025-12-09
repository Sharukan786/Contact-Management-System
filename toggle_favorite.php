<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$contact_id = $_GET['id'] ?? null;

if ($contact_id) {
    // Check if the contact belongs to the logged-in user
    $checkSql = "SELECT * FROM contacts WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $user_id, $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Toggle the favorite status
        $contact = $result->fetch_assoc();
        $newFavoriteStatus = $contact['is_favorite'] ? 0 : 1; // Toggle between 0 and 1
        
        // Update the contact's favorite status in the database
        $updateSql = "UPDATE contacts SET is_favorite = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $newFavoriteStatus, $contact_id);
        $updateStmt->execute();
    }
}

header("Location: index.php?message=Favorite status updated successfully!");
exit();
