<?php
header("Content-Type: application/json");
include "conn.php"; // assumes $conn = new mysqli(...);

$response = [];

$sql = "SELECT id, name, email, user_type, create_on, profile_image, contact FROM users";
$result = mysqli_query($conn, $sql);

if ($result) {
    $users = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    $response['success'] = true;
    $response['users'] = $users;
} else {
    $response['success'] = false;
    $response['error'] = mysqli_error($conn);
}

echo json_encode($response);
