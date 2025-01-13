<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'connectDB.php';


if (isset($_GET['approve'])) {
    $vehicle_id = $_GET['approve'];

    $sql = "UPDATE vehicles SET approved = 1 WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "<script>alert('Server error. Please contact support.'); window.location.href='approve_vehicles.php';</script>";
        exit;
    }

    $stmt->bind_param("i", $vehicle_id);
    if ($stmt->execute()) {
        echo "<script>alert('Vehicle approved successfully.'); window.location.href='approve_vehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to approve vehicle. Try again.'); window.location.href='approve_vehicles.php';</script>";
    }
    $stmt->close();
}


if (isset($_GET['decline'])) {
    $vehicle_id = $_GET['decline'];

    
    $sql = "DELETE FROM vehicles WHERE vehicle_id = ? AND approved = 0"; // Ensures only unapproved vehicles are deleted
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "<script>alert('Server error. Please contact support.'); window.location.href='approve_vehicles.php';</script>";
        exit;
    }

    $stmt->bind_param("i", $vehicle_id);
    if ($stmt->execute()) {
        echo "<script>alert('Vehicle declined and removed successfully.'); window.location.href='approve_vehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to remove vehicle. Try again.'); window.location.href='approve_vehicles.php';</script>";
    }
    $stmt->close();
}


$sql = "SELECT * FROM vehicles WHERE approved = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve or Decline Vehicles - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
         body {
            
            background-size: cover;
             padding-top: 70px;
            color: #333; /* Dark text for readability */
            }

       /* footer {
            position: absolute;
             bottom: 0;
             width: 100%;
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            }
*/
        .vehicle-card {
            background-color: #ffffff; 
            border: 1px solid #ddd;
            margin: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            }

        .vehicle-image {
          max-width: 100%;
         max-height: 200px;
         object-fit: cover;
         border-radius: 8px;
        }

        .vehicle-card-body {
             padding-top: 15px;
            }

        .vehicle-card h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #004085; 
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); 
        }

        .vehicle-card p {
            color: #333; 
        }

        .vehicle-card .btn-approve {
            background-color: #28a745;
            color: white;
            font-size: 1rem;
        }

        .vehicle-card .btn-approve:hover {
            background-color: #218838;
        }

        .vehicle-card .btn-decline {
            background-color: #dc3545;
            color: white;
            font-size: 1rem;
        }

        .vehicle-card .btn-decline:hover {
            background-color: #c82333;
        }

        h2 {
         color: black; 
         text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
        }
        </style>

</head>
<body>
<?php
    require_once 'navbar.php';
    ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Approve or Decline Vehicles</h2>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="vehicle-card">
                        <img class="vehicle-image" src="uploads/<?php echo $row['image']; ?>" alt="Vehicle Image">
                        <div class="vehicle-card-body">
                            <h5 class="vehicle-card-title"><?php echo $row['model']; ?> (<?php echo $row['year']; ?>)</h5>
                            <p><strong>Price:</strong> Rs. <?php echo $row['price']; ?></p>
                            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                            <p><strong>Owner:</strong> <?php echo $row['owner_id']; ?></p>
                            <p><strong>Phone:</strong> <?php echo $row['phone1']; ?></p>
                            <a href="approve_vehicles.php?approve=<?php echo $row['vehicle_id']; ?>" class="btn btn-approve">Approve</a>
                            <a href="approve_vehicles.php?decline=<?php echo $row['vehicle_id']; ?>" class="btn btn-decline">Decline</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No unapproved vehicles found.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
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
</html>

<?php
$conn->close();
?>
