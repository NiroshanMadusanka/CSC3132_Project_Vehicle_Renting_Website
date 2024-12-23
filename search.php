<?php
require_once "connectDB.php";


$pickup_location = $_GET['pickup_location'];
$drop_location = $_GET['drop_location'];
$pickup_date = $_GET['pickup_date'];
$drop_date = $_GET['drop_date'];
$category = $_GET['category'];
$near_me = $_GET['near_me'];


$sql = "SELECT * FROM vehicles WHERE is_booked = 0 AND category = '$category' AND approved = 1";


if ($near_me == "yes") {
    $sql .= " AND location = '$pickup_location'";
}



$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
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
    <section class="container mt-5">
    <div class="container" align = "center">
        <h2 class="mt-5">Available Vehicles</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="row mt-4">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="Vehicle Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['model']; ?></h5>
                                <p class="card-text">Category: <?php echo $row['category']; ?></p>
                                <p class="card-text">Location: <?php echo $row['location']; ?></p>
                                <p class="card-text">Price: $<?php echo $row['price']; ?> per day</p>
                               
                                <a href="book.php?id=<?php echo $row['vehicle_id']; ?>" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No vehicles found matching your search criteria.</p>
        <?php endif; ?>

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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>