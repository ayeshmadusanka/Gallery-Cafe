<?php
require("auth.php");
checkLoggedIn($db);

if (isset($_GET['id']) && isset($_GET['status'])) {
    $reservation_id = $_GET['id'];
    $status = $_GET['status'];

    // Validate the status value
    if ($status != 'Confirmed' && $status != 'Canceled') {
        die('Invalid status');
    }

    // Update the status in the database
    $sql = "UPDATE table_reservations SET status = ? WHERE reservation_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('si', $status, $reservation_id);

    if ($stmt->execute()) {
        header("Location: all_reservations.php");
    } else {
        die('Error updating status');
    }
} else {
    die('Invalid request');
}
?>
