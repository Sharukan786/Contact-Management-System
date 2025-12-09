<?php
session_start();

// Define admin credentials
$admin_username = "Sharukan";
$admin_password = "asha786";

// Handle login form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = $admin_username;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid admin username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lora font -->
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: url('assets/file1.gif') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Lora', serif;
        }

        .login-container {
            width: 400px;
            margin-left: 60px;
            margin-top: 100px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.15); /* Slightly more opaque */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            color: #fff;
        }

        /* Text inside login box to be black */
        label, input.form-control {
            color: #000; /* Black text for input fields and labels */
        }

        /* Make the "Admin Login" heading black */
        .login-container h2 {
            color: #000; /* Black color for heading */
        }

        .btn-match-bg {
            background: rgba(0, 100, 255, 0.4);
            color: #fff;
            border: none;
        }

        .btn-match-bg:hover {
            background: rgba(0, 100, 255, 0.7);
        }

        .alert-danger {
            background-color: rgba(255, 0, 0, 0.5);
            border: none;
            color: white;
        }

        input.form-control {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            color: #000; /* Black text for input fields */
        }

        input.form-control:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2 class="text-center mb-4">Admin Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-match-bg w-100">Login as Admin</button>
    </form>
</div>
</body>
</html>
