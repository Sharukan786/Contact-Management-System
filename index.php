<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

$message = "";
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

$sql = "SELECT * FROM contacts 
        WHERE user_id = ? 
        AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR address LIKE ? OR category LIKE ?)
        ORDER BY name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $user_id, $search, $search, $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$totalContactsSql = "SELECT COUNT(*) as total_contacts FROM contacts WHERE user_id = ?";
$stmtCount = $conn->prepare($totalContactsSql);
$stmtCount->bind_param("i", $user_id);
$stmtCount->execute();
$countResult = $stmtCount->get_result();
$totalContacts = $countResult->fetch_assoc()['total_contacts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        body {
            font-family: 'Lora', serif;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }
        .profile-pic:hover {
            transform: scale(1.2);
        }
        tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease-in-out;
        }
        .btn {
            transition: transform 0.2s ease-in-out;
        }
        .btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="container mt-4">

<nav class="navbar navbar-light bg-light mb-3">
    <a class="navbar-brand">📞 Contact Management</a>
    <span class="navbar-text">Welcome, <?= htmlspecialchars($user_name); ?>! 😊</span>
    <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
</nav>

<?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="mb-3">
    <h5>Total Contacts: <?= $totalContacts ?></h5>
</div>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-10">
            <input type="text" name="search" placeholder="🔍 Search contacts by name, phone, email, address, or category..." class="form-control" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </div>
</form>

<h2 class="text-center">Manage Your Contacts</h2>
<a href="add.php" class="btn btn-success mb-3">➕ Add New Contact</a>
<a href="export.php" class="btn btn-primary mb-3">📤 Export Contacts</a>

<!-- Sample Template Download Button -->
<a href="download_sample.php" class="btn btn-outline-info mb-3">📄 Download Sample Template</a>


<!-- Import Contacts -->
<form action="import.php" method="POST" enctype="multipart/form-data" class="d-inline mb-3 ms-2">
    <input type="file" name="file" accept=".csv, .xls, .xlsx" required />
    <button type="submit" class="btn btn-warning">📥 Import Contacts</button>
</form>

<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Profile</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Category</th>
            <th>Favorite</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php 
                    $profilePic = !empty($row['profile_picture']) ? "uploads/" . htmlspecialchars($row['profile_picture']) : "default.png"; 
                    ?>
                    <img src="<?= $profilePic ?>" class="profile-pic" alt="Profile Picture" />
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <?= htmlspecialchars($row['phone']) ?><br />
                    <a href="log_call.php?contact_id=<?= $row['id']; ?>" class="btn btn-success btn-sm mt-1">📞 Call</a>
                </td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>
                    <a href="toggle_favorite.php?id=<?= $row['id'] ?>" class="btn btn-sm <?= $row['is_favorite'] ? 'btn-warning' : 'btn-secondary' ?>">
                        <?= $row['is_favorite'] ? '⭐' : '☆' ?>
                    </a>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏ Edit</a>
                    <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-danger btn-sm">🗑 Delete</button>
                    <a href="contact_call_history.php?contact_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">📜 View History</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this contact?")) {
            window.location.href = "delete.php?id=" + id;
        }
    }
</script>

</body>
</html>
