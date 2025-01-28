<?php
// Start the session
session_start();

// Include database connection
include 'connection/connect.php';

// Function to ensure user is logged in
function ensureLoggedIn() {
  // Check if the user is logged in
  if (isset($_SESSION['gallery_cafe_customer_id']) && isset($_SESSION['gallery_cafe_customer_contact_number'])) {
      // Redirect to index.php
      header("Location: index.php");
      exit(); // Stop further script execution
  }
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
// Initialize variables for messages
$login_error_message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact_number = trim($_POST['contact_number']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($contact_number) || empty($password)) {
        $login_error_message = "Please enter both contact number and password.";
    } else {
        // Prepare statement to get user data
        $stmt = $db->prepare("SELECT customer_id, password, is_active FROM customers WHERE contact_number = ?");
        $stmt->bind_param("s", $contact_number);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($customer_id, $hashed_password, $is_active);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Check if user is active
                if ($is_active) {
                    // Store customer ID and contact number in session
                    $_SESSION['gallery_cafe_customer_id'] = $customer_id;
                    $_SESSION['gallery_cafe_customer_contact_number'] = $contact_number;

                    // Redirect to cart
                    header("Location: cart.php");
                    exit();
                } else {
                    $login_error_message = "Your account is not active. Please verify your OTP.";
                }
            } else {
                $login_error_message = "Invalid password. Please try again.";
            }
        } else {
            $login_error_message = "No account found with that contact number.";
        }

        $stmt->close(); // Close the statement after use
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>The Gallery Café | Login</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
          <li><a class="nav-link active" href="login.php">Login</a></li>
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
          <h2>Login Page</h2>
          <ol>
            <li><a href="index.php">Home</a></li>
            <li>Login Page</li>
          </ol>
        </div>
      </div>
    </section>

   <!-- ======= Login Section ======= -->
<section id="login" class="contact">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Login</h2>
      <p>Customer Login</p>
    </div>
  </div>

  <div class="container" data-aos="fade-up">
    <div class="row mt-5">
      <div class="col-lg-12 mt-5 mt-lg-0">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="php-email-form">
          <?php if ($login_error_message): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $login_error_message; ?>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-6 form-group mt-3">
              <label for="contact_number" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="contact_number" name="contact_number" required>
            </div>
            <div class="col-md-6 form-group mt-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>

        <!-- Registration link -->
        <div class="mt-3 text-center">
          <p>Don't have an account? <a href="registration.php">Please register</a></p>
        </div>
      </div>
    </div>
  </div>
</section><!-- End Login Section -->

  </main>

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
