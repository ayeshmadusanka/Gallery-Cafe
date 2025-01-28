<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Function to check if the user is logged in
function checkLoggedIn($db) {
    // Check if 'gallerycafe_admin' is not set in the session or if the user doesn't exist, redirect to index
    if (!isset($_SESSION['gallerycafe_admin']) || !fetchGalleryCafeAdminName($db, $_SESSION['gallerycafe_admin'])) {
        header("Location: index.php");
        exit();
    }
}

// Function to fetch gallerycafe_admin_username from the database
function fetchGalleryCafeAdminName($db, $gallerycafe_admin_id) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $db->prepare("SELECT gallerycafe_admin_username FROM gallerycafe_admin WHERE gallerycafe_admin_id = ?");
    $stmt->bind_param("i", $gallerycafe_admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        // Fetch the gallerycafe_admin_username from the result
        $row = $result->fetch_assoc();
        return $row['gallerycafe_admin_username'];
    }
    return false;
}

// Call the function to check if the user is logged in
checkLoggedIn($db);

// Optionally fetch the admin name to use later in the script
$galleryCafeAdminName = fetchGalleryCafeAdminName($db, $_SESSION['gallerycafe_admin']);
?>
