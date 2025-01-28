<?php
session_start();

function getCartItemCount() {
  // Check if the cart session is set
  if (isset($_SESSION['cart'])) {
    // Return the total count of items in the cart
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
      $count += $item['quantity'];
    }
    return $count;
  }
  return 0; // No items in the cart
}

// Output the cart item count as JSON
header('Content-Type: application/json');
echo json_encode(['count' => getCartItemCount()]);
?>
