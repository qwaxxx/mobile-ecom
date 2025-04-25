<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: Clear session cookie (recommended)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login or home page
header("Location: login_page.php");
$_SESSION['success'] = 'Logged out successfully!';

exit;
