<?php
// Include database connection
include('connection/connect.php');

session_start(); // Start the session

// Function to ensure user is logged in
function ensureLoggedIn() {
    return isset($_SESSION['gallery_cafe_customer_id']) && isset($_SESSION['gallery_cafe_customer_contact_number']);
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
// Check if the user is logged in
if (!ensureLoggedIn()) {
    header('Location: login.php?message=You need to login to view your orders.');
    exit();
}

$customerId = $_SESSION['gallery_cafe_customer_id'];
$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// Retrieve order details
$sql = "SELECT order_date, update_date, status FROM orders WHERE order_id = ? AND customer_id = ?";
$stmt = $db->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die('Error preparing order details statement: ' . htmlspecialchars($db->error));
}

$stmt->bind_param("ii", $orderId, $customerId);
$stmt->execute();
$orderDetails = $stmt->get_result()->fetch_assoc();

// Retrieve order items
$sqlItems = "SELECT i.name, i.image_path, oi.quantity, i.price 
             FROM order_items oi 
             JOIN items i ON oi.item_id = i.item_id 
             WHERE oi.order_id = ?";

$stmtItems = $db->prepare($sqlItems);

// Check if the statement was prepared successfully
if ($stmtItems === false) {
    die('Error preparing order items statement: ' . htmlspecialchars($db->error));
}

$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$orderItems = $stmtItems->get_result();

function calculateOrderTotal($orderId) {
    global $db; // Use the database connection

    // SQL to calculate the total price of the order
    $sql = "SELECT SUM(i.price * oi.quantity) AS total 
            FROM order_items oi 
            JOIN items i ON oi.item_id = i.item_id 
            WHERE oi.order_id = ?";
    
    $stmt = $db->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('Error preparing total calculation statement: ' . htmlspecialchars($db->error));
    }

    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the result is valid
    if ($result === false) {
        die('Error executing total calculation statement: ' . htmlspecialchars($stmt->error));
    }

    // Fetch the resulting row
    $row = $result->fetch_assoc();

    // Check if the row exists
    if ($row) {
        return $row['total'] ?? 0;
    } else {
        return 0; // No items found for this order, return 0
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café | Order Details</title>
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

  <!-- DataTables CSS from CDN -->
  <link href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.min.css" rel="stylesheet">



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
        <li><a class="nav-link" href="cart.php">
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
        <h2>Order Details Page</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Order Details Page</li>
        </ol>
      </div>
    </div>
  </section>
  <!-- Display Order Details -->
<section id="order-details" class="menu section-bg">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Order Details</h2>
      <p>Review your order details</p>
    </div>

    <!-- Order Summary -->
    <div class="row">
      <div class="col-lg-12">
        <h3>Order Summary</h3>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($orderDetails['order_date']) ?></p>
        <p><strong>Update Date:</strong> <?= htmlspecialchars($orderDetails['update_date']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($orderDetails['status']) ?></p>
      </div>
    </div>

    <!-- Order Items -->
    <div class="row menu-container">
      <?php if ($orderItems->num_rows > 0): ?>
        <?php while ($item = $orderItems->fetch_assoc()): ?>
          <div class="col-lg-6 menu-item">
            <img src="<?= htmlspecialchars(str_replace('../', '', $item['image_path'])) ?>" class="menu-img" alt="">
            <div class="menu-content">
              <a href="#"><?= htmlspecialchars($item['name']) ?></a>
              <span>LKR <?= number_format($item['price'], 2) ?></span>
            </div>
            <div class="menu-ingredients">
              <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-lg-12"><p>No items available for this order.</p></div>
      <?php endif; ?>
    </div>

    <!-- Order Total -->
    <div class="row">
      <div class="col-lg-12 mt-5">
        <div class="cart-total">
          <h3>Total: LKR <?= number_format(calculateOrderTotal($orderId), 2) ?></h3>
        </div>
      </div>
    </div>
  </div>
</section>


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

 <!-- DataTables JS from CDN -->
 <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
 <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "responsive": true,
                "info": true
            });
        });
    </script>

</body>

</html>