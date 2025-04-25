<?php
session_start();
include 'api/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, email, password, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['user_type'];

            // ✅ Set cookies if "Remember Me" is checked
            if (isset($_POST['remember'])) {
                setcookie('email', $email, time() + (86400 * 30), "/"); // 30 days
                setcookie('password', $password, time() + (86400 * 30), "/");
            } else {
                // Clear cookies if checkbox is unchecked
                setcookie('email', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            switch ($user['user_type']) {
                case 'customer':
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirectTo = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']); // Clear it after use
                        header("Location: $redirectTo");
                        exit();
                    } else {
                        header("Location: customer_dashboard.php"); // Default redirect
                        exit();
                    }

                case 'seller':
                    header("Location: seller_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                 // Unknown role
                $_SESSION['error'] = 'Unknown user role.';
                header("Location: login_page.php");
                exit();
            }
        } else {
            // Invalid password
            $_SESSION['error'] = 'Invalid email or password.';
            header("Location: login_page.php");
            exit();
        }
    } else {
       // No user found
        $_SESSION['error'] = 'No user found with that email.';
        header("Location: login_page.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
