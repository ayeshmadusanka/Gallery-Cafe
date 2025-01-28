<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Function to check if the staff member is logged in
function checkLoggedIn($db) {
    // Check if 'gallerycafe_staff_id' is set in the session
    if (!isset($_SESSION['gallerycafe_staff_id']) || !isset($_SESSION['gallerycafe_staff_name'])) {
        // Redirect to the login page if not set
        header("Location: index.php");
        exit();
    }

    // Optional: Verify that the staff member exists and is active
    $stmt = $db->prepare("SELECT full_name FROM staff WHERE staff_id = ? AND is_active = 1");
    $stmt->bind_param("i", $_SESSION['gallerycafe_staff_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If no matching staff member found or staff is not active, redirect to login page
        unset($_SESSION['gallerycafe_staff_id']);
        unset($_SESSION['gallerycafe_staff_name']);
        header("Location: index.php");
        exit();
    }
}

// Call the function to check if the user is logged in
checkLoggedIn($db);
?>
