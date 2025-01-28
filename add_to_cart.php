<?php
session_start(); // Start the session

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($itemId, $itemName, $imagePath, $itemPrice, $quantity) {
  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$itemId])) {
    // If already in the cart, update the quantity
    $_SESSION['cart'][$itemId]['quantity'] += $quantity;
  } else {
    // Otherwise, add the item with specified quantity, name, image path, and price
    $_SESSION['cart'][$itemId] = [
      'quantity' => $quantity,
      'name' => $itemName,
      'image' => $imagePath,
      'price' => $itemPrice
    ];
  }
}

// Get item data from the AJAX request
$itemId = $_POST['item_id'];
$itemName = $_POST['item_name'];
$imagePath = $_POST['image_path'];
$itemPrice = $_POST['price'];
$quantity = intval($_POST['quantity']);

// Validate data and add to cart
if (isset($itemId) && isset($itemName) && isset($imagePath) && isset($itemPrice) && $quantity > 0) {
  addToCart($itemId, $itemName, $imagePath, $itemPrice, $quantity);

  // Send a success response
  echo "success";
} else {
  echo "error";
}
?>
