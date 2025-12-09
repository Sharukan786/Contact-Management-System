<?php
session_start();
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM contacts WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $category = $_POST['category'];

    // Handle profile picture update
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        $profilePicture = time() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $profilePicture;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);

        // Update query with new profile picture
        $update_sql = "UPDATE contacts SET name='$name', phone='$phone', email='$email', address='$address', category='$category', profile_picture='$profilePicture' WHERE id=$id";
    } else {
        // Update query without changing profile picture
        $update_sql = "UPDATE contacts SET name='$name', phone='$phone', email='$email', address='$address', category='$category' WHERE id=$id";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Contact updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Edit Contact</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Address:</label>
            <input type="text" name="address" class="form-control" value="<?php echo $row['address']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Category:</label>
            <select name="category" class="form-control">
                <option value="Family" <?php if($row['category'] == 'Family') echo 'selected'; ?>>Family</option>
                <option value="Work" <?php if($row['category'] == 'Work') echo 'selected'; ?>>Work</option>
                <option value="Friends" <?php if($row['category'] == 'Friends') echo 'selected'; ?>>Friends</option>
                <option value="Other" <?php if($row['category'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Contact</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
