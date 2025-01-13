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
      .btn-theme {
        background-color:rgb(0, 0, 0);
        color: white;
        border-radius: 20px;
      }

      .btn-theme:hover {
        background-color:rgb(255, 255, 255);
      }
      section.container {
        margin-top: 80px; 
        margin-bottom: 30px;
      }
      .blur-effect {
        background: rgba(255, 255, 255, 0.2); 
        backdrop-filter: blur(10px); 
        padding: 40px;
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
      }
    </style>
  </head>

  <body>
  <?php
    require_once 'navbar.php';
    ?>
    <section class="container mt-5">
      <h2 class="text-center blur-effect mb-4">Tell us what wheels you’re after—we’ve got you covered!</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/car.jpg" class="card-img-top" alt="Car" />
            <div class="card-body">
              <h5 class="card-title">Car</h5>
              <p class="card-text">
                Comfortable and reliable cars for your journey.
              </p>
              <a href="listvehicle.php?category=car" class="btn btn-theme">See Available Cars</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="img/van.jpg" class="card-img-top" alt="Van" />
            <div class="card-body">
              <h5 class="card-title">Van</h5>
              <p class="card-text">Perfect for family trips or group travel.</p>
              <a href="listvehicle.php?category=van" class="btn btn-theme">See Available Vans</a>
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
              <a href="listvehicle.php?category=scooter" class="btn btn-theme">See Available Scooters</a>
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
              <a href="listvehicle.php?category=motorbike" class="btn btn-theme">See Available Motorbikes</a>
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
                <a href="listvehicle.php?category=threewheel" class="btn btn-theme">See Available Three-Wheels</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="text-center blur-effect mb-4">
      <h2 class="text-center mb-4">Want to Add Your Vehicle?</h2>
      <p class="text-center">
          Trust Rent & Ride to help you rent out your vehicle to people who need it. Whether it's a car, van, scooter, or any other type of vehicle, our platform makes it easy to list your vehicle and start earning. We ensure a safe and secure experience for both vehicle owners and renters, making it a win-win situation for everyone.
      </p>
      <p class="text-center mb-4" >
        Simply fill out the form on the next page to add your vehicle. It’s easy and free! Get started today and help others find the perfect ride.
      </p>
      <div class="text-center" >
          <a href="addvehicle.php" class="btn btn-theme btn-lg">Add Your Vehicle</a>
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
