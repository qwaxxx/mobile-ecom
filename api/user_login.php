<?php
include 'conn.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        echo json_encode(["message" => "Login successful", "user_id" => $id]);
    } else {
        echo json_encode(["error" => "Invalid email or password"]);
    }
}
