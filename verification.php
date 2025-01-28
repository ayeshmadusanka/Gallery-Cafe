<?php
// Include database connection
include('connection/connect.php');

session_start(); // Start the session

// Function to ensure user is logged in
function ensureLoggedIn() {
  // Return true if the user is logged in
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


// Call the function to check if the user is logged in
ensureLoggedIn();

// Check if customer_id is present in the URL
if (!isset($_GET['customer_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $customer_id = $_POST['customer_id'];

    // Check OTP
    $stmt = $db->prepare("SELECT otp_code FROM otp WHERE customer_id = ? ORDER BY created_at DESC LIMIT 1");
    
    if (!$stmt) {
        die("Error preparing statement: " . $db->error);
    }

    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->bind_result($stored_otp);
    $stmt->fetch();
    
    $stmt->close(); // Close the statement after fetching results

    if ($otp == $stored_otp) {
        // Activate user
        $stmt = $db->prepare("UPDATE customers SET is_active = 1 WHERE customer_id = ?");
        
        if (!$stmt) {
            die("Error preparing statement: " . $db->error);
        }
        
        $stmt->bind_param("i", $customer_id);
        if ($stmt->execute()) {
            $message = "Registration successful! You can now log in.";
             header("Location: login.php");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
        
        $stmt->close(); // Close the statement after executing
    } else {
        $message = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café | Verification</title>
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
        <h2>Verification Page</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Verification Page</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- ======= OTP Verification Section ======= -->
  <section id="contact" class="contact">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Verify OTP</h2>
        <p>Enter the OTP sent to your phone</p>
      </div>

      <div class="row mt-5">
        <div class="col-lg-12 mt-5 mt-lg-0">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?customer_id=<?php echo urlencode($_GET['customer_id']); ?>" method="post" class="php-email-form">
        <div class="form-group mb-3">
              <label for="otp" class="form-label">Enter OTP</label>
              <input type="text" class="form-control" id="otp" name="otp" required>
              <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($_GET['customer_id']); ?>">
            </div>
            <div class="my-3">
              <?php if (isset($message)) { echo '<div class="sent-message">' . htmlspecialchars($message) . '</div>'; } ?>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Verify OTP</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section><!-- End OTP Verification Section -->

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

</body>

</html>