<?php
session_start();
include 'api/conn.php';

// Get search and price filter
$search = $_POST['search'] ?? '';
$price = $_POST['price'] ?? '';
$page = $_POST['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM products WHERE 1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (prod_name LIKE '%$search%' OR prod_description LIKE '%$search%')";
}

if (!empty($price)) {
    [$min, $max] = explode('-', $price);
    $sql .= " AND prod_price BETWEEN $min AND $max";
}

// Count total for pagination
$countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
$countResult = $conn->query($countSql);
$totalProducts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

// Add pagination limit
$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="row">';
    while ($row = $result->fetch_assoc()) {
        $modalId = "productModal" . $row['prod_id'];
        echo '
        <div class="col-lg-3 col-md-6 mb-4 d-flex">
            <div class="card h-100 w-100" data-toggle="modal" data-target="#' . $modalId . '" style="cursor:pointer;">
                <div class="view overlay">
                    <img src="' . $row['prod_picture'] . '" class="card-img-top" alt="">
                </div>
                <div class="card-body text-center">
                    <h5>' . $row['prod_stock'] . ' in stock</h5>
                    <h5><strong>' . $row['prod_name'] . '</strong></h5>
                    <p>' . $row['prod_description'] . '</p>
                    <p>₱ ' . $row['prod_price'] . '</p>
                </div>
            </div>
        </div>';
    }
    echo '</div>';

    // Pagination UI
    echo '<nav class="d-flex justify-content-center mt-4  wow fadeIn"><ul class="pagination pg-blue"><li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo '<li class="page-item ' . $active . '"><a class="page-link pagination-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    echo '<li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li></ul></nav>';
} else {
    echo '<p class="text-center">No products found.</p>';
}
$conn->close();
