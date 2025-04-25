<?php
include 'api/conn.php';

$prod_id = $_POST['prod_id'];
$name = $_POST['prod_name'];
$desc = $_POST['prod_description'];
$stock = $_POST['prod_stock'];
$price = $_POST['prod_price'];

$sql = "UPDATE products SET prod_name=?, prod_description=?, prod_stock=?, prod_price=? WHERE prod_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssidi", $name, $desc, $stock, $price, $prod_id);

if ($stmt->execute()) {
    header("Location: seller_dashboard.php?success=1");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
