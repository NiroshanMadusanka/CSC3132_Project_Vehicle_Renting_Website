<?php
session_start();
require_once 'connectDB.php';

// Initialize variables
$total_fee = 0;
$vehicle_id = $_GET['id'] ?? null;
$vehicle = null;

// Fetch vehicle details from the database
if ($vehicle_id) {
    $sql = "SELECT v.*, u.username AS owner_name, u.email AS owner_email FROM vehicles v JOIN users u ON v.owner_id = u.id WHERE v.vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    } else {
        echo "Vehicle not found.";
        exit();
    }
} else {
    echo "Invalid access.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $pickup_location = $_POST['pickup_location'] ?? null;
    $drop_location = $_POST['drop_location'] ?? null;
    $primary_phone = $_POST['primary_phone'] ?? null;
    $secondary_phone = $_POST['secondary_phone'] ?? null;
    $driver_option = $_POST['driver_option'] ?? null;
    $reference_number=$_POST['reference_number']??null;
    

    if (isset($_POST['calculate_fee'])) {
        if ($start_date && $end_date && $driver_option) {
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            $days_rented = $date2->diff($date1)->days + 1; // Include last day
            $total_fee = $vehicle['price'] * $days_rented + ($vehicle['price'] * $days_rented * 0.02); // +2% service charge
            if ($driver_option == 'Yes') {
                $total_fee += 2000 * $days_rented + ($total_fee * 0.01); // Rs. 2000/day and 1% service charge
            }
        }
    } 
    elseif (isset($_POST['confirm_booking'])) {
        if ($start_date && $end_date && $driver_option) {
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            $days_rented = $date2->diff($date1)->days + 1; // Include last day
            $total_fee = $vehicle['price'] * $days_rented + ($vehicle['price'] * $days_rented * 0.02); // +2% service charge
            if ($driver_option == 'Yes') {
                $total_fee += 2000 * $days_rented + ($total_fee * 0.01); // Rs. 2000/day and 1% service charge
            }
        }
        if ($start_date && $end_date && $pickup_location && $drop_location && $primary_phone && $secondary_phone) {
            $sql = "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, pickup_location, drop_location, primary_phone, secondary_phone, driver_option, total_fee, status, payment_method, reference_number) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending','Bank', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisssssssss", $_SESSION['user_id'], $vehicle_id, $start_date, $end_date, $pickup_location, $drop_location, $primary_phone, $secondary_phone, $driver_option, $total_fee,$reference_number);
            if ($stmt->execute()) {
                echo "<script>alert('Booking request submitted successfully!'); window.location.href = 'index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error processing booking request.');</script>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Vehicle - RENT & RIDE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .booking-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .booking-header {
            color: #00cdfe;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #00cdfe;
            border: none;
        }
        .btn-primary:hover {
            background-color: #009ec3;
        }
        .form-control:focus {
            border-color: #00cdfe;
            box-shadow: 0px 0px 5px rgba(0, 205, 254, 0.5);
        }
        .container {
            margin-top: 50px;
        }
        .vehicle-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        #driver-warning {
            display: none;
            margin-top: 15px;
            color: red;
            font-size: 1.1rem;
        }
        .terms-checkbox {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="booking-form">
                    <h2 class="booking-header text-center mb-4">Book Your Vehicle</h2>

                    <!-- Vehicle Details Card -->
                    <div class="card vehicle-card mb-4">
                        <img src="uploads/<?php echo $vehicle['image']; ?>" class="card-img-top" alt="<?php echo $vehicle['model']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $vehicle['model']; ?></h5>
                            <p class="card-text">Owner: <?php echo $vehicle['owner_name']; ?></p>
                            <p class="card-text">Email: <?php echo $vehicle['owner_email']; ?></p>
                            <p class="card-text">Phone: <?php echo $vehicle['phone1']; ?></p>
                        </div>
                    </div>

                    <!-- User Details Form -->
                    <form action="bookvehicle.php?id=<?php echo $vehicle_id; ?>" method="POST">
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">

                        <div class="form-group">
                            <label for="user_name">Your Name:</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="user_email">Your Email:</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $_SESSION['user_email']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="primary_phone">Primary Phone:</label>
                            <input type="text" class="form-control" id="primary_phone" name="primary_phone" required value="<?php echo isset($_POST['primary_phone']) ? $_POST['primary_phone'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="text" class="form-control" id="secondary_phone" name="secondary_phone" required value="<?php echo isset($_POST['secondary_phone']) ? $_POST['secondary_phone'] : ''; ?>">
                        </div>

                        <!-- Rental Details -->
                        <div class="form-group">
                            <label for="pickup_location">Pick-up Location:</label>
                            <select class="form-control" id="pickup_location" name="pickup_location" required>
                                <option value="Colombo" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
                                <option value="Kandy" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
                                <option value="Galle" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
                                <option value="Matara" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Pick-up Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="drop_location">Drop-off Location:</label>
                            <select class="form-control" id="drop_location" name="drop_location" required>
                                <option value="Colombo" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
                                <option value="Kandy" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
                                <option value="Galle" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
                                <option value="Matara" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Return Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="driver_option">Do you need a driver?</label><br>
                            <input type="radio" id="driver_yes" name="driver_option" value="Yes" <?php echo (isset($_POST['driver_option']) && $_POST['driver_option'] == 'Yes') ? 'checked' : ''; ?>> Yes
                            <input type="radio" id="driver_no" name="driver_option" value="No" <?php echo (isset($_POST['driver_option']) && $_POST['driver_option'] == 'No') ? 'checked' : ''; ?>> No
                        </div>

                        <div id="driver-warning">
                            <p><strong>Important:</strong> If you do not need a driver, you must provide your NIC or passport as insurance when taking the vehicle. This will be returned when the vehicle is handed over.</p>
                        </div>

                        <div class="terms-checkbox">
                            <label>
                                <input type="checkbox" name="terms_conditions" required <?php echo isset($_POST['terms_conditions']) ? 'checked' : ''; ?>> I agree to the <a href="#">terms and conditions</a>
                            </label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block" name="calculate_fee">Calculate Fee</button>
                        </div>
                    </form>

                    <!-- Fee Calculation -->
                    <div class="mt-4">
                        <p><strong>Calculated Fee:</strong> Rs. <?php echo number_format($total_fee, 2); ?></p>

                        <!-- Payment Method -->
                        <div class="form-group">
                            <label for="payment_method">Payment Method:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="bank">Bank Deposit</option>
                                <option value="card">Card Payment</option>
                            </select>
                        </div>

                        <div id="bank-details" style="display:none;">
                            <div class="form-group">
                                <label for="reference_number">Bank Reference Number:</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number" required>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Booking -->
                    <form action="bookvehicle.php?id=<?php echo $vehicle_id; ?>" method="POST">
    <!-- Hidden Fields for Vehicle and User Information -->
    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    
    <!-- All required fields for booking -->
    <div class="form-group">
        <label for="primary_phone">Primary Phone:</label>
        <input type="text" class="form-control" id="primary_phone" name="primary_phone" required value="<?php echo isset($_POST['primary_phone']) ? $_POST['primary_phone'] : ''; ?>">
    </div>

    <div class="form-group">
        <label for="secondary_phone">Secondary Phone:</label>
        <input type="text" class="form-control" id="secondary_phone" name="secondary_phone" required value="<?php echo isset($_POST['secondary_phone']) ? $_POST['secondary_phone'] : ''; ?>">
    </div>

    <!-- Rental Details -->
    <div class="form-group">
        <label for="pickup_location">Pick-up Location:</label>
        <select class="form-control" id="pickup_location" name="pickup_location" required>
            <option value="Colombo" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
            <option value="Kandy" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
            <option value="Galle" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
            <option value="Matara" <?php echo (isset($_POST['pickup_location']) && $_POST['pickup_location'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
        </select>
    </div>

    <div class="form-group">
        <label for="start_date">Pick-up Date:</label>
        <input type="date" class="form-control" id="start_date" name="start_date" required min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
    </div>

    <div class="form-group">
        <label for="drop_location">Drop-off Location:</label>
        <select class="form-control" id="drop_location" name="drop_location" required>
            <option value="Colombo" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
            <option value="Kandy" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
            <option value="Galle" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
            <option value="Matara" <?php echo (isset($_POST['drop_location']) && $_POST['drop_location'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
        </select>
    </div>

    <div class="form-group">
        <label for="end_date">Return Date:</label>
        <input type="date" class="form-control" id="end_date" name="end_date" required value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
    </div>

    <div class="form-group">
        <label for="driver_option">Do you need a driver?</label><br>
        <input type="radio" id="driver_yes" name="driver_option" value="Yes" <?php echo (isset($_POST['driver_option']) && $_POST['driver_option'] == 'Yes') ? 'checked' : ''; ?>> Yes
        <input type="radio" id="driver_no" name="driver_option" value="No" <?php echo (isset($_POST['driver_option']) && $_POST['driver_option'] == 'No') ? 'checked' : ''; ?>> No
    </div>

    <div id="driver-warning">
        <p><strong>Important:</strong> If you do not need a driver, you must provide your NIC or passport as insurance when taking the vehicle. This will be returned when the vehicle is handed over.</p>
    </div>

    <!-- Confirm Booking Button -->
    <button type="submit" class="btn btn-success btn-block" name="confirm_booking">Confirm Booking</button>
    <a href="listvehicle.php" class="btn btn-danger btn-block">Cancel</a>
</form>

                </div>
            </div>
        </div>
    </div>
    <script>
    // Toggle driver warning and payment details visibility
    document.addEventListener('DOMContentLoaded', function() {
        // Driver option warning
        const driverYes = document.getElementById('driver_yes');
        const driverNo = document.getElementById('driver_no');
        const driverWarning = document.getElementById('driver-warning');

        driverYes.addEventListener('change', function() {
            driverWarning.style.display = 'none';  // Hide warning
        });

        driverNo.addEventListener('change', function() {
            driverWarning.style.display = 'block';  // Show warning
        });

        // Toggle payment method fields
        const paymentMethod = document.getElementById('payment_method');
        const bankDetails = document.getElementById('bank-details');

        paymentMethod.addEventListener('change', function() {
            if (paymentMethod.value === 'bank') {
                bankDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'none';
            }
        });

        // Trigger initial states
        if (driverNo.checked) driverWarning.style.display = 'block';
        if (paymentMethod.value === 'bank') bankDetails.style.display = 'block';
    });
</script>

</body>
</html>
