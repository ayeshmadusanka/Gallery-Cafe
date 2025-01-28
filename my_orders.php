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

// Retrieve orders for the logged-in user
$sql = "SELECT * FROM orders WHERE customer_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$orders = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café | My Orders</title>
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
              <li><a class="nav-link active" href="my_orders.php">My Orders</a></li>
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
        <h2>My Orders Page</h2>
        <ol>
          <li><a href="index.php">Home</a></li>
          <li>My Orders Page</li>
        </ol>
      </div>
    </div>
  </section>
  <section id="orders" class="section-bg">
    <div class="container">
        <div class="section-title">
            <h2>Your Orders</h2>
            <p>Here is a list of your orders.</p>
        </div>

        <div class="row">
            <!-- Card wrapper with dark theme -->
            <div class="col-lg-12">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="card-title">Order List</h5>
                    </div>
                    <div class="card-body">
                        <table id="orderTable" class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php if ($orders->num_rows > 0): ?>
                              <?php while ($order = $orders->fetch_assoc()): ?>
                                  <tr>
                                      <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                      <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                      <td>
                                          <?php
                                          $status = htmlspecialchars($order['status']);
                                          switch ($status) {
                                              case 'Pending':
                                                  echo '<i class="fas fa-hourglass-start" title="Pending"></i> Pending';
                                                  break;
                                              case 'Confirmed':
                                                  echo '<i class="fas fa-check-circle" title="Confirmed"></i> Confirmed';
                                                  break;
                                              case 'Processing':
                                                  echo '<i class="fas fa-cogs" title="Processing"></i> Processing';
                                                  break;
                                              case 'Ready To Pickup':
                                                  echo '<i class="fas fa-box-open" title="Ready To Pickup"></i> Ready To Pickup';
                                                  break;
                                              case 'Canceled':
                                                  echo '<i class="fas fa-times-circle" title="Canceled"></i> Canceled';
                                                  break;
                                              case 'Completed':
                                                  echo '<i class="fas fa-check-double" title="Completed"></i> Completed';
                                                  break;
                                              default:
                                                  echo $status; // Fallback for unknown status
                                          }
                                          ?>
                                      </td>
                                      <td><a href="order_details.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="text-light">View Details</a></td>
                                  </tr>
                              <?php endwhile; ?>
                              <?php else: ?>
                              <tr>
                                  <td colspan="4">You have no orders yet.</td>
                              </tr>
                              <?php endif; ?>
                          </tbody>
                        </table>
                    </div>
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
            "info": true,
            "order": [[1, 'desc']]
        });
    });
</script>


</body>

</html>