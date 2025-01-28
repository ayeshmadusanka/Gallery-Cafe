<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['gallery_cafe_customer_id']) && isset($_SESSION['gallery_cafe_customer_contact_number'])) {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();
    
    // Redirect to the index page
    header("Location: index.php");
    exit();
} else {
    // If the user is not logged in, redirect them to the index page
    header("Location: index.php");
    exit();
}
?>
