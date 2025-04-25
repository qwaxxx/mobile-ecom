<?php
session_start();
include 'api/conn.php';
$cart = $_SESSION['cart'] ?? []; // cart should be an associative array: ['prod_id' => qty]
$total = 0;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Material Design Bootstrap</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.min.css" rel="stylesheet">
</head>

<body class="grey lighten-3">

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
        <div class="container">

            <!-- Brand -->
            <a class="navbar-brand waves-effect" href="index.php">
                <strong class="blue-text">Lappy</strong>
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
                        <a class="nav-link waves-effect">
                            <span class="badge red z-depth-1 mr-1"> <?php echo count($cart)  ?> </span>
                            <i class="fas fa-shopping-cart"></i>
                            <span class="clearfix d-none d-sm-inline-block"> Cart </span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
                        // Fetch user's name from DB
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

    <!--Main layout-->
    <main class="mt-5 pt-4">
        <div class="container wow fadeIn">

            <!-- Heading -->
            <h2 class="my-5 h2 text-center">Checkout form</h2>

            <!--Grid row-->
            <div class="row">

                <!--Grid column-->
                <div class="col-md-8 mb-4">

                    <!--Card-->
                    <div class="card">

                        <!--Card content-->
                        <form class="card-body" action="process_billing.php" method="POST">

                            <!--Grid row-->
                            <div class="row">

                                <!--Grid column-->
                                <div class="col-md-6 mb-2">

                                    <!--firstName-->
                                    <div class="md-form ">
                                        <input type="text" name="first_name" id="firstName" class="form-control">
                                        <label for="firstName" class="">First name</label>
                                    </div>

                                </div>
                                <!--Grid column-->

                                <!--Grid column-->
                                <div class="col-md-6 mb-2">

                                    <!--lastName-->
                                    <div class="md-form">
                                        <input type="text" name="last_name" id="lastName" class="form-control">
                                        <label for="lastName" class="">Last name</label>
                                    </div>

                                </div>
                                <!--Grid column-->

                            </div>
                            <!--Grid row-->



                            <!--email-->
                            <div class="md-form mb-5">
                                <input type="text" name="email" id="email" class="form-control" placeholder="youremail@example.com">
                                <label for="email" class="">Email (required)</label>
                            </div>

                            <!--address-->
                            <div class="md-form mb-5">


                                <input type="text" name="house_address" id="address_house" class="form-control" placeholder="1234 Main St">
                                <label for="address_house" class="">Blk-Lot/street/village</label>

                            </div>

                            <!--address-2-->
                            <div class="row">

                                <div class="col-md-6 mb-2">
                                    <div class="md-form">
                                        <input type="text" name="baranggay" id="Baranggay" class="form-control" placeholder="Baranggay">
                                        <label for="Baranggay" class="">Baranggay</label>
                                    </div>

                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="md-form">
                                        <input type="text" name="city" id="City" class="form-control" placeholder="City">
                                        <label for="City" class="">City</label>
                                    </div>

                                </div>
                            </div>

                            <!--Grid row-->
                            <div class="row">

                                <!--Grid column-->
                                <div class="col-lg-4 col-md-12 mb-4">

                                    <label for="province">Province</label>
                                    <select class="custom-select d-block w-100" id="province" name="province" required>
                                        <option value="">Choose...</option>
                                        <option>Misamis Occidental</option>
                                        <option>Misamis Oriental</option>
                                        <option>Cagayan De Oro</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Province.
                                    </div>

                                </div>
                                <!--Grid column-->

                                <!--Grid column-->
                                <div class="col-lg-4 col-md-6 mb-4">

                                    <label for="country">Country</label>
                                    <select class="custom-select d-block w-100" name="country" id="country" required>
                                        <option value="">Choose...</option>
                                        <option>Philippines</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please provide a valid Country.
                                    </div>

                                </div>
                                <!--Grid column-->

                                <!--Grid column-->
                                <div class="col-lg-4 col-md-6 mb-4">

                                    <label for="zip">Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" required>
                                    <div class="invalid-feedback">
                                        Zip code required.
                                    </div>

                                </div>
                                <!--Grid column-->

                            </div>
                            <!--Grid row-->

                            <hr>



                            <div class="d-block my-3">
                                <div class="custom-control custom-radio">
                                    <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                    <label class="custom-control-label" for="credit">Cash on delivery</label>
                                </div>

                            </div>

                            <hr class="mb-4">
                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Continue to checkout">

                        </form>

                    </div>
                    <!--/.Card-->

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <?php




                echo '
<div class="col-md-4 mb-4">
    <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Your cart</span>
        <span class="badge badge-secondary badge-pill">' . count($cart) . '</span>
    </h4>
    <ul class="list-group mb-3 z-depth-1">';

                if (!empty($cart)) {
                    foreach ($cart as $productId => $qty) {
                        $stmt = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");
                        $stmt->bind_param("i", $productId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $subtotal = $row['prod_price'] * $qty;
                            $total += $subtotal;
                            echo '
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">' . htmlspecialchars($row['prod_name']) . ' (x' . $qty . ')</h6>
                        <small class="text-muted">' . htmlspecialchars($row['prod_description']) . '</small>
                    </div>
                    <span class="text-muted">₱' . number_format($subtotal, 2) . '</span>
                </li>';
                        }
                        $stmt->close();
                    }

                    // Example: If you want to apply discount
                    // $discount = 5;
                    // $total -= $discount;
                    // echo '
                    // <li class="list-group-item d-flex justify-content-between bg-light">
                    //     <div class="text-success">
                    //         <h6 class="my-0">Promo code</h6>
                    //         <small>EXAMPLECODE</small>
                    //     </div>
                    //     <span class="text-success">-₱' . number_format($discount, 2) . '</span>
                    // </li>';

                    echo '
        <li class="list-group-item d-flex justify-content-between">
            <span>Total (PHP)</span>
            <strong>₱' . number_format($total, 2) . '</strong>
        </li>';
                } else {
                    echo '<li class="list-group-item text-center text-muted">Your cart is empty.</li>';
                }

                echo '</ul></div>';

                $conn->close();
                ?>


            </div>
            <!--Grid column-->

        </div>
        <!--Grid row-->

        </div>
    </main>
    <!--Main layout-->

    <!--Footer-->
    <footer class="page-footer text-center font-small mt-4 wow fadeIn">

        <!--Call to action-->
        <div class="pt-4">
            <a class="btn btn-outline-white" href="https://mdbootstrap.com/docs/jquery/getting-started/download/" target="_blank" role="button">Download MDB
                <i class="fas fa-download ml-2"></i>
            </a>
            <a class="btn btn-outline-white" href="https://mdbootstrap.com/education/bootstrap/" target="_blank" role="button">Start free tutorial
                <i class="fas fa-graduation-cap ml-2"></i>
            </a>
        </div>
        <!--/.Call to action-->

        <hr class="my-4">

        <!-- Social icons -->
        <div class="pb-4">
            <a href="https://www.facebook.com/mdbootstrap" target="_blank">
                <i class="fab fa-facebook-f mr-3"></i>
            </a>

            <a href="https://twitter.com/MDBootstrap" target="_blank">
                <i class="fab fa-twitter mr-3"></i>
            </a>

            <a href="https://www.youtube.com/watch?v=7MUISDJ5ZZ4" target="_blank">
                <i class="fab fa-youtube mr-3"></i>
            </a>

            <a href="https://plus.google.com/u/0/b/107863090883699620484" target="_blank">
                <i class="fab fa-google-plus-g mr-3"></i>
            </a>

            <a href="https://dribbble.com/mdbootstrap" target="_blank">
                <i class="fab fa-dribbble mr-3"></i>
            </a>

            <a href="https://pinterest.com/mdbootstrap" target="_blank">
                <i class="fab fa-pinterest mr-3"></i>
            </a>

            <a href="https://github.com/mdbootstrap/bootstrap-material-design" target="_blank">
                <i class="fab fa-github mr-3"></i>
            </a>

            <a href="http://codepen.io/mdbootstrap/" target="_blank">
                <i class="fab fa-codepen mr-3"></i>
            </a>
        </div>
        <!-- Social icons -->

        <!--Copyright-->
        <div class="footer-copyright py-3">
            © 2019 Copyright:
            <a href="https://mdbootstrap.com/education/bootstrap/" target="_blank"> MDBootstrap.com </a>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'fetch_billing_orders.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Fetched billing data:", data); // <-- add this
                    if (data && Object.keys(data).length > 0) {
                        $('#firstName').val(data.billing_fname);
                        $('#lastName').val(data.billing_lname);
                        $('#email').val(data.billing_email);
                        $('#address_house').val(data.billing_street_village_purok);
                        $('#Baranggay').val(data.billing_baranggay);
                        $('#City').val(data.billing_city);
                        $('#province').val(data.billing_province);
                        $('#country').val(data.billing_country);
                        $('#zip').val(data.billing_postal);
                    }
                }
            });
        });
    </script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Initializations -->
    <script type="text/javascript">
        // Animations initialization
        new WOW().init();
    </script>
</body>

</html>