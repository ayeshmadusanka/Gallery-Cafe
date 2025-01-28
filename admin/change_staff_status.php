<?php
require("auth.php");
checkLoggedIn($db);

// Set header to JSON format
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => 'Unknown error'];

// Check if the necessary parameters are set
if (isset($_GET['id']) && isset($_GET['status'])) {
    $staff_id = intval($_GET['id']);
    $current_status = intval($_GET['status']);

    // Toggle status
    $new_status = ($current_status == 1) ? 0 : 1;

    // Update the staff status in the database
    $sql = "UPDATE staff SET is_active = '$new_status' WHERE staff_id = '$staff_id'";
    $query = mysqli_query($db, $sql);

    if ($query) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Error updating status';
    }
} else {
    $response['message'] = 'Invalid parameters';
}

// Output JSON response
echo json_encode($response);

mysqli_close($db);
?>
