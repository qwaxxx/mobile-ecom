<?php
include 'api/conn.php';
session_start();

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    $name = $_POST['prod_name'];
    $price = $_POST['prod_price'];
    $stock = $_POST['prod_stock'];
    $description = $_POST['prod_description'];

    $image_name = $_FILES['prod_picture']['name'];
    $image_tmp = $_FILES['prod_picture']['tmp_name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image_name);

    if (move_uploaded_file($image_tmp, $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (prod_name, prod_stock, prod_description, prod_price, prod_picture, prod_user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisdss", $name, $stock, $description, $price, $target_file, $user_id);

        if ($stmt->execute()) {
            header("Location: seller_dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Image upload failed.";
    }
}

$conn->close();
