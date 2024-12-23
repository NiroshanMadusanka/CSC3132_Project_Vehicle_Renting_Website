<?php
session_start();
include 'connectDB.php';

// Get vehicle ID and fetch details from the database
if (isset($_GET['id'])) {
    $vehicle_id = $_GET['id'];
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
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];
    $primary_phone = $_POST['primary_phone'];
    $secondary_phone = $_POST['secondary_phone'];

    $driver_option = $_POST['driver_option']; // Driver option (Yes/No)
    $terms_conditions = isset($_POST['terms_conditions']) ? 1 : 0;

    // Calculate rental fee
    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);
    $days_rented = $date2->diff($date1)->days + 1; // Include last day
    $total_fee=0;
    $total_fee = $vehicle['price'] * $days_rented + ($vehicle['price'] * $days_rented * 0.02); // +2% service charge
    if ($driver_option == 'No') {
        $total_fee += 2000 * $days_rented + ($total_fee * 0.01); // Adding Rs. 2000 per day and 1% service charge for driver option
    }

    // Check if booking is made at least 2 days before the start date
    $current_date = new DateTime();
    $start_date_obj = new DateTime($start_date);
    $interval = $current_date->diff($start_date_obj);
    if ($interval->days < 2) {
        echo "<script>alert('You must book at least 2 days in advance.');</script>";
    } else {
        // Insert booking into the database
        $sql = "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, pickup_location, drop_location, primary_phone, secondary_phone, driver_option, total_fee, status, terms_conditions) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssssdsd", $_SESSION['user_id'], $vehicle_id, $start_date, $end_date, $pickup_location, $drop_location, $primary_phone, $secondary_phone, $driver_option, $total_fee, $terms_conditions);

        if ($stmt->execute()) {
            echo "<script>alert('Booking request submitted successfully!');</script>";
            header("Location: managebookings.php");
            exit();
        } else {
            echo "<script>alert('Error processing booking request.');</script>";
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
            <div class="col-md-6">
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

                    <!-- Booking Form -->
                    <form action="book_vehicle.php?id=<?php echo $vehicle_id; ?>" method="POST">
                        <!-- Hidden field for vehicle ID -->
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">

                        <div class="form-group">
                            <label for="start_date">Pick-up Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                        </div>

                        <div class="form-group">
                            <label for="end_date">Return Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>

                        <div class="form-group">
                            <label for="user_name">Your Name:</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="pickup_location">Pick-up Location:</label>
                            <select class="form-control" id="pickup_location" name="pickup_location" required>
                                <option value="Colombo">Colombo</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Galle">Galle</option>
                                <option value="Matara">Matara</option>
                                <!-- Add more districts as needed -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="drop_location">Drop-off Location:</label>
                            <select class="form-control" id="drop_location" name="drop_location" required>
                                <option value="Colombo">Colombo</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Galle">Galle</option>
                                <option value="Matara">Matara</option>
                                <!-- Add more districts as needed -->
                            </select>
                        </div>

                        <div class="form-group">
    <label for="primary_phone">Primary Phone:</label>
    <input type="text" class="form-control" id="primary_phone" name="primary_phone" required>
</div>

<div class="form-group">
    <label for="secondary_phone">Secondary Phone:</label>
    <input type="text" class="form-control" id="secondary_phone" name="secondary_phone" required>
</div>


                        <div class="form-group">
                            <label for="driver_option">Do you need a driver?</label><br>
                            <input type="radio" id="driver_yes" name="driver_option" value="Yes" required> Yes
                            <input type="radio" id="driver_no" name="driver_option" value="No"> No
                        </div>

                        <div id="driver-warning">
                            <p><strong>Important:</strong> If you do not need a driver, you must provide your NIC or passport as insurance when taking the vehicle. This will be returned when the vehicle is handed over.</p>
                        </div>

                        <div class="terms-checkbox">
                            <label>
                                <input type="checkbox" name="terms_conditions" required> I agree to the <a href="#">terms and conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">Confirm Booking</button>
<button type="button" class="btn btn-danger btn-block mt-2" onclick="window.history.back();">Cancel</button>

                    </form>

                    <div class="mt-4">
                        <!-- Fee Calculation -->
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
                                <input type="text" class="form-control" id="reference_number" name="reference_number">
                            </div>

                            <div class="form-group">
                                <label for="bank_slip">Bank Slip Photo:</label>
                                <input type="file" class="form-control" id="bank_slip" name="bank_slip">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="driver_option"]').forEach(function (elem) {
            elem.addEventListener('change', function () {
                if (document.getElementById('driver_no').checked) {
                    document.getElementById('driver-warning').style.display = 'block';
                } else {
                    document.getElementById('driver-warning').style.display = 'none';
                }
            });
        });

        document.getElementById('payment_method').addEventListener('change', function () {
            if (this.value === 'bank') {
                document.getElementById('bank-details').style.display = 'block';
            } else {
                document.getElementById('bank-details').style.display = 'none';
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
