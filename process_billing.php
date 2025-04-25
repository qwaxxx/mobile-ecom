<?php
session_start();
include 'api/conn.php';
if (isset($_SESSION['checkout_form'])) {
    $_POST = $_SESSION['checkout_form'];
    unset($_SESSION['checkout_form']); // Clean it after use
}


if (!isset($_SESSION['user_id']) && isset($_SESSION['temp_id'])) {
    $_SESSION['checkout_form'] = $_POST; // 👈 Add this line
    $_SESSION['redirect_after_login'] = 'process_billing.php';
    header("Location: login_page.php");
    exit();
}

// If session has user_id OR both user_id and temp_id, bypass login
if (isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && isset($_SESSION['temp_id']))) {
    // Allow access to page


    $user_id = $_SESSION['user_id'] ?? null;
    $temp_id = $_SESSION['temp_id'] ?? null;
    $batch_id = $user_id . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    if (!$user_id && !$temp_id) {
        die("No user session found.");
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $house = $_POST['house_address'];
    $barangay = $_POST['baranggay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $zip = $_POST['zip'];

    $id_field = $user_id ? "user_id" : "temp_id";
    $id_value = $user_id ?? $temp_id;

    $stmt = $conn->prepare("SELECT * FROM billing_orders WHERE billing_$id_field = ?");
    $stmt->bind_param("s", $id_value);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($existing = $result->fetch_assoc()) {
        // Check if any value changed
        if (
            $existing['billing_fname'] !== $first_name || $existing['billing_lname'] !== $last_name ||
            $existing['billing_email'] !== $email || $existing['billing_street_village_purok'] !== $house ||
            $existing['billing_baranggay'] !== $barangay || $existing['billing_city'] !== $city ||
            $existing['billing_province'] !== $province || $existing['billing_country'] !== $country ||
            $existing['billing_postal'] !== $zip
        ) {
            // Update
            $update = $conn->prepare("UPDATE billing_orders SET billing_fname=?, billing_lname=?, billing_email=?, billing_street_village_purok=?, billing_baranggay=?, billing_city=?, billing_province=?, billing_country=?, billing_postal=? WHERE billing_$id_field=?");
            $update->bind_param("ssssssssss", $first_name, $last_name, $email, $house, $barangay, $city, $province, $country, $zip, $id_value);
            $update->execute();
            // Insert into addcarts
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $prod_id => $qty) {
                    $stmt = $conn->prepare("SELECT prod_price, prod_user_id, prod_name FROM products WHERE prod_id = ?");
                    $stmt->bind_param("i", $prod_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $prod_price = $row['prod_price'];
                        $prod_seller_id = $row['prod_user_id'];
                        $prod_name = $row['prod_name'];
                        $addcart_status = "pending";

                        $insertCart = $conn->prepare("INSERT INTO addcarts (addcart_batch_id, addcart_user_id, addcart_seller_id, addcart_prod_id, addcart_pcs, addcart_price, addcart_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $insertCart->bind_param("siiiids", $batch_id, $user_id, $prod_seller_id, $prod_id, $qty, $prod_price, $addcart_status);
                        $insertCart->execute();

                        // Get the ID of the newly inserted addcart row
                        $addcart_id = $insertCart->insert_id;
                        $insertCart->close();

                        $notif_sql = "INSERT INTO notifications (user_id, sender_id, addcart_id, message, type) VALUES (?, ?, ?, ?, 'purchase')";
                        $stmt1 = $conn->prepare($notif_sql);
                        $msg = "Someone purchased your product: $prod_name / $qty pcs / Price : $prod_price";
                        $stmt1->bind_param("iiis", $prod_seller_id, $user_id, $addcart_id, $msg);
                        $stmt1->execute();
                        $stmt1->close();
                    }


                    $stmt->close();
                }
            }
            /*
            // Get billing_order_id (for linking to the orders table)
            $billing_stmt = $conn->prepare("SELECT billing_order_id FROM billing_orders WHERE billing_$id_field = ?");
            $billing_stmt->bind_param("s", $id_value);
            $billing_stmt->execute();
            $billing_result = $billing_stmt->get_result();

            if ($billing_row = $billing_result->fetch_assoc()) {
                $billing_order_id = $billing_row['billing_order_id'];

                // Insert only one order record for this batch
                $order_status = "pending"; // set your preferred default status
                $order_stmt = $conn->prepare("INSERT INTO orders (order_user_id, order_billing_id, order_batch_id, order_status) VALUES (?, ?, ?, ?)");
                $order_stmt->bind_param("iiss", $user_id, $billing_order_id, $batch_id, $order_status);
                $order_stmt->execute();
                $order_stmt->close();
            }

            $billing_stmt->close();*/
            unset($_SESSION['cart']);
        }
    } else {
        // Insert
        $insert = $conn->prepare("INSERT INTO billing_orders (billing_fname, billing_lname, billing_email, billing_street_village_purok, billing_baranggay, billing_city, billing_province, billing_country, billing_postal, billing_$id_field) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssssssss", $first_name, $last_name, $email, $house, $barangay, $city, $province, $country, $zip, $id_value);
        $insert->execute();
        // Insert into addcarts
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $prod_id => $qty) {
                $stmt = $conn->prepare("SELECT prod_price, prod_user_id, prod_name FROM products WHERE prod_id = ?");
                $stmt->bind_param("i", $prod_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $prod_price = $row['prod_price'];
                    $prod_seller_id = $row['prod_user_id'];
                    $prod_name = $row['prod_name'];
                    $addcart_status = "pending";

                    $insertCart = $conn->prepare("INSERT INTO addcarts (addcart_batch_id, addcart_user_id, addcart_seller_id, addcart_prod_id, addcart_pcs, addcart_price, addcart_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $insertCart->bind_param("siiiids", $batch_id, $user_id, $prod_seller_id, $prod_id, $qty, $prod_price, $addcart_status);
                    $insertCart->execute();

                    // Get the ID of the newly inserted addcart row
                    $addcart_id = $insertCart->insert_id;
                    $insertCart->close();

                    $notif_sql = "INSERT INTO notifications (user_id, sender_id, addcart_id, message, type) VALUES (?, ?, ?, ?, 'purchase')";
                    $stmt1 = $conn->prepare($notif_sql);
                    $msg = "Someone purchased your product: $prod_name / $qty pcs / Price : $prod_price";
                    $stmt1->bind_param("iiis", $prod_seller_id, $user_id, $addcart_id, $msg);
                    $stmt1->execute();
                    $stmt1->close();
                }


                $stmt->close();
            }
        }
        /*
        // Get billing_order_id (for linking to the orders table)
        $billing_stmt = $conn->prepare("SELECT billing_order_id FROM billing_orders WHERE billing_$id_field = ?");
        $billing_stmt->bind_param("s", $id_value);
        $billing_stmt->execute();
        $billing_result = $billing_stmt->get_result();

        if ($billing_row = $billing_result->fetch_assoc()) {
            $billing_order_id = $billing_row['billing_order_id'];

            // Insert only one order record for this batch
            $order_status = "pending"; // set your preferred default status
            $order_stmt = $conn->prepare("INSERT INTO orders (order_user_id, order_billing_id, order_batch_id, order_status) VALUES (?, ?, ?, ?)");
            $order_stmt->bind_param("iiss", $user_id, $billing_order_id, $batch_id, $order_status);
            $order_stmt->execute();
            $order_stmt->close();
        }

        $billing_stmt->close();*/
        unset($_SESSION['cart']);
    }

    header("Location: customer_dashboard.php"); // Or order success page

} elseif (isset($_SESSION['temp_id']) && !isset($_SESSION['user_id'])) {
    /* Only temp_id exists → register user

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $full_name = $first_name . ' ' . $last_name;
    $raw_password = $first_name . $last_name;
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
    $user_type = 'customer';

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows === 0) {
        // Insert new user
        $insertUser = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $insertUser->bind_param("ssss", $full_name, $email, $hashed_password, $user_type);
        $insertUser->execute();

        // Get the inserted user_id
        $new_user_id = $insertUser->insert_id;

        // Set user_id in session and unset temp_id
        $_SESSION['user_id'] = $new_user_id;
        unset($_SESSION['temp_id']);
        $_SESSION['checkout_form'] = $_POST;
        // Redirect back to the same page or go to checkout summary
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Email already exists — redirect to login
        header("Location: login_page.php?error=Email already exists");
        exit();
    }*/
} else {
    // No session at all → redirect to login
    header("Location: login_page.php");
    exit();
}
