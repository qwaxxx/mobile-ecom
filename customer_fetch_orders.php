<?php
header('Content-Type: application/json');
session_start();
include 'api/conn.php';
$customer_id = $_SESSION['user_id'] ?? null;

if (!$customer_id) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Fetch cart-based "orders" grouped by billing
$sql = "
SELECT 
    b.billing_user_id,
    b.billing_street_village_purok,
    b.billing_baranggay,
    b.billing_city,
    b.billing_province,
    b.billing_country,
    ac.addcart_date AS order_date,
    ac.addcart_pcs AS total_quantity,
    (ac.addcart_pcs * p.prod_price) AS total_amount,
    ac.addcart_status,
    ac.addcart_id
FROM addcarts ac
JOIN billing_orders b ON ac.addcart_user_id = b.billing_user_id
JOIN products p ON ac.addcart_prod_id = p.prod_id
WHERE ac.addcart_user_id = ?
ORDER BY ac.addcart_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
