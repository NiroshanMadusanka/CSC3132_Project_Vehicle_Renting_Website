<?php
session_start();
include 'connectDB.php';
if (isset($_GET['message'])) {
  $message = urldecode($_GET['message']);
  echo "<script type='text/javascript'>
          alert('$message');
        </script>";
}
if (isset($_GET['delete'])) {
  $vehicle_id = $_GET['delete'];

  // Delete vehicle from the database
  $sql = "DELETE FROM vehicles WHERE vehicle_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $vehicle_id);

  if ($stmt->execute()) {
      // Success message
      $message = "Vehicle deleted successfully.";
      $alert_type = "success";  // Success alert
  } else {
      // Error message
      $message = "Error deleting vehicle: " . $conn->error;
      $alert_type = "error";  // Error alert
  }

  $stmt->close();
}

// Fetch vehicles from the database
$sql = "SELECT * FROM vehicles WHERE approved = 1";
$result = $conn->query($sql);

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
        background-size: cover;
        padding-top: 70px;
      }
      .card-img-top {
          width: 100%;  
          height: 200px; 
          object-fit: cover;  
          object-position: center; 
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

    <div class="container mt-5">
        <h2 class="text-center">Manage Vehicles</h2>
        
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vehicle_id = $row['vehicle_id'];
                    $model = $row['model'];
                    $price = $row['price'];
                    $color = $row['color'];
                    $description = $row['description'];
                    $status = $row['status'];
                    $phone1 = $row['phone1'];
                    $phone2 = $row['phone2'];
                    $image = $row['image'];
                    $category = $row['category'];
                    $is_booked = $row['is_booked'];
                    $location = $row['location'];
                    $no_booking = $row['no_booking'];
                    $created_at = $row['created_at'];

                    // Check if the vehicle hasn't been rented for at least one month
                    $date = new DateTime($created_at);
                    $now = new DateTime();
                    $interval = $date->diff($now);
                    $is_long_time = ($interval->m >= 1) ? true : false;
                    ?>

                    <!-- Vehicle Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="uploads/<?php echo $image; ?>" class="card-img-top" alt="Vehicle Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $model; ?></h5>
                                <p class="card-text"><strong>Price:</strong> $<?php echo $price; ?></p>
                                <p class="card-text"><strong>Color:</strong> <?php echo $color; ?></p>
                                <p class="card-text"><strong>Description:</strong> <?php echo $description ? $description : 'No description available'; ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo ucfirst($status); ?></p>
                                <p class="card-text"><strong>Phone 1:</strong> <?php echo $phone1; ?></p>
                                <p class="card-text"><strong>Phone 2:</strong> <?php echo $phone2 ? $phone2 : 'Not available'; ?></p>
                                <p class="card-text"><strong>Category:</strong> <?php echo $category; ?></p>
                                <p class="card-text"><strong>Location:</strong> <?php echo $location; ?></p>
                                <p class="card-text"><strong>Bookings:</strong> <?php echo $no_booking; ?></p>
                                <p class="card-text"><strong>Created At:</strong> <?php echo $created_at; ?></p>
                                <p class="card-text"><strong>Is Booked:</strong> <?php echo $is_booked ? 'Yes' : 'No'; ?></p>
                                
                                <?php if ($is_long_time): ?>
                                    <p class="text-warning">Not rented for a long time</p>
                                <?php endif; ?>
                                
                                <a href="editvehicle.php?vehicle_id=<?php echo $vehicle_id; ?>" class="btn btn-primary">Edit</a>
                                <a href="manage_vehicles.php?delete=<?php echo $vehicle_id; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<p>No vehicles found.</p>";
            }
            ?>
        </div>

        <div class="row mt-5">
            <h3>Vehicles Not Rented for Long</h3>
            <?php
            // Query for vehicles not rented for at least one month
            $sql_long_time = "SELECT * FROM vehicles WHERE DATEDIFF(CURRENT_DATE, created_at) >= 30";
            $result_long_time = $conn->query($sql_long_time);
            
            if ($result_long_time->num_rows > 0) {
                while ($row = $result_long_time->fetch_assoc()) {
                    $vehicle_id = $row['vehicle_id'];
                    $model = $row['model'];
                    $price = $row['price'];
                    $image = $row['image'];
                    ?>
                    <!-- Vehicle Card for Long Not Rented -->
                    <div class="col-md-4">
                        <div class="card">
                            <img src="uploads/<?php echo $image; ?>" class="card-img-top" alt="Vehicle Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $model; ?></h5>
                                <p class="card-text"><strong>Price:</strong> $<?php echo $price; ?></p>
                                <p class="text-warning">Not rented for at least one month</p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No vehicles have been rented for a long time.</p>";
            }
            ?>
        </div>

    </div>

    


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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>

<?php
$conn->close();
?>