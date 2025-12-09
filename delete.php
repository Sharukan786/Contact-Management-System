<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete profile picture from folder
    $query = "SELECT profile_picture FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($profilePicture);
    $stmt->fetch();
    $stmt->close();

    if (!empty($profilePicture) && file_exists("uploads/" . $profilePicture)) {
        unlink("uploads/" . $profilePicture); // Delete file
    }

    // Delete contact from database
    $sql = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Contact deleted successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error deleting contact!');</script>";
    }
    $stmt->close();
}

$conn->close();
?>
