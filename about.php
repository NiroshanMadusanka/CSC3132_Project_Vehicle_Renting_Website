<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RENT & RIDE</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <style>
      body {
        background: url("img/about\ background.jpg") no-repeat center center
          fixed;
        background-size: cover;
        padding-top: 70px;
      }
    </style>
  </head>

  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <img
            src="img/LogoNew.jpg"
            alt="Rent & Ride Logo"
            style="height: 40px; margin-right: 10px"
          />
          Rent & Ride
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="vehicles.php">Vehicles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>

            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link btn btn-warning text-white" href="myadmin.php"
                >Admin Dashboard</a
              >
            </li>
            <?php endif; ?>

           
            <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link btn btn-secondary text-white" href="logout.php"
                >Logout</a
              >
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link btn btn-primary text-white" href="login.php"
                >Login</a
              >
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <section class="container my-5 text-center text-dark">
      <h1 class="mb-4" style="font-weight: 600">About Us</h1>
      <div
        style="
          background-color: rgba(255, 255, 255, 0.8);
          padding: 30px;
          border-radius: 10px;
        "
      >
        <p class="fs-5">
          Welcome to Rent & Ride, Sri Lanka’s premier vehicle renting platform!
          We are committed to providing easy and reliable transport solutions to
          everyone, whether you're exploring the beautiful beaches of Galle,
          heading to the scenic highlands of Kandy, or navigating Colombo's
          bustling city streets.
        </p>
        <p class="fs-5">
          Our mission is to bridge convenience and affordability, offering a
          wide selection of vehicles ranging from compact cars to luxurious SUVs
          and bikes. With Rent & Ride, your journey begins with trust and ends
          with satisfaction.
        </p>
        <p class="fs-5">
          Explore Sri Lanka with confidence and ease—because your journey
          matters to us.
        </p>
      </div>

      <h2 class="mt-5 mb-4" style="font-weight: 600">Customer Feedback</h2>
      <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
          <div class="p-3 border rounded bg-light">
            <p>
              "Rent & Ride made my vacation hassle-free. The process was smooth,
              and the car was in excellent condition!"
            </p>
            <strong>-Niroshan</strong>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="p-3 border rounded bg-light">
            <p>
              "Great service and affordable rates. Highly recommend them for
              anyone traveling across Sri Lanka."
            </p>
            <strong>- Sulochana</strong>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="p-3 border rounded bg-light">
            <p>
              "Loved the easy booking system! They had a wide variety of
              vehicles to choose from. Very reliable."
            </p>
            <strong>- Madusanka</strong>
          </div>
        </div>
      </div>
    </section>

    <footer class="bg-dark text-white text-center py-4">
      <div>
        <a
          href="https://www.facebook.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-facebook fa-2x"></i>
        </a>
        <a
          href="https://www.twitter.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-twitter fa-2x"></i>
        </a>
        <a
          href="https://www.instagram.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-instagram fa-2x"></i>
        </a>
        <a
          href="https://www.linkedin.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-linkedin fa-2x"></i>
        </a>
      </div>
      <p>&copy; 2024 Rent & Ride. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
