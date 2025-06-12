<?php
header("Content-Type: application/json");
include("conn.php");

// Retrieve input
$input = json_decode(file_get_contents('php://input'), true);
//$user_id = 8; // Fetch user ID from JSON input
$user_id = $input['userId'] ?? null; // Fetch user ID from JSON input
$search = $input['search'] ?? ''; // Fetch search term from JSON input
$price = $input['price'] ?? ''; // Fetch price filter from JSON input
$page = $input['page'] ?? 1; // Fetch current page from JSON input
$limit = 12; // Set number of items per page
$offset = ($page - 1) * $limit; // Calculate offset for pagination

$response = [
    'success' => false,
    'products' => [],
    'pagination' => [
        'currentPage' => $page,
        'totalPages' => 0,
        'totalProducts' => 0
    ],
    'message' => ''
];

if ($user_id) {
    $sql = "SELECT * FROM products WHERE prod_user_id = ?";
    $params = [$user_id];

    // Search filter
    if (!empty($search)) {
        $search = "%{$conn->real_escape_string($search)}%";
        $sql .= " AND (prod_name LIKE ? OR prod_description LIKE ?)";
        $params[] = $search;
        $params[] = $search;
    }

    // Price filter
    if (!empty($price)) {
        [$min, $max] = explode('-', $price);
        $sql .= " AND prod_price BETWEEN ? AND ?";
        $params[] = $min;
        $params[] = $max;
    }

    // Count total products for pagination
    $countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
    $stmtCount = $conn->prepare($countSql);
    $stmtCount->bind_param(str_repeat('s', count($params)), ...$params);
    $stmtCount->execute();
    $countResult = $stmtCount->get_result();
    $totalProducts = $countResult->fetch_assoc()['total'];
    $response['pagination']['totalProducts'] = $totalProducts;
    $response['pagination']['totalPages'] = ceil($totalProducts / $limit);

    // Add pagination limit
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    // Fetch products
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format product data
            $row['prod_picture'] = $row['prod_picture'] ?? ''; // Ensure picture is set
            $response['products'][] = $row;
        }
        $response['success'] = true;
    } else {
        $response['message'] = 'No products found.';
    }
} else {
    $response['message'] = 'Invalid user ID.';
}

echo json_encode($response); // Return JSON response
$conn->close();
