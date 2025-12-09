<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get user ID
if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$user_id = intval($_GET['user_id']);

// Fetch user info
$userStmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($userResult->num_rows === 0) {
    echo "User not found.";
    exit();
}
$user = $userResult->fetch_assoc();

// Fetch user's contacts
$contactStmt = $conn->prepare("SELECT id, name, phone, email FROM contacts WHERE user_id = ?");
$contactStmt->bind_param("i", $user_id);
$contactStmt->execute();
$contacts = $contactStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user['username']) ?>'s Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lora', serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4"><?= htmlspecialchars($user['username']) ?>'s Saved Contacts</h3>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>

    <div class="mb-3 d-flex gap-2">
        <a href="admin.php" class="btn btn-secondary">🔙 Back to Admin Dashboard</a>
        <a href="export_contacts.php?user_id=<?= $user_id ?>" class="btn btn-success">Export Contacts to CSV</a>
    </div>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Contact ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($contacts->num_rows > 0): ?>
                <?php while ($contact = $contacts->fetch_assoc()): ?>
                    <tr>
                        <td><?= $contact['id'] ?></td>
                        <td><?= htmlspecialchars($contact['name']) ?></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No contacts found for this user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
