<?php
ob_start(); // Start output buffering

// Start the session
session_start();
require_once 'connectDB.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['useremail'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted (step 1, passing booking details)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['confirm'])) {
        // If confirm is pressed, insert into database
        $vehicle_id = $_SESSION['booking_details']['vehicle_id'];
        $pickup_date = $_SESSION['booking_details']['pickup_date'];
        $drop_date = $_SESSION['booking_details']['drop_date'];
        $pickup_location = $_SESSION['booking_details']['pickup_location'];
        $drop_location = $_SESSION['booking_details']['drop_location'];
        $phone1 = $_SESSION['booking_details']['phone1'];
        $phone2 = $_SESSION['booking_details']['phone2'];
        $need_driver = $_SESSION['booking_details']['need_driver'];
        $total_fee = $_SESSION['booking_details']['total_fee'];
        $payment_method = $_POST['payment_method'];
        $reference_number = ($payment_method === 'bank') ? $_POST['reference_number'] : null;
    
        // Insert the booking into the database
        $sql = "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, pickup_location, drop_location, primary_phone, secondary_phone, driver_option, total_fee, payment_method, reference_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssssdss", $_SESSION['user_id'], $vehicle_id, $pickup_date, $drop_date, $pickup_location, $drop_location, $phone1, $phone2, $need_driver, $total_fee, $payment_method, $reference_number);
        
        if ($stmt->execute()) {
            // Booking was successfully added
            echo "<script>
                    alert('Your booking has been successfully confirmed!');
                    window.location.href = 'vehicles.php';
                  </script>";
        } else {
            // If the booking failed
            echo "<script>
                    alert('There was an error processing your booking. Please try again.');
                  </script>";
        }
    
        $stmt->close();
        $conn->close();
        unset($_SESSION['booking_details']);
        exit();
    }

    
 //   if (isset($_POST['NEXT'])) {
        // Fetch booking details from POST
        $vehicle_id = $_POST['vehicle_id'];
        $username = $_POST['username'];
        $useremail = $_POST['useremail'];
        $pickup_date = $_POST['pickup_date'];
        $drop_date = $_POST['drop_date'];
        $pickup_location = $_POST['pickup_location'];
        $drop_location = $_POST['drop_location'];
        $phone1 = $_POST['phone1'];
        $phone2 = $_POST['phone2'];
        $need_driver = $_POST['need_driver'];
        $agree_terms = isset($_POST['agree_terms']) ? true : false; // Handle checkbox

        // Fetch vehicle details from DB
        $sql = "SELECT 
                    v.model, 
                    v.price, 
                    v.image, 
                    u.username AS owner, 
                    u.email AS owner_email, 
                    v.phone1 AS owner_phone 
                FROM vehicles v 
                INNER JOIN users u ON v.owner_id = u.id 
                WHERE v.vehicle_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            echo "<div class='alert alert-danger text-center'>Vehicle not found.</div>";
            exit();
        }

        $vehicle = $result->fetch_assoc();

        // Calculate the rental fee
        $pickup = new DateTime($pickup_date);
        $drop = new DateTime($drop_date);
        $interval = $pickup->diff($drop);
        $days = $interval->days;

        $total_fee = $vehicle['price'] * $days;
        $service_charge = $total_fee * 0.02; // 2% service charge

        if ($need_driver === 'yes') {
            $driver_fee = 2000 * $days;
            $driver_service_charge = $driver_fee * 0.01; // 1% service charge for driver
            $total_fee += $driver_fee + $driver_service_charge;
        } else {
            $insurance_warning = "Warning: You need to provide your NIC or Passport for insurance.";
        }

        // Store the booking details in the session for confirmation
        $_SESSION['booking_details'] = [
            'vehicle_id' => $vehicle_id,
            'username' => $username,
            'useremail' => $useremail,
            'pickup_date' => $pickup_date,
            'drop_date' => $drop_date,
            'pickup_location' => $pickup_location,
            'drop_location' => $drop_location,
            'phone1' => $phone1,
            'phone2' => $phone2,
            'need_driver' => $need_driver,
            'total_fee' => $total_fee,
            'service_charge' => $service_charge,
            'driver_fee' => isset($driver_fee) ? $driver_fee : null,
            'driver_service_charge' => isset($driver_service_charge) ? $driver_service_charge : null,
            'insurance_warning' => isset($insurance_warning) ? $insurance_warning : null
        ];
  //  }
}



ob_end_flush(); // Flush the output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .confirmation-container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #00cdfe;
            color: white;
        }
        .btn-custom:hover {
            background-color: #00b4e5;
        }
        .vehicle-image {
            max-width: 100%;
            border-radius: 10px;
        }
        .summary-section {
            background-color: #f1f3f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-control, .btn {
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <h2 class="text-center mb-4">Confirm Your Booking</h2>

    <!-- Vehicle Details -->
    <div class="card mb-4">
        <img src="uploads/<?php echo htmlspecialchars($vehicle['image']); ?>" alt="Vehicle Image" class="vehicle-image card-img-top">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($vehicle['model']); ?></h5>
            <p><strong>Owner:</strong> <?php echo htmlspecialchars($vehicle['owner']); ?></p>
            <p><strong>Owner Email:</strong> <?php echo htmlspecialchars($vehicle['owner_email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($vehicle['owner_phone']); ?></p>
            <p><strong>Price:</strong> Rs. <?php echo number_format($vehicle['price'], 2); ?> per day</p>
        </div>
    </div>

    <!-- Booking Summary -->
    <div class="summary-section">
        <h5>Booking Details</h5>
        <p><strong>Pickup Date:</strong> <?php echo $_SESSION['booking_details']['pickup_date']; ?></p>
        <p><strong>Drop Date:</strong> <?php echo $_SESSION['booking_details']['drop_date']; ?></p>
        <p><strong>Total Days:</strong> <?php echo htmlspecialchars($days); ?> days</p>
        <p><strong>Total Fee:</strong> Rs. <?php echo number_format($_SESSION['booking_details']['total_fee'], 2); ?></p>

        <?php if (isset($_SESSION['booking_details']['insurance_warning'])) { echo "<div class='alert alert-warning'>{$_SESSION['booking_details']['insurance_warning']}</div>"; } ?>
    </div>

    <!-- Payment Method -->
    <h5>Payment Method</h5>
    <form action="confirm_booking.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Select Payment Method</label><br>
            <input type="radio" id="bank" name="payment_method" value="bank" required> Bank Transfer
            <input type="radio" id="card" name="payment_method" value="card"> Card (under development)<br><br>
        </div>
        
        <div id="bankDetails" class="mb-3" style="display: none;">
            <label for="reference_number" class="form-label">Bank Deposit Reference Number</label>
            <input type="text" id="reference_number" name="reference_number" class="form-control" placeholder="Enter reference number">
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" name="confirm" class="btn btn-custom">Confirm</button>
            <a href="booking.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Show bank reference input when bank transfer is selected
    document.getElementById('bank').addEventListener('change', function() {
        document.getElementById('bankDetails').style.display = 'block';
    });
    document.getElementById('card').addEventListener('change', function() {
        document.getElementById('bankDetails').style.display = 'none';
    });
</script>

</body>
</html>
