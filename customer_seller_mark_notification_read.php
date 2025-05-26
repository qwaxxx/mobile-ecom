<?php
session_start();
header('Content-Type: application/json');
// turn off HTML error output
ini_set('display_errors', 0);
error_reporting(E_ALL);

include 'api/conn.php';

// 1) Decode JSON
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// 2) Validate
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Notification ID missing']);
    exit;
}
$notif_id = (int)$data['id'];

// 3) Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$user_id = $_SESSION['user_id'];

// 4) Verify this notification belongs to the user
$check = $conn->prepare("SELECT user_id FROM notifications WHERE id = ?");
$check->bind_param("i", $notif_id);
$check->execute();
$res = $check->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Notification not found']);
    exit;
}
$row = $res->fetch_assoc();
if ($row['user_id'] != $user_id) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

// 5) Perform the update
$update = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
$update->bind_param("i", $notif_id);
if ($update->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $update->error
    ]);
}
