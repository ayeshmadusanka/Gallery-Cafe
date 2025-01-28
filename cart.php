<?php
// Include database connection
include('connection/connect.php');

session_start(); // Start the session

// Function to ensure user is logged in
function ensureLoggedIn() {
    return isset($_SESSION['gallery_cafe_customer_id']) && isset($_SESSION['gallery_cafe_customer_contact_number']);
}

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate the cart total
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

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


// Initialize success message variable
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {

    // Check if the user is logged in
    if (!ensureLoggedIn()) {
        header('Location: login.php?message=You need to login before checking out.');
        exit();
    }

    $customerId = $_SESSION['gallery_cafe_customer_id'];
    $cart = $_SESSION['cart'];

    // Start transaction
    mysqli_begin_transaction($db);

    try {
        // Insert into orders table
        $sql = "INSERT INTO orders (customer_id) VALUES (?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Insert each cart item into order_items table
        $sql = "INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        foreach ($cart as $itemId => $item) {
            $stmt->bind_param("iii", $orderId, $itemId, $item['quantity']);
            $stmt->execute();
        }

        // Commit transaction
        mysqli_commit($db);

        // Clear the cart session
        unset($_SESSION['cart']);

        // Set success message
        $successMessage = 'Order placed successfully';

    } catch (Exception $e) {
        // Rollback transaction in case of error
        mysqli_rollback($db);
        echo "Failed to place order: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café | Cart</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

 <!-- ======= Top Bar ======= -->
 <div id="topbar" class="d-flex align-items-center fixed-top">
  <div class="container d-flex justify-content-center justify-content-md-between">

    <div class="contact-info d-flex align-items-center">
      <i class="bi bi-phone d-flex align-items-center"><span>+94 11 222 4287</span></i>
      <i class="bi bi-clock d-flex align-items-center ms-4"><span> Mon-Sun: 11 AM - 11 PM </span></i>
    </div>
  </div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-lg-between">

    <h1 class="logo me-auto me-lg-0"><a href="index.php">Gallery Café</a></h1>

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="nav-link scrollto" href="index.php">Home</a></li>
        <li><a class="nav-link scrollto" href="index.php#about">About</a></li>
        <li><a class="nav-link scrollto" href="index.php#menu">Menu</a></li>
        <li><a class="nav-link scrollto" href="index.php#specials">Specials</a></li>
        <li><a class="nav-link scrollto" href="index.php#events">Events</a></li>
        <li><a class="nav-link scrollto" href="index.php#chefs">Chefs</a></li>
        <li><a class="nav-link scrollto" href="index.php#gallery">Gallery</a></li>
        <li><a class="nav-link scrollto" href="index.php#contact">Contact</a></li>

        <?php if (ensureLoggedIn()): ?>
          <!-- Dropdown for logged-in users -->
          <li class="dropdown">
            <a href="#"><span>Account</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a class="nav-link" href="my_orders.php">My Orders</a></li>
              <li><a class="nav-link" href="my_reservations.php">My Reservations</a></li>
              <li><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Links for not logged-in users -->
          <li><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
        <li><a class="nav-link active" href="cart.php">
            <i class="fa fa-shopping-cart cart-icon"></i>
            <span class="cart-count"><?php echo getCartItemCount(); ?></span></a>
        </li>

      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->
    <a href="reservation.php" class="book-a-table-btn scrollto d-none d-lg-flex">Book a table</a>

  </div>
</header><!-- End Header -->

<main id="main">
  <section class="breadcrumbs">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <h2>Cart Page</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Cart Page</li>
        </ol>
      </div>
    </div>
  </section>
  <?php if (!empty($successMessage)) : ?>
    <div class="custom-alert">
        <div id="custom-alert-content">
            <p id="custom-alert-message"><?php echo $successMessage; ?></p>
        </div>
        <script>
            setTimeout(function(){
                window.location.href = "my_orders.php";
            }, 2000); // 2000 milliseconds = 2 seconds
        </script>
    </div>
<?php endif; ?>

<section id="cart" class="menu section-bg">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Your Cart</h2>
      <p>Review and manage your cart items</p>
    </div>

    <div class="row menu-container">
      <?php
      // Ensure cart is set
      if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo '<div class="col-lg-12"><p>Your cart is empty.</p></div>';
      } else {
        $cart = $_SESSION['cart'];

        // Display cart items
        foreach ($cart as $itemId => $item) {
          echo '<div class="col-lg-6 menu-item">
                  <img src="' . htmlspecialchars($item['image']) . '" class="menu-img" alt="">
                  <div class="menu-content">
                    <a href="#">' . htmlspecialchars($item['name']) . '</a><span>LKR ' . number_format($item['price'], 2) . '</span>
                  </div>
                  <div class="menu-ingredients">
                    <form method="post" action="" class="quantity-form" data-id="' . htmlspecialchars($itemId) . '">
                      <input type="hidden" name="itemId" value="' . htmlspecialchars($itemId) . '" />
                      Quantity: <input type="number" name="quantity" value="' . htmlspecialchars($item['quantity']) . '" min="1" class="quantity-input" />
                      <button type="submit" name="update_quantity" class="quantity-btn">Update</button>
                    </form>
                    <button class="remove-from-cart" data-id="' . htmlspecialchars($itemId) . '">Remove</button>
                  </div>
                </div>';
        }
      }
      ?>
    </div>

    <div class="row">
      <div class="col-lg-12 mt-5">
        <div class="cart-total">
          <h3>Total: LKR <?php echo number_format(calculateCartTotal(), 2); ?></h3>
          <form method="post" action="">
            <button type="submit" name="checkout" class="add-to-cart">Proceed to Checkout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="custom-alert" style="display:none;">
  <div id="custom-alert-content">
    <p id="custom-alert-message"></p>
  </div>
</div>

</main><!-- End #main -->

  <!-- ======= Footer ======= -->
<footer id="footer">
  <div class="footer-top">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-md-6">
          <div class="footer-info">
            <h3>The Gallery Café</h3>
            <p>
              No. 45 , Galle Road <br>
              Colombo 03, Sri Lanka<br><br>
              <strong>Phone:</strong> +94 11 222 4287<br>
              <strong>Email:</strong> info@gallerycafe.lk<br>
            </p>
          </div>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php">Home</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#about">About</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#menu">Menu</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#specials">Specials</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Additional Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#events">Events</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#chefs">Chefs</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#gallery">Gallery</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="index.php#contact">Contact</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Account</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="login.php">Login</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="registration.php">Register</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Bookings</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="reservation.php">Book a Table</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="container">
    <div class="copyright">
      &copy; Copyright <strong><span>The Gallery Café</span></strong>. All Rights Reserved
    </div>
  </div>
</footer><!-- End Footer -->




  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
  // Function to show custom alert
  function showAlert(message) {
    $('#custom-alert-message').text(message);
    $('#custom-alert').fadeIn();
    setTimeout(function() {
      $('#custom-alert').fadeOut(function() {
        // Reload the page after the alert has faded out
        location.reload();
      });
    }, 1000); // 3000 milliseconds = 3 seconds
  }

  // Handle quantity update
  $('.quantity-form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    $.ajax({
      url: 'update_cart.php',
      method: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      success: function (data) {
        if (data.success) {
          showAlert('Quantity updated successfully.');
        } else {
          showAlert('Error updating quantity: ' + data.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        showAlert('Network error: ' + errorThrown);
      }
    });
  });

  // Handle item removal
  $('.remove-from-cart').on('click', function () {
    var itemId = $(this).data('id');
    $.ajax({
      url: 'remove_from_cart.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: itemId }),
      dataType: 'json',
      success: function (data) {
        if (data.success) {
          showAlert('Item removed from cart.');
        } else {
          showAlert('Error removing item from cart: ' + data.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        showAlert('Network error: ' + errorThrown);
      }
    });
  });
});

</script>
</body>

</html>