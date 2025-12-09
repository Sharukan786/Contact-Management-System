<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $phone    = trim($_POST['phone']);
    $email    = trim($_POST['email']);
    $address  = trim($_POST['address']);
    $category = trim($_POST['category']);
    $user_id  = $_SESSION['user_id']; // Get logged-in user's ID

    // Handle profile picture upload
    $profilePicture = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $profilePicture = time() . '_' . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $profilePicture;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                echo "<script>alert('Error uploading the file!');</script>";
                $profilePicture = '';
            }
        } else {
            echo "<script>alert('Invalid file type! Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
            $profilePicture = '';
        }
    }

    // 🔁 Check for duplicate contact (same name or phone or email for the same user)
    $check = $conn->prepare("SELECT id FROM contacts WHERE user_id = ? AND (name = ? OR phone = ? OR email = ?)");
    $check->bind_param("isss", $user_id, $name, $phone, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Contact with same name, phone, or email already exists!'); window.location.href='add.php';</script>";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO contacts (name, phone, email, address, category, profile_picture, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $phone, $email, $address, $category, $profilePicture, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Contact added successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contact</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Lora Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Lora', serif;
        }
    </style>
</head>
<body class="container mt-4">

    <h2>Add New Contact</h2>
    <a href="index.php" class="btn btn-secondary">Back to Contacts</a>

    <form action="add.php" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address:</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category:</label>
            <select name="category" class="form-control">
                <option value="Family">Family</option>
                <option value="Friends">Friends</option>
                <option value="Work">Work</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture:</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Add Contact</button>
    </form>

</body>
</html>
