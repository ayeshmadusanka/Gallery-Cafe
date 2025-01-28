<?php
// Include database connection
include("connection/connect.php");
session_start(); // Start the session

// Initialize response array
$response = array('status' => '', 'message' => '');

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact_number = trim($_POST['contact_number']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (empty($name) || empty($contact_number) || empty($subject) || empty($message)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all required fields.';
    } elseif (!preg_match('/^\d{10}$/', $contact_number)) {
        $response['status'] = 'error';
        $response['message'] = 'Contact number must be 10 digits.';
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO contact (name, contact_number, subject, message) VALUES (?, ?, ?, ?)";

        if ($stmt = $db->prepare($sql)) {
            // Bind parameters and execute query
            $stmt->bind_param('ssss', $name, $contact_number, $subject, $message);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Your message has been sent. Thank you!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to send your message. Please try again.';
            }
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Database error. Please try again later.';
        }
    }
    $db->close();

    // Return response as JSON
    echo json_encode($response);
}
?>
