<?php
include 'conn.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $data['name'];
    $price = $data['price'];
    $stock = $data['stock'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $name, $price, $stock);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add product"]);
    }
}
