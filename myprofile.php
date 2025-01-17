<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

// Database connection
require_once 'connectDB.php';

// Fetch user details
$userid = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $userid);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch user's vehicles
$vehicle_query = "SELECT * FROM vehicles WHERE owner_id = ?";
$stmt = $conn->prepare($vehicle_query);
$stmt->bind_param("i", $user['id']); 
$stmt->execute();
$vehicle_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    
    .profile-container {
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin-top: 100px;
    }

    .btn-primary, .btn-warning, .btn-danger {
      border-radius: 20px;
    }

    table {
    table-layout: fixed;
}
table th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 2;
}
table tbody tr:hover {
    background-color: #f1f1f1;
}

  </style>
</head>
<body>
<?php
    require_once 'navbar.php';
    ?>
  
    <section class="container my-5">
 
    <div class="profile-container">
      <h1 class="mb-4" align='center'>My Profile</h1>

      <!-- User Details Form -->
      <form action="update_profile.php" method="POST">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly />
          </div>
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required />
          </div>
        </div>
        
          
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>

      <!-- User's Booking Table -->
      <h2 class="mt-5">My Bookings</h2>
<table class="table table-striped table-bordered mt-3">
  <thead>
    <tr>
      <th>No</th>
      <th>Model</th>
      <th>Category</th>
      <th>Pickup Date</th>
      <th>Drop Date</th>
      <th>Pickup Location</th>
      <th>Drop Location</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $booking_query = "SELECT b.booking_id, v.model, v.category, b.start_date, b.end_date, 
                      b.pickup_location, b.drop_location, b.status 
                      FROM bookings b
                      JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                      WHERE b.user_id = ?";
    $stmt = $conn->prepare($booking_query);
    $stmt->bind_param("i", $user['id']); 
    $stmt->execute();
    $booking_result = $stmt->get_result();

    if ($booking_result->num_rows > 0) {
        $count = 1;
        while ($booking = $booking_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $count++ . "</td>";
            echo "<td>" . htmlspecialchars($booking['model']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['category']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['start_date']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['end_date']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['pickup_location']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['drop_location']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['status']) . "</td>";
            echo "<td>
                    <form method='POST' action='delete_booking.php' onsubmit='return confirm(\"Are you sure you want to delete this booking?\");'>
                      <input type='hidden' name='booking_id' value='" . htmlspecialchars($booking['booking_id']) . "'>
                      <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No bookings found.</td></tr>";
    }
    ?>
  </tbody>
</table>





      <!-- User's Vehicles Table -->
      <h2 class="mt-5">Vehicles Added</h2>
      <table class="table table-striped table-bordered mt-3">
        <thead>
          <tr>
            <th>#</th>
            <th>Model</th>
            <th>Category</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($vehicle_result->num_rows > 0) {
              $count = 1;
              while ($vehicle = $vehicle_result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $count++ . "</td>";
                  echo "<td>" . htmlspecialchars($vehicle['model']) . "</td>";
                  echo "<td>" . htmlspecialchars($vehicle['category']) . "</td>";
                  echo "<td>" . htmlspecialchars($vehicle['description']) . "</td>";
                  echo "<td>
                          <a href='edit_vehicle.php?id=" . $vehicle['vehicle_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                          <a href='delete_vehicle.php?id=" . $vehicle['vehicle_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='5'>No vehicles added yet.</td></tr>";
          }
          ?>
        </tbody>
      </table>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
