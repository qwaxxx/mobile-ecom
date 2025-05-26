<?php
include 'conn.php';

if (!isset($_POST['product_id'])) exit;

$product_id = (int) $_POST['product_id'];
$sql = "SELECT * FROM products WHERE prod_id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0):
    $row = $result->fetch_assoc();
    $modalId = "productModal" . $row['prod_id'];
?>
    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form data-product-id="<?= $row['prod_id'] ?>">

                    <div class="modal-header">
                        <h5 class="modal-title"><?= htmlspecialchars($row['prod_name']) ?></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= htmlspecialchars($row['prod_picture']) ?>" class="img-fluid mb-3" alt="">
                        <p><?= htmlspecialchars($row['prod_description']) ?></p>
                        <p><strong>Price:</strong> ₱<?= number_format($row['prod_price'], 2) ?></p>
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <button type="button" class="btn btn-secondary btn-sm quantity-btn">-</button>
                            <input type="number" name="quantity" class="form-control mx-2 text-center" value="1" min="1" max="<?= $row['prod_stock'] ?>" style="width:60px;">
                            <button type="button" class="btn btn-secondary btn-sm quantity-btn">+</button>
                        </div>
                        <input type="hidden" name="product_id" value="<?= $row['prod_id'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="addcart">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
endif;
$conn->close();
