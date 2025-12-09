<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES["file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Skip header row
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = $data[0];
            $phone = $data[1];
            $email = $data[2];
            $address = $data[3];
            $category = $data[4];

            $sql = "INSERT INTO contacts (name, phone, email, address, category, user_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $phone, $email, $address, $category, $user_id);
            $stmt->execute();
        }
        fclose($handle);
        echo "<script>alert('Contacts imported successfully!'); window.location.href='index.php';</script>";
    }
}
?>
