<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
session_start(); // Start the session

// Function to ensure user is logged in
function ensureLoggedIn() {
  // Return true if the user is logged in
  return isset($_SESSION['gallery_cafe_customer_id']) && isset($_SESSION['gallery_cafe_customer_contact_number']);
}

// Call the function to check if the user is logged in
ensureLoggedIn();

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
function calculateCartTotal() {
  $total = 0;

  // Ensure cart is set and is an array
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }

  return $total;
}

?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>The Gallery Café</title>
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

  <!-- Template Main CSS File -->
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
    <!-- Uncomment below if you prefer to use an image logo -->
    <!-- <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
        <li><a class="nav-link scrollto" href="#about">About</a></li>
        <li><a class="nav-link scrollto" href="#menu">Menu</a></li>
        <li><a class="nav-link scrollto" href="#specials">Specials</a></li>
        <li><a class="nav-link scrollto" href="#events">Events</a></li>
        <li><a class="nav-link scrollto" href="#chefs">Chefs</a></li>
        <li><a class="nav-link scrollto" href="#gallery">Gallery</a></li>
        <li><a class="nav-link scrollto" href="#contact">Contact</a></li>

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
                <span class="cart-count">0</span></a>
            </li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->
    <a href="reservation.php" class="book-a-table-btn scrollto d-none d-lg-flex">Book a table</a>

  </div>
</header><!-- End Header -->


  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container position-relative text-center text-lg-start" data-aos="zoom-in" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-8">
          <h1>Welcome to <span>The Gallery Café</span></h1>
          <h2>Delivering great food for more than 18 years!</h2>

          <div class="btns">
            <a href="#menu" class="btn-menu animated fadeInUp scrollto">Our Menu</a>
            <a href="reservation.php" class="btn-book animated fadeInUp scrollto">Book a Table</a>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
<section id="about" class="about">
  <div class="container" data-aos="fade-up">

    <div class="row">
      <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="100">
        <div class="about-img">
          <img src="assets/img/about.jpg" alt="Gallery Café Interior">
        </div>
      </div>
      <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
        <h3>Welcome to The Gallery Café – Where Culinary Excellence Meets Elegance</h3>
        <p class="fst-italic">
          At The Gallery Café, we pride ourselves on delivering an exceptional dining experience with a perfect blend of flavors, ambiance, and service.
        </p>
        <ul>
          <li><i class="bi bi-check-circle"></i> Experience a wide range of gourmet dishes crafted with fresh, locally sourced ingredients.</li>
          <li><i class="bi bi-check-circle"></i> Enjoy our elegant and cozy atmosphere, perfect for both casual meals and special occasions.</li>
          <li><i class="bi bi-check-circle"></i> Explore our seasonal promotions and special events, designed to make every visit memorable.</li>
        </ul>
        <p>
          Our dedicated team at The Gallery Café is committed to providing you with an unforgettable dining experience. Whether you're here for a quick lunch, a romantic dinner, or a family gathering, our warm hospitality and exquisite menu offerings ensure that every moment with us is delightful. Join us and let us make your dining experience extraordinary.
        </p>
      </div>
    </div>

  </div>
</section><!-- End About Section -->


   <!-- ======= Why Us Section ======= -->
<section id="why-us" class="why-us">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Why Us</h2>
      <p>Why Choose The Gallery Café</p>
    </div>

    <div class="row">

      <div class="col-lg-4">
        <div class="box" data-aos="zoom-in" data-aos-delay="100">
          <span>01</span>
          <h4>Spacious Table Capacities</h4>
          <p>Our restaurant offers a variety of table options to accommodate different group sizes. Whether you're planning an intimate dinner or a large family gathering, we have the perfect space for you.</p>
        </div>
      </div>

      <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="box" data-aos="zoom-in" data-aos-delay="200">
          <span>02</span>
          <h4>Convenient Parking Availability</h4>
          <p>We understand the importance of easy access to parking. Our restaurant provides ample parking spaces for our guests, ensuring a hassle-free visit from start to finish.</p>
        </div>
      </div>

      <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="box" data-aos="zoom-in" data-aos-delay="300">
          <span>03</span>
          <h4>Exciting Special Promotions</h4>
          <p>Stay tuned for our seasonal promotions and exclusive offers. We regularly update our special deals to bring you great value and make your dining experience even more enjoyable.</p>
        </div>
      </div>

    </div>

  </div>
</section><!-- End Why Us Section -->

<section id="menu" class="menu section-bg">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Food Menu</h2>
      <p>Check Our Delicious Food Options</p>
    </div>

    <div class="row" data-aos="fade-up" data-aos-delay="100">
      <div class="col-lg-12 d-flex justify-content-center">
        <ul id="menu-flters">
          <li data-filter="*" class="filter-active">All</li>
          <li data-filter=".filter-sri-lankan">Sri Lankan</li>
          <li data-filter=".filter-chinese">Chinese</li>
          <li data-filter=".filter-italian">Italian</li>
          <li data-filter=".filter-other">Other</li>
        </ul>
      </div>
    </div>

    <div class="row menu-container" data-aos="fade-up" data-aos-delay="200">
      <?php
      $sql = "SELECT * FROM items WHERE item_type = 'Food' AND is_active = 1;";
      $query = mysqli_query($db, $sql);

      if (mysqli_num_rows($query) > 0) {
        while ($rows = mysqli_fetch_array($query)) {
          $imagePath = str_replace('../', '', $rows['image_path']);
          $cuisineType = strtolower(str_replace(' ', '-', htmlspecialchars($rows['cuisine_type'])));

          echo '<div class="col-lg-6 menu-item filter-' . $cuisineType . '">
                  <img src="' . htmlspecialchars($imagePath) . '" class="menu-img" alt="">
                  <div class="menu-content">
                    <a href="#">' . htmlspecialchars($rows['name']) . '</a><span>LKR ' . number_format($rows['price'], 2) . '</span>
                  </div>
                  <div class="menu-ingredients">
                    ' . htmlspecialchars($rows['description']) . '
                  </div>
                  <div class="quantity-controls">
                    <button class="quantity-btn minus" data-id="' . $rows['item_id'] . '">-</button>
                    <input type="text" class="quantity-input" data-id="' . $rows['item_id'] . '" value="1">
                    <button class="quantity-btn plus" data-id="' . $rows['item_id'] . '">+</button>
                  </div>
                  <button class="add-to-cart" 
                          data-id="' . $rows['item_id'] . '" 
                          data-name="' . htmlspecialchars($rows['name']) . '" 
                          data-image="' . htmlspecialchars($imagePath) . '"
                          data-price="' . htmlspecialchars($rows['price']) . '">Add to Cart</button>
                </div>';
        }
      } else {
        echo '<div class="col-lg-12"><p>No food items available.</p></div>';
      }
      ?>
    </div>
  </div>
</section><!-- End Food Menu Section -->

<section id="beverages-menu" class="menu section-bg">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Beverages Menu</h2>
      <p>Discover Our Refreshing Beverages</p>
    </div>

    <div class="row" data-aos="fade-up" data-aos-delay="100">
      <div class="col-lg-12 d-flex justify-content-center">
        <ul id="beverages-flters">
          <li data-filter="*" class="filter-active">All</li>
          <li data-filter=".filter-soft-drink">Soft Drink</li>
          <li data-filter=".filter-juice">Juice</li>
          <li data-filter=".filter-alcoholic">Alcoholic</li>
          <li data-filter=".filter-other">Other</li>
        </ul>
      </div>
    </div>

    <div class="row menu-container" data-aos="fade-up" data-aos-delay="200">
      <?php
      $sql = "SELECT * FROM items WHERE item_type = 'Beverage' AND is_active = 1;";
      $query = mysqli_query($db, $sql);

      if (mysqli_num_rows($query) > 0) {
        while ($rows = mysqli_fetch_array($query)) {
          $imagePath = str_replace('../', '', $rows['image_path']);
          $beverageType = strtolower(str_replace(' ', '-', htmlspecialchars($rows['beverage_type'])));

          echo '<div class="col-lg-6 menu-item filter-' . $beverageType . '">
                  <img src="' . htmlspecialchars($imagePath) . '" class="menu-img" alt="">
                  <div class="menu-content">
                    <a href="#">' . htmlspecialchars($rows['name']) . '</a><span>LKR ' . number_format($rows['price'], 2) . '</span>
                  </div>
                  <div class="menu-ingredients">
                    ' . htmlspecialchars($rows['description']) . '
                  </div>
                  <div class="quantity-controls">
                    <button class="quantity-btn minus" data-id="' . $rows['item_id'] . '">-</button>
                    <input type="text" class="quantity-input" data-id="' . $rows['item_id'] . '" value="1" readonly>
                    <button class="quantity-btn plus" data-id="' . $rows['item_id'] . '">+</button>
                  </div>
                  <button class="add-to-cart" 
                          data-id="' . $rows['item_id'] . '" 
                          data-name="' . htmlspecialchars($rows['name']) . '" 
                          data-image="' . htmlspecialchars($imagePath) . '"
                          data-price="' . htmlspecialchars($rows['price']) . '">Add to Cart</button>
                </div>';
        }
      } else {
        echo '<div class="col-lg-12"><p>No beverages available.</p></div>';
      }
      ?>
    </div>
  </div>
</section><!-- End Beverages Menu Section -->


    <!-- ======= Specials Section ======= -->
<section id="specials" class="specials">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Specials</h2>
      <p>Check Our Specials</p>
    </div>

    <div class="row" data-aos="fade-up" data-aos-delay="100">
      <div class="col-lg-3">
        <ul class="nav nav-tabs flex-column">
          <li class="nav-item">
            <a class="nav-link active show" data-bs-toggle="tab" href="#tab-1">Sri Lankan Delights</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-2">Italian Classics</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-3">Chinese Favorites</a>
          </li>
        </ul>
      </div>
      <div class="col-lg-9 mt-4 mt-lg-0">
        <div class="tab-content">
          <div class="tab-pane active show" id="tab-1">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Authentic Sri Lankan Curry</h3>
                <p class="fst-italic">A blend of traditional spices and local ingredients for a true Sri Lankan experience.</p>
                <p>Experience the rich flavors of Sri Lanka with our special curry made from fresh, locally-sourced ingredients. Our Sri Lankan curry is a mix of spicy, aromatic, and savory flavors that will transport you to the heart of Sri Lanka.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="assets/img/specials-1.png" alt="Sri Lankan Curry" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-2">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Classic Italian Pasta</h3>
                <p class="fst-italic">A traditional Italian pasta dish with a rich tomato sauce and fresh basil.</p>
                <p>Indulge in the classic Italian flavors with our pasta dish, featuring a savory tomato sauce made from ripe tomatoes and aromatic basil. This dish captures the essence of Italian cuisine with every bite.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="assets/img/specials-2.png" alt="Italian Pasta" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-3">
            <div class="row">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3>Delicious Chinese Dumplings</h3>
                <p class="fst-italic">Steamed dumplings with a flavorful filling and a savory dipping sauce.</p>
                <p>Our Chinese dumplings are filled with a mix of tender meat and fresh vegetables, perfectly steamed to preserve their flavor and juiciness. Served with a savory dipping sauce, these dumplings are a true taste of China.</p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="assets/img/specials-3.png" alt="Chinese Dumplings" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section><!-- End Specials Section -->

<!-- ======= Events Section ======= -->
<section id="events" class="events">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Events</h2>
      <p>Organize Your Events at The Gallery Café</p>
    </div>

    <div class="events-slider swiper-container" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="row event-item">
            <div class="col-lg-6">
              <img src="assets/img/event-birthday.jpg" class="img-fluid" alt="Birthday Party">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 content">
              <h3>Birthday Parties</h3>
              <div class="price">
                <p><span>LKR 38,000</span> per event</p>
              </div>
              <p class="fst-italic">
                Celebrate your special day with us in style. Our spacious venue and exceptional service will make your birthday unforgettable.
              </p>
              <ul>
                <li><i class="bi bi-check-circled"></i> Customizable party setups to fit your theme.</li>
                <li><i class="bi bi-check-circled"></i> Delicious menu options tailored to your preferences.</li>
                <li><i class="bi bi-check-circled"></i> Professional staff to ensure a seamless event experience.</li>
              </ul>
              <p>
                We offer various packages for birthday parties, including decorations, catering, and entertainment options. Contact us to plan your perfect celebration.
              </p>
            </div>
          </div>
        </div><!-- End birthday party item -->

        <div class="swiper-slide">
          <div class="row event-item">
            <div class="col-lg-6">
              <img src="assets/img/event-private.jpg" class="img-fluid" alt="Private Party">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 content">
              <h3>Private Parties</h3>
              <div class="price">
                <p><span>LKR 58,000</span> per event</p>
              </div>
              <p class="fst-italic">
                Host your private event in our exclusive dining areas for a more intimate and personalized experience.
              </p>
              <ul>
                <li><i class="bi bi-check-circled"></i> Private rooms with exclusive access.</li>
                <li><i class="bi bi-check-circled"></i> Customizable menus and service options.</li>
                <li><i class="bi bi-check-circled"></i> Dedicated event coordinator to assist with planning.</li>
              </ul>
              <p>
                Our private party packages are designed to meet your specific needs, from small gatherings to larger events. Reach out to us for more details and to book your date.
              </p>
            </div>
          </div>
        </div><!-- End private party item -->

        <div class="swiper-slide">
          <div class="row event-item">
            <div class="col-lg-6">
              <img src="assets/img/event-custom.jpg" class="img-fluid" alt="Custom Party">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 content">
              <h3>Custom Parties</h3>
              <div class="price">
                <p><span>LKR 22,000</span> per hour</p>
              </div>
              <p class="fst-italic">
                Whether it's a themed event or a special gathering, our team will help you create a unique and memorable experience.
              </p>
              <ul>
                <li><i class="bi bi-check-circled"></i> Tailored event packages to match your vision.</li>
                <li><i class="bi bi-check-circled"></i> Flexible scheduling and setup options.</li>
                <li><i class="bi bi-check-circled"></i> High-quality service and amenities to suit your needs.</li>
              </ul>
              <p>
                Our custom party packages offer flexibility and creativity for any occasion. Contact us to discuss your ideas and secure your event.
              </p>
            </div>
          </div>
        </div><!-- End custom party item -->

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>
</section><!-- End Events Section -->

   <!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials section-bg">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Testimonials</h2>
      <p>What our guests are saying about us</p>
    </div>

    <div class="testimonials-slider swiper-container" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              "The Gallery Café is a wonderful place to dine. The atmosphere is cozy, and the food is consistently excellent. I always enjoy my visits here."
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-1.png" class="testimonial-img" alt="Chathura Perera">
            <h3>Chathura Perera</h3>
            <h4>Entrepreneur</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              "Fantastic dining experience at The Gallery Café! The service was exceptional, and the food was delicious. Definitely a top spot in Colombo."
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-2.png" class="testimonial-img" alt="Samanthi Silva">
            <h3>Samanthi Silva</h3>
            <h4>Designer</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              "The Gallery Café offers a delightful atmosphere and superb food. It’s my go-to place for a relaxing meal with friends or family."
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-3.png" class="testimonial-img" alt="Ravi Kumar">
            <h3>Ravi Kumar</h3>
            <h4>Business Owner</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              "Always a pleasure to dine at The Gallery Café. The staff is friendly, and the menu offers a variety of delicious options. Highly recommended!"
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-4.png" class="testimonial-img" alt="Nadeesha Weerasinghe">
            <h3>Nadeesha Weerasinghe</h3>
            <h4>Freelancer</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              "The Gallery Café is a gem in Colombo. The food is amazing, and the ambiance is perfect for any occasion. Always a great experience!"
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-5.png" class="testimonial-img" alt="Dinusha Fernando">
            <h3>Dinusha Fernando</h3>
            <h4>Marketing Executive</h4>
          </div>
        </div><!-- End testimonial item -->

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>
</section><!-- End Testimonials Section -->

    <!-- ======= Gallery Section ======= -->
    <section id="gallery" class="gallery">

      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Gallery</h2>
          <p>Some photos from Our Restaurant</p>
        </div>
      </div>

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-1.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-2.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-3.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-4.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-4.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-5.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-6.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-7.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-7.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-8.jpg" class="gallery-lightbox" data-gall="gallery-item">
                <img src="assets/img/gallery/gallery-8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Gallery Section -->

    <!-- ======= Chefs Section ======= -->
<section id="chefs" class="chefs">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Chefs</h2>
      <p>Our Professional Chefs</p>
    </div>

    <div class="row">

      <div class="col-lg-4 col-md-6">
        <div class="member" data-aos="zoom-in" data-aos-delay="100">
          <img src="assets/img/chefs/chefs-1.jpeg" class="img-fluid" alt="Chef Anuradha">
          <div class="member-info">
            <div class="member-info-content">
              <h4>Anuradha Perera</h4>
              <span>Head Chef</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="member" data-aos="zoom-in" data-aos-delay="200">
          <img src="assets/img/chefs/chefs-2.jpeg" class="img-fluid" alt="Chef Sanduni">
          <div class="member-info">
            <div class="member-info-content">
              <h4>Sanduni Fernando</h4>
              <span>Pastry Chef</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="member" data-aos="zoom-in" data-aos-delay="300">
          <img src="assets/img/chefs/chefs-3.jpeg" class="img-fluid" alt="Chef Nimal">
          <div class="member-info">
            <div class="member-info-content">
              <h4>Nimal Rajapaksa</h4>
              <span>Sous Chef</span>
            </div>           
          </div>
        </div>
      </div>

    </div>

  </div>
</section><!-- End Chefs Section -->


<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
  <div class="container" data-aos="fade-up">
    <div class="section-title">
      <h2>Contact</h2>
      <p>Contact Us</p>
    </div>
  </div>

  <div data-aos="fade-up">
    <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d79.9588531!3d6.9270798!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5c236a8a3d59f5e2!2z2LTYsdmD2KfZhiDYqNin2KrZhzY!5e0!3m2!1sen!2slk!4v1639530746925" frameborder="0" allowfullscreen></iframe>
  </div>

  <div class="container" data-aos="fade-up">
    <div class="row mt-5">
      <div class="col-lg-4">
        <div class="info">
          <div class="address">
            <i class="bi bi-geo-alt"></i>
            <h4>Location:</h4>
            <p>No.45 , Galle Road, Colombo 03, Sri Lanka</p>
          </div>

          <div class="open-hours">
            <i class="bi bi-clock"></i>
            <h4>Open Hours:</h4>
            <p>
              Monday-Sunday:<br>
              11:00 AM - 11:00 PM
            </p>
          </div>

          <div class="phone">
            <i class="bi bi-phone"></i>
            <h4>Call:</h4>
            <p>+94 11 222 4287</p>
          </div>
        </div>
      </div>

      <div class="col-lg-8 mt-5 mt-lg-0">
        <form id="contact-form" method="post" role="form" class="php-email-form">
          <div class="row">
            <div class="col-md-6 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
            </div>
            <div class="col-md-6 form-group mt-3 mt-md-0">
              <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Your Contact Number" required>
            </div>
          </div>
          <div class="form-group mt-3">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
          </div>
          <div class="form-group mt-3">
            <textarea class="form-control" name="message" rows="8" placeholder="Message" required></textarea>
          </div>
          <div class="my-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Send Message</button></div>
        </form>
      </div>
    </div>
  </div>
</section><!-- End Contact Section -->

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
            <li><i class="bx bx-chevron-right"></i> <a href="#about">About</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#menu">Menu</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#specials">Specials</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-6 footer-links">
          <h4>Additional Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#events">Events</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#chefs">Chefs</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#gallery">Gallery</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#contact">Contact</a></li>
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

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
$(document).ready(function() {
  // Handle quantity adjustments
  $('.quantity-btn').click(function() {
    var $input = $(this).siblings('.quantity-input');
    var currentValue = parseInt($input.val());
    var newValue = $(this).hasClass('plus') ? currentValue + 1 : Math.max(currentValue - 1, 1);
    $input.val(newValue);
  });

  // Handle add to cart button click
  $('.add-to-cart').click(function() {
    var itemId = $(this).data('id');
    var itemName = $(this).data('name');
    var imagePath = $(this).data('image');
    var itemPrice = $(this).data('price');
    var quantity = $(this).siblings('.quantity-controls').find('.quantity-input').val();

    // AJAX request to add the item to the cart
    $.ajax({
      url: 'add_to_cart.php',
      type: 'POST',
      data: {
        item_id: itemId,
        item_name: itemName,
        image_path: imagePath,
        price: itemPrice,
        quantity: quantity
      },
      success: function(response) {
        $('#custom-alert-message').text('Item has been added to your cart!');
        $('#custom-alert').fadeIn();

        setTimeout(function() {
          $('#custom-alert').fadeOut();
          // Update the cart item count
          updateCartItemCount();
        }, 2000); // 2000 milliseconds = 2 seconds
      }
    });
  });

  // Close the custom alert immediately when the OK button is clicked
  $('#custom-alert-close').click(function() {
    $('#custom-alert').fadeOut();
  });

  function showAlert(message) {
    $('#custom-alert-message').text(message);
    $('#custom-alert').fadeIn();
    setTimeout(function() {
        $('#custom-alert').fadeOut(function() {
            // Reload the page after the alert has faded out
            location.reload();
        });
    }, 3000); // 3000 milliseconds = 3 seconds
  }

  $('#contact-form').submit(function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Validate contact number (10 digits)
    var contactNumber = $('#contact_number').val();
    if (!/^\d{10}$/.test(contactNumber)) {
      alert('Contact number must be 10 digits.');
      return;
    }

    // Collect form data
    var formData = $(this).serialize();

    $.ajax({
      url: 'contact.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          showAlert('Your message has been sent. We will call back regarding your inquiry. Thank you!');
        } else {
          showAlert(response.message);
        }
      },
      error: function() {
        showAlert('An error occurred. Please try again.');
      }
    });
  });

  // Function to update the cart item count
  function updateCartItemCount() {
    $.ajax({
      url: 'cart_item.php',
      type: 'GET',
      success: function(response) {
        $('.cart-count').text(response.count);
      },
      error: function() {
        console.error('Failed to update cart item count.');
      }
    });
  }

  // Initial update of the cart item count when the page loads
  updateCartItemCount();
});

</script>



</body>

</html>