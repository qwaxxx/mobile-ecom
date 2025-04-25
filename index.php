<?php
session_start();
include 'api/conn.php';
//session_unset(); // Unsets all session variables
//session_destroy(); // Destroys the session entirely
$cart = $_SESSION['cart'] ?? [];
if (!isset($_SESSION['user_id']) && !isset($_SESSION['temp_id'])) {
    $_SESSION['temp_id'] = uniqid("guest_", true);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-commerce</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
    <style type="text/css">
        html,
        body,
        header,
        .carousel {
            height: 60vh;
        }

        @media (max-width: 740px) {

            html,
            body,
            header,
            .carousel {
                height: 100vh;
            }
        }

        @media (min-width: 800px) and (max-width: 850px) {

            html,
            body,
            header,
            .carousel {
                height: 100vh;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
        <div class="container">

            <!-- Brand -->
            <a class="navbar-brand waves-effect" href="">
                <strong class="blue-text">E-commerce</strong>
            </a>

            <!-- Collapse -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Left -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right -->
                <ul class="navbar-nav nav-flex-icons">
                    <li class="nav-item">
                        <a class="nav-link waves-effect" href="checkout-page.php">
                            <span class="badge red z-depth-1 mr-1"> <?php echo count($cart)  ?></span>
                            <i class="fas fa-shopping-cart"></i>
                            <span class="clearfix d-none d-sm-inline-block"> Cart </span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
                        // Fetch user's name from DB
                        include 'api/conn.php';
                        $user_id = $_SESSION['user_id'];
                        $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $stmt->bind_result($name);
                        $stmt->fetch();
                        $stmt->close();
                    ?>
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link" href="customer_dashboard.php"><span><?php echo htmlspecialchars($name); ?></span></a>
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login_page.php" class="nav-link border border-light rounded waves-effect">
                                <i class="fas fa-user-circle mr-2"></i>Login
                            </a>
                        </li>
                    <?php endif; ?>


                </ul>

            </div>

        </div>
    </nav>
    <!-- Navbar -->

    <!--Carousel Wrapper-->
    <div id="carousel-example-1z" class="carousel slide carousel-fade pt-4" data-ride="carousel">

        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-1z" data-slide-to="1"></li>
            <li data-target="#carousel-example-1z" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->

        <!--Slides-->
        <div class="carousel-inner" role="listbox">

            <!--First slide-->
            <div class="carousel-item active">
                <div class="view" style="background-image: url('img/carousel1.png'); background-repeat: no-repeat; background-size: cover;">

                    <!-- Mask & flexbox options-->
                    <div class="mask rgba-black-strong d-flex justify-content-center align-items-center">

                        <!-- Content -->
                        <div class="text-center white-text mx-5 wow fadeIn">
                            <h1 class="mb-4">
                                <strong>Affordable Laptops Available</strong>
                            </h1>

                            <p>
                                <strong>Choose And Use your best choice</strong>
                            </p>

                            <p class="mb-4 d-none d-md-block">
                                <strong>Hurry up and Avail our Time-limited Products. dont waste the opportunity that comes to you my friend.</strong>
                            </p>


                        </div>
                        <!-- Content -->

                    </div>
                    <!-- Mask & flexbox options-->

                </div>
            </div>
            <!--/First slide-->

            <!--Second slide-->
            <div class="carousel-item">
                <div class="view" style="background-image: url('img/carousel2.png'); background-repeat: no-repeat; background-size: cover;">

                    <!-- Mask & flexbox options-->
                    <div class="mask rgba-black-strong d-flex justify-content-center align-items-center">

                        <!-- Content -->
                        <div class="text-center white-text mx-5 wow fadeIn">
                            <h1 class="mb-4">
                                <strong>Affordable Laptops Available</strong>
                            </h1>

                            <p>
                                <strong>Choose And Use your best choice</strong>
                            </p>

                            <p class="mb-4 d-none d-md-block">
                                <strong>Hurry up and Avail our Time-limited Products. dont waste the opportunity that comes to you my friend.</strong>
                            </p>


                        </div>
                        <!-- Content -->

                    </div>
                    <!-- Mask & flexbox options-->

                </div>
            </div>
            <!--/Second slide-->

            <!--Third slide-->
            <div class="carousel-item">
                <div class="view" style="background-image: url('img/carousel3.png'); background-repeat: no-repeat; background-size: cover;">

                    <!-- Mask & flexbox options-->
                    <div class="mask rgba-black-strong d-flex justify-content-center align-items-center">

                        <!-- Content -->
                        <div class="text-center white-text mx-5 wow fadeIn">
                            <h1 class="mb-4">
                                <strong>Affordable Laptops Available</strong>
                            </h1>

                            <p>
                                <strong>Choose And Use your best choice</strong>
                            </p>

                            <p class="mb-4 d-none d-md-block">
                                <strong>Hurry up and Avail our Time-limited Products. dont waste the opportunity that comes to you my friend.</strong>
                            </p>


                        </div>
                        <!-- Content -->

                    </div>
                    <!-- Mask & flexbox options-->

                </div>
            </div>
            <!--/Third slide-->

        </div>
        <!--/.Slides-->

        <!--Controls-->
        <a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!--/.Controls-->

    </div>
    <!--/.Carousel Wrapper-->

    <!--Main layout-->
    <main>
        <div class="container">
            <!--Navbar-->
            <nav class="navbar navbar-expand-lg navbar-dark mdb-color lighten-3 mt-3 mb-5">
                <!-- Navbar brand -->
                <span class="navbar-brand">Categories:</span>
                <!-- Collapsible content -->
                <div class="collapse navbar-collapse" id="basicExampleNav">
                    <!-- Links -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" id="showAll" href="#">All
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">

                            <select class="form-control nav-link mdb-color lighten-3" name="price_range" id="price_range" style="color:aqua;height:42px;margin-left:2px">
                                <option value="">Select price range</option>
                                <option value="0-10000">₱0 - ₱10000</option>
                                <option value="10001-20000">₱10001 - ₱20000</option>
                                <option value="20001-30000">₱20001 - ₱30000</option>
                                <option value="30001-130000">₱30001 - ₱130000</option>
                            </select>
                        </li>

                    </ul>
                    <!-- Links -->
                    <form class="form-inline">
                        <div class="md-form my-0">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search" id="search" aria-label="Search">
                        </div>
                    </form>

                </div>
                <!-- Collapsible content -->
            </nav>
            <!--/.Navbar-->
            <div id="productContainer"></div>
        </div>
    </main>
    <!--Main layout-->

    <!-- Modal -->
    <?php
    // Query to fetch products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $modalId = "productModal" . $row['prod_id'];
    ?>
            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel<?= $row['prod_id'] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="addcart.php" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cartModalLabel<?= $row['prod_id'] ?>"><?= htmlspecialchars($row['prod_name']) ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="<?= htmlspecialchars($row['prod_picture']) ?>" class="img-fluid mb-3" alt="">
                                <p><?= htmlspecialchars($row['prod_description']) ?></p>
                                <p><strong>Price:</strong> ₱<?= number_format($row['prod_price'], 2) ?></p>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <button type="button" class="btn btn-secondary btn-sm quantity-btn" onclick="updateQty('qty<?= $row['prod_id'] ?>', -1)">-</button>
                                    <input type="number" name="quantity" id="qty<?= $row['prod_id'] ?>" class="form-control mx-2 text-center" value="1" min="1" max="<?= $row['prod_stock'] ?>" style="width:60px;">
                                    <button type="button" class="btn btn-secondary btn-sm quantity-btn" onclick="updateQty('qty<?= $row['prod_id'] ?>', 1)">+</button>
                                </div>
                                <input type="hidden" name="product_id" value="<?= $row['prod_id'] ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else:
        ?>
        <p class="text-center">No products available.</p>
    <?php
    endif;
    $conn->close();
    ?>

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {

            loadProducts();

            // Quantity Update Function
            window.updateQty = function(inputId, change) {
                let input = document.getElementById(inputId);
                let currentValue = parseInt(input.value);
                let min = parseInt(input.min);
                let max = parseInt(input.max);
                let newValue = currentValue + change;

                if (newValue >= min && newValue <= max) {
                    input.value = newValue;
                }
            };

            // Search input live typing
            $('#search').on('keyup', function() {
                loadProducts($('#search').val(), $('#price_range').val(), 1);
            });

            // Filter by price
            $('#price_range').on('change', function() {
                loadProducts($('#search').val(), $('#price_range').val(), 1);
            });

            // Show all reset
            $('#showAll').on('click', function(e) {
                e.preventDefault();
                $('#search').val('');
                $('#price_range').val('');
                loadProducts();
            });

            // Pagination click
            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                loadProducts($('#search').val(), $('#price_range').val(), page);
            });

            // Load products AJAX
            function loadProducts(search = '', price = '', page = 1) {
                $.ajax({
                    url: 'fetch_products.php',
                    method: 'POST',
                    data: {
                        search: search,
                        price: price,
                        page: page
                    },
                    success: function(data) {
                        $('#productContainer').html(data);
                    }
                });
            }
        });
    </script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Initializations -->
    <!-- MDB core JavaScript (should come after Bootstrap) -->

    <script type="text/javascript">
        // Animations initialization
        new WOW().init();
    </script>

</body>

</html>