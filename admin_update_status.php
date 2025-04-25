<?php
session_start();
header('Content-Type: application/json');
include("api/conn.php");

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Decode JSON body
$body = file_get_contents('php://input');
$data = json_decode($body, true);
if (!isset($data['order_id'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}
$order_id = (int)$data['order_id'];
$status   = $data['status'];

// Check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}
$seller_id = $_SESSION['user_id'];

// Fetch the order and verify it belongs to this seller
$stmt = $conn->prepare(
    "SELECT addcart_user_id, addcart_seller_id, addcart_pcs, addcart_prod_id
     FROM addcarts
     WHERE addcart_id = ?"
);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit;
}
$order = $result->fetch_assoc();
if ($order['addcart_seller_id'] !== $seller_id) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Forbidden']);
    exit;
}

// Prepare common values
$buyer_id = (int)$order['addcart_user_id'];
$quantity = (int)$order['addcart_pcs'];
$prod_id  = (int)$order['addcart_prod_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Insert notification
    $msg = $status === 'accepted'
        ? "Your order #{$order_id} has been accepted."
        : "Your order #{$order_id} has been rejected.";

    $ns = $conn->prepare(
        "INSERT INTO notifications 
            (user_id, addcart_id, sender_id, message, type)
         VALUES (?, ?, ?, ?, 'response')"
    );
    $ns->bind_param("iiis", $buyer_id, $order_id, $seller_id, $msg);
    $ns->execute();

    // If accepted, deduct stock
    if ($status === 'accepted') {
        $up = $conn->prepare(
            "UPDATE products SET prod_stock = prod_stock - ? 
             WHERE prod_id = ?"
        );
        $up->bind_param("ii", $quantity, $prod_id);
        $up->execute();
    }

    // Update the addcart status
    $ou = $conn->prepare(
        "UPDATE addcarts SET addcart_status = ? 
         WHERE addcart_id = ?"
    );
    $ou->bind_param("si", $status, $order_id);
    $ou->execute();

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

exit;
