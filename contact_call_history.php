<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Validate contact_id
if (!isset($_GET['contact_id']) || !is_numeric($_GET['contact_id'])) {
    die("Invalid contact ID.");
}

$contact_id = intval($_GET['contact_id']);

// Check if contact belongs to this user
$stmt = $conn->prepare("SELECT name, phone FROM contacts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $contact_id, $user_id);
$stmt->execute();
$contactResult = $stmt->get_result();

if ($contactResult->num_rows === 0) {
    die("Contact not found or you don't have permission to view this contact.");
}

$contact = $contactResult->fetch_assoc();

// Fetch call history for this contact
$callStmt = $conn->prepare("SELECT called_at FROM call_logs WHERE user_id = ? AND contact_id = ? ORDER BY called_at DESC");
$callStmt->bind_param("ii", $user_id, $contact_id);
$callStmt->execute();
$callResult = $callStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Call History - <?= htmlspecialchars($contact['name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        body {
            font-family: 'Lora', serif;
        }
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body class="container">

<nav class="navbar navbar-light bg-light mb-4">
    <a class="navbar-brand" href="index.php">← Back to Contacts</a>
    <span class="navbar-text">Call History for <strong><?= htmlspecialchars($contact['name']) ?></strong> (<?= htmlspecialchars($contact['phone']) ?>)</span>
</nav>

<?php if ($callResult->num_rows === 0): ?>
    <div class="alert alert-info">No call history found for this contact.</div>
<?php else: ?>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Call Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($row = $callResult->fetch_assoc()):
                $calledAt = date("d M Y, h:i A", strtotime($row['called_at']));
            ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= $calledAt ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
