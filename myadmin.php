<?php
session_start();
include 'connectDB.php';


$total_users_result = $conn->query("SELECT COUNT(*) AS count FROM users");
$total_users_row = $total_users_result->fetch_assoc();
$total_users = $total_users_row['count'] ?? 0;


$total_vehicles_result = $conn->query("SELECT COUNT(*) AS count FROM vehicles WHERE approved = 1");
$total_vehicles_row = $total_vehicles_result->fetch_assoc();
$total_vehicles = $total_vehicles_row['count'] ?? 0;


$pending_requests_result = $conn->query("SELECT COUNT(*) AS count FROM vehicles WHERE approved = 0");
$pending_requests_row = $pending_requests_result->fetch_assoc();
$pending_requests = $pending_requests_row['count'] ?? 0;

$pending_booking_result = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status = 'Pending'");
$pending_booking_row = $pending_booking_result->fetch_assoc();
$pending_booking = $pending_booking_row['count'] ?? 0;

$contact_submissions_result = $conn->query("SELECT COUNT(*) AS count FROM contact_submissions WHERE reviewed = 0 ");
$contact_submissions_row = $contact_submissions_result->fetch_assoc();
$contact_submissions = $contact_submissions_row['count'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rent & Ride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url("img/admin_back.jpg") no-repeat center center fixed;
            background-size: cover;
            padding-top: 70px;
        }
        footer {
          position: absolute;
          bottom: 0;
          width: 100%;
          background-color: #343a40;
          color: white;
          text-align: center;
        padding: 1rem 0;
        }
    </style>
</head>
<body>
<?php
    require_once 'navbar.php';
    ?>

<div class="container mt-5 mb-5">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $total_users; ?></p>
                    <a href="manage_users.php" class="btn btn-sm btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Vehicles</h5>
                    <p class="card-text"><?php echo $total_vehicles; ?></p>
                    <a href="manage_vehicles.php" class="btn btn-sm btn-success">Manage Vehicles</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pending Vehicle Requests</h5>
                    <p class="card-text"><?php echo $pending_requests; ?></p>
                    <a href="approve_vehicles.php" class="btn btn-sm btn-warning">Approve Vehicles</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pending Booking Requests</h5>
                    <p class="card-text"><?php echo $pending_booking; ?></p>
                    <a href="manage_bookings.php" class="btn btn-sm btn-success">Manage Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">User Messages</h5>
                    <p class="card-text"><?php echo $contact_submissions; ?></p>
                    <a href="contact_submissions.php" class="btn btn-sm btn-warning">Review</a>
                </div>
            </div>
        </div>
        
    </div>
</div>


<footer class="bg-dark text-white text-center py-4 mt-4">
    <div>
        <a href="https://www.facebook.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-facebook fa-2x"></i>
        </a>
        <a href="https://www.twitter.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-twitter fa-2x"></i>
        </a>
        <a href="https://www.instagram.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-instagram fa-2x"></i>
        </a>
        <a href="https://www.linkedin.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-linkedin fa-2x"></i>
        </a>
    </div>
    <p>&copy; 2024 Rent & Ride. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
