<?php
session_start();
include 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle login form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Please enter both username and password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Lora font -->
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    <style>
        /* Full-screen background GIF */
        body {
            background: url('assets/file1.gif') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Move to left */
            font-family: 'Lora', serif; /* Lora font applied */
        }

        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Transparent login box styling */
        .login-container {
            max-width: 400px;
            margin-left: 60px; /* Shift to left */
            padding: 20px;
            background: rgba(255, 255, 255, 0.3); /* Transparent background */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        /* Button Style Matching Background Blue Color */
        .btn-primary {
            background: rgba(0, 100, 255, 0.6); /* Match background */
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            border-radius: 50px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: rgba(0, 100, 255, 0.8); /* Hover effect matching */
            transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.3);
        }

        /* Link style for the registration text */
        .register-link {
            color: black;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .register-link:hover {
            color: #007bff; /* Match hover effect with button color */
        }
    </style>
</head>
<body class="fade-in">

    <div class="login-container">
        <h2 class="text-center">Login</h2>
        
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
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="text-center mt-2">
                <a href="register.php" class="register-link">Don't have an account? Register here</a>
            </div>
        </form>
    </div>

</body>
</html>
