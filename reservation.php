<?php
// Include database connection
include("connection/connect.php");
session_start(); // Start the session

// Initialize alert messages
$success_message = '';
$error_message = '';
$warning_message = '';

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

// Check if user is logged in
if (!ensureLoggedIn()) {
    // Redirect to login page or show an error message
    header("Location: login.php"); 
    exit();
}


// Handle reservation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $customer_id = $_SESSION['gallery_cafe_customer_id'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $people = $_POST['people'];
  $message = $_POST['message'];

  // Validate inputs
  if (!empty($date) && !empty($time) && !empty($people)) {
      // Check if the date is today or in the future
      $current_date = date('Y-m-d');
      if ($date >= $current_date) {
          // Check if the time falls within the allowed ranges
          $time = DateTime::createFromFormat('H:i', $time);
          $lunch_start = DateTime::createFromFormat('H:i', '12:00');
          $lunch_end = DateTime::createFromFormat('H:i', '15:00');
          $dinner_start = DateTime::createFromFormat('H:i', '19:00');
          $dinner_end = DateTime::createFromFormat('H:i', '22:00'); // Dinner ends at 10:00 PM

          if ($time !== false && 
              (($time >= $lunch_start && $time <= $lunch_end) || ($time >= $dinner_start && $time <= $dinner_end))) {
              // Prepare SQL statement
              $sql = "INSERT INTO table_reservations (customer_id, reservation_date, reservation_time, number_of_people, message)
                      VALUES (?, ?, ?, ?, ?)";
              
              if ($stmt = $db->prepare($sql)) {
                  $reservation_date = $date;
                  $reservation_time = $time->format('H:i');
                  $number_of_people = $people;
                  $reservation_message = $message;
              
                  $stmt->bind_param('issis', $customer_id, $reservation_date, $reservation_time, $number_of_people, $reservation_message);
              
                  if ($stmt->execute()) {
                      $success_message = 'Your booking request was sent. We will call back or send a message to confirm your reservation. Thank you!';
                  } else {
                      $error_message = 'Failed to process your reservation. Please try again.';
                  }
                  
                  $stmt->close();
              } else {
                  $error_message = 'Database error. Please try again later.';
              }
          } else {
              $warning_message = 'Please select a time between 12:00 PM - 3:00 PM for lunch or 7:00 PM - 10:00 PM for dinner.';
          }
      } else {
          $warning_message = 'Please select a current or future date for your reservation.';
      }
  } else {
      $warning_message = 'Please fill in all required fields.';
  }
}

// Close the database connection
$db->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café | Reservation</title>
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
        <h2>Reservation Page</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Reservation Page</li>
        </ol>
      </div>
    </div>
  </section>

<!-- ======= Book A Table Section ======= -->
<section id="book-a-table" class="book-a-table">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Reservation</h2>
      <p>Book a Table</p>
    </div>

    <p class="reservation-note">
      We take reservations for lunch (12:00 PM - 3:00 PM) and dinner (07:00 PM - 10:00 PM) . To make a reservation, fill out the form below. A confirmed reservation is valid for a one-hour period from the reserved time on the reserved date. We reserve some tables for walk-in guests to ensure that there are always tables available for those without a reservation.
    </p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" role="form" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
         <!-- Display success, error, or warning messages -->
         <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
            <script>
                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = 'my_reservations.php';
                }, 2000); // 2000 milliseconds = 2 seconds
            </script>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($warning_message)): ?>
            <div class="alert alert-warning">
                <?php echo $warning_message; ?>
            </div>
        <?php endif; ?>
    <div class="row">
        <div class="col-lg-4 col-md-6 form-group mt-3">
          <label for="date">Date</label>
          <input type="date" name="date" class="form-control" id="date" data-rule="required" data-msg="Please select a date">
          <div class="validate"></div>
        </div>
        <div class="col-lg-4 col-md-6 form-group mt-3">
          <label for="time">Time</label>
          <input type="time" class="form-control" name="time" id="time" data-rule="required" data-msg="Please select a time">
          <div class="validate"></div>
        </div>
        <div class="col-lg-4 col-md-6 form-group mt-3">
          <label for="people">Number of People</label>
          <input type="number" class="form-control" name="people" id="people" placeholder="# of people" data-rule="required" data-msg="Please enter the number of people">
          <div class="validate"></div>
        </div>
      </div>
      <div class="form-group mt-3">
        <label for="message">Message</label>
        <textarea class="form-control" name="message" id="message" rows="5" placeholder="Message"></textarea>
        <div class="validate"></div>
      </div>
      <div class="mb-3">
        <div class="loading">Loading</div>
      </div>
      <div class="text-center"><button type="submit">Book a Table</button></div>
      

    </form>

  </div>
</section><!-- End Book A Table Section -->



</main><!-- End #main -->
<!-- Custom Alert -->
<div id="custom-alert" style="display: none;">
  <div id="custom-alert-content">
    <span id="custom-alert-message"></span>
  </div>
</div>
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
  <script>
function showCustomAlert(message) {
    document.getElementById('custom-alert-message').innerText = message;
    document.getElementById('custom-alert').style.display = 'flex';

    // Hide the alert after 3 seconds
    setTimeout(function() {
        document.getElementById('custom-alert').style.display = 'none';
    }, 3000); // 3000 milliseconds = 3 seconds
}

document.querySelector('form').addEventListener('submit', function(event) {
    var timeInput = document.getElementById('time').value;
    var hour = parseInt(timeInput.split(':')[0]);
    var minute = parseInt(timeInput.split(':')[1]);

    if ((hour < 12 || (hour === 12 && minute < 0)) || 
        (hour > 15 && hour < 19) || 
        (hour > 22) || // Adjusted to 22:00 for dinner end
        (hour < 7 && hour > 11) || 
        (hour === 19 && minute < 0)) {
      event.preventDefault();
      showCustomAlert('Please select a time between 12:00 PM - 3:00 PM for lunch or 7:00 PM - 10:00 PM for dinner.');
    }
});
</script>


</body>

</html>