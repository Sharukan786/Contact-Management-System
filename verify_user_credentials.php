<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$user_id = $_GET['user_id'] ?? '';
$username = $_GET['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredUsername = trim($_POST['username']);
    $enteredPassword = trim($_POST['password']);
    $userId = $_POST['user_id'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $userId, $enteredUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($enteredPassword, $hashedPassword)) {
            $_SESSION['verified_user'] = $userId;
            header("Location: user_contacts.php?user_id=$userId");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#e9ecef;">
<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title text-center mb-3">Verify User Credentials</h5>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Verify & View Contacts</button>
                <a href="admin.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
