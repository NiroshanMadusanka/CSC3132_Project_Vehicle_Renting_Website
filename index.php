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
        background: url("img/background_Home.jpg") no-repeat center center fixed;
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

    <header class="hero d-flex align-items-center">
    <div class="container text-center text-white">
  <h1 class="mb-4">Find Your Perfect Vehicle</h1>
  <form class="search-bar row g-2 justify-content-center" method="GET" action="search.php">
    <!-- Row for Pick-up and Drop Sections -->
    <div class="row w-100 mb-4 justify-content-center">
      <!-- Left Column for Pick-up -->
      <div class="col-md-5">
        <!-- Pick-up Location -->
        <div class="mb-3">
          <label for="pickup_location">Pick-up Location</label>
          <select class="form-control" name="pickup_location" id="pickup_location" required>
            <option value="" disabled selected>Select Pick-up Location</option>
            <option value="Colombo">Colombo</option>
            <option value="Kandy">Kandy</option>
            <option value="Galle">Galle</option>
            <option value="Jaffna">Jaffna</option>
            <!-- Add other districts as needed -->
          </select>
        </div>
        
        <!-- Pick-up Date -->
        <div class="mb-3">
          <label for="pickup_date">Pick-up Date</label>
          <input
            type="date"
            class="form-control"
            name="pickup_date"
            id="pickup_date"
            required
          />
        </div>
      </div>

      <!-- Right Column for Drop -->
      <div class="col-md-5">
        <!-- Drop Location -->
        <div class="mb-3">
          <label for="drop_location">Drop Location</label>
          <select class="form-control" name="drop_location" id="drop_location" required>
            <option value="" disabled selected>Select Drop Location</option>
            <option value="Colombo">Colombo</option>
            <option value="Kandy">Kandy</option>
            <option value="Galle">Galle</option>
            <option value="Jaffna">Jaffna</option>
            <!-- Add other districts as needed -->
          </select>
        </div>

        <!-- Drop Date -->
        <div class="mb-3">
          <label for="drop_date">Drop Date</label>
          <input
            type="date"
            class="form-control"
            name="drop_date"
            id="drop_date"
            required
          />
        </div>
      </div>
    </div>

    <!-- Find Vehicles Near Me Option -->
    <div class="row mb-3 justify-content-center">
      <div class="col-md-12 text-center">
        <label>Find Vehicles Near Me</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="near_me" value="yes" required>
          <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="near_me" value="no" required>
          <label class="form-check-label">No</label>
        </div>
      </div>
    </div>

    <!-- Vehicle Category -->
    <div class="row mb-3 justify-content-center">
      <div class="col-md-6">
        <label for="category">Vehicle Category</label>
        <select class="form-control" name="category" id="category" required>
          <option value="" disabled selected>Select Vehicle Category</option>
          <option value="Car">Car</option>
          <option value="Van">Van</option>
          <option value="Three-Wheel">Three-Wheel</option>
          <option value="Motorbike">Motorbike</option>
          <option value="Scooter">Scooter</option>
        </select>
      </div>
    </div>

    <!-- Centered Search Button -->
    <div class="row justify-content-center">
      <div class="col-md-12">
        <button class="btn btn-primary w-25 mx-auto" type="submit">Search</button>
      </div>
    </div>
  </form>
</div>





    </header>

    <section class="container text-center mt-5">
      <h2 class="mb-4">Why Choose Us?</h2>
      <div class="row">
        <div class="col-md-4">
          <h4>Wide Selection</h4>
          <p>Choose from a variety of vehicles for every need.</p>
        </div>
        <div class="col-md-4">
          <h4>Easy Booking</h4>
          <p>Fast and hassle-free reservation process.</p>
        </div>
        <div class="col-md-4">
          <h4>Affordable Rates</h4>
          <p>Enjoy competitive pricing for all rentals.</p>
        </div>
      </div>
    </section>

    <section class="container mt-5">
      <h2 class="text-center mb-4">Our Vehicles</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/car.jpg" class="card-img-top" alt="Car" />
            <div class="card-body">
              <h5 class="card-title">Car</h5>
              <p class="card-text">
                Comfortable and reliable cars for your journey.
              </p>
              <a href="#" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/van.jpg" class="card-img-top" alt="Van" />
            <div class="card-body">
              <h5 class="card-title">Van</h5>
              <p class="card-text">Perfect for family trips or group travel.</p>
              <a href="#" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/scooter.jpg" class="card-img-top" alt="Scooter" />
            <div class="card-body">
              <h5 class="card-title">Scooter</h5>
              <p class="card-text">
                Great for quick and easy transport around the city.
              </p>
              <a href="#" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <img
              src="img/motor_bikes.jpg"
              class="card-img-top"
              alt="Motorbike"
            />
            <div class="card-body">
              <h5 class="card-title">Motorbike</h5>
              <p class="card-text">Fast and fun way to get around.</p>
              <a href="#" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="card">
            <img
              src="img/threewheel.jpg"
              class="card-img-top"
              alt="Three-Wheel"
            />
            <div class="card-body">
              <h5 class="card-title">Three-Wheel</h5>
              <p class="card-text">
                Enjoy a unique and fun ride with our three-wheeled vehicles.
              </p>
              <a href="#" class="btn btn-primary">Book Now</a>
            </div>
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
