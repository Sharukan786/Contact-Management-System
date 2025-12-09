<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

$sql = "SELECT id, username, email, created_at FROM users WHERE username LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$searchQuery%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$totalUsers = $conn->query("SELECT COUNT(*) AS total_users FROM users")->fetch_assoc()['total_users'];
$totalContacts = $conn->query("SELECT COUNT(*) AS total_contacts FROM contacts")->fetch_assoc()['total_contacts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa; font-family: 'Lora', serif;">
<div class="container mt-5 p-4 bg-white shadow rounded">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Welcome Admin - <?= htmlspecialchars($_SESSION['admin_name']); ?></h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?= htmlspecialchars($searchQuery); ?>">
    </form>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">👥 Total Users</h5>
                    <p class="fs-3"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success text-center">
                <div class="card-body">
                    <h5 class="card-title">📇 Total Contacts</h5>
                    <p class="fs-3"><?= $totalContacts ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="export_users.php" class="btn btn-success mb-3">Export Users to CSV</a>

    <h4>Registered Users</h4>
    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username (Click to View Contacts)</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td>
                        <a href="verify_user_credentials.php?user_id=<?= $row['id']; ?>&username=<?= urlencode($row['username']); ?>" class="text-primary text-decoration-underline">
                            <?= htmlspecialchars($row['username']); ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_user.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>
