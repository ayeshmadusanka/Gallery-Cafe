<?php
session_start();
header('Content-Type: application/json');

// Ensure cart is set
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form data
    if (isset($_POST['itemId']) && isset($_POST['quantity'])) {
        $itemId = $_POST['itemId'];
        $quantity = intval($_POST['quantity']);

        if ($quantity > 0) {
            if (isset($_SESSION['cart'][$itemId])) {
                $_SESSION['cart'][$itemId]['quantity'] = $quantity;
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item not found in cart.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid quantity.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
