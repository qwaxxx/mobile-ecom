<?php
session_start();
header('Content-Type: application/json');
include("api/conn.php");

// Validate session
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'result' => false,
        'error' => 'Unauthorized: User not logged in.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$limit   = isset($_GET['all']) ? 1000 : 10;

try {
    // 1) Get the unread count
    $countSql  = "SELECT COUNT(*) AS unread_count
                  FROM notifications
                  WHERE user_id = ? AND status = 'unread'";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("i", $user_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $unread_count = (int)$countRow['unread_count'];

    // 2) Fetch the notifications themselves
    $sql = "SELECT 
                n.id,
                n.addcart_id,
                n.message,
                n.status,
                n.created_at,
                p.prod_name,
                u.profile_image
            FROM notifications n
            LEFT JOIN products p ON n.addcart_id = p.prod_id
            LEFT JOIN users u ON n.sender_id = u.id
            WHERE n.user_id = ?
            ORDER BY n.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id'            => $row['id'],
            'addcart_id'    => $row['addcart_id'],
            'message'       => $row['message'],
            'status'        => $row['status'],
            'created_at'    => $row['created_at'],
            'prod_name'     => $row['prod_name'] ?: 'Product Notification',
            'profile_image' => $row['profile_image'] ?: 'https://localhost/ecom-V6/img/Profile.jpg',
            'url'           => 'https://localhost/ecom-V6/seller_transaction.php?id=' . $row['addcart_id'] // optional
        ];
    }

    // 3) Return both in one JSON response
    echo json_encode([
        'result'        => true,
        'unread_count'  => $unread_count,
        'notifications' => $notifications
    ]);
} catch (Exception $e) {
    echo json_encode([
        'result' => false,
        'error'  => 'Server error: ' . $e->getMessage()
    ]);
}
