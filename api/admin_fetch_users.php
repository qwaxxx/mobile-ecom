<?php

header("Access-Control-Allow-Credentials: true");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

header('Access-Control-Allow-Origin: *'); // Or replace * with your frontend domain
header('Content-Type: application/json');
include 'conn.php';

$users = $conn->query("SELECT id, name, email, contact, profile_image, user_type, password FROM users");

$data = [];

while ($row = $users->fetch_assoc()) {
    $row['profile_image'] = $row['profile_image'] ? "img/" . $row['profile_image'] : "https://via.placeholder.com/50";
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
