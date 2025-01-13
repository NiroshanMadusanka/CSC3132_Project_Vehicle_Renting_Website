<?php
// Start the session
session_start();
require_once 'connectDB.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$useremail = $_SESSION['user_email'];

// Fetch vehicle details
$vehicle_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$vehicle_id) {
    echo "<div class='alert alert-danger text-center'>Vehicle ID not provided.</div>";
    exit();
}

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
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .booking-container {
            max-width: 800px;
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
    </style>
</head>
<body>
<?php
    require_once 'navbar.php';
    ?>
<div class="booking-container">
    <h2 class="text-center mb-4">Book Your Vehicle</h2>

    <!-- Vehicle Details Card -->
    <div class="card mb-4">
        <img src="uploads/<?php echo htmlspecialchars($vehicle['image']); ?>" alt="Vehicle Image" class="vehicle-image card-img-top">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($vehicle['model']); ?></h5>
            <p><strong>Owner:</strong> <?php echo htmlspecialchars($vehicle['owner']); ?></p>
            <p><strong>Owner Email:</strong> <?php echo htmlspecialchars($vehicle['owner_email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($vehicle['owner_phone']); ?></p>
            <p><strong>Price:</strong> Rs. <?php echo number_format($vehicle['price'], 2); ?></p>
        </div>
    </div>

    <form id="bookingForm" action="confirm_booking.php" method="POST">
        <!-- Hidden vehicle ID -->
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">

        <!-- User Info -->
        <div class="mb-3">
            <label for="username" class="form-label">User Name</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="useremail" class="form-label">User Email</label>
            <input type="email" id="useremail" name="useremail" class="form-control" value="<?php echo htmlspecialchars($useremail); ?>" readonly>
        </div>

        <!-- Booking Details -->
        <div class="mb-3">
            <label for="pickup_date" class="form-label">Pickup Date</label>
            <input type="date" id="pickup_date" name="pickup_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="drop_date" class="form-label">Drop Date</label>
            <input type="date" id="drop_date" name="drop_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="pickup_location" class="form-label">Pickup Location</label>
            <select id="pickup_location" name="pickup_location" class="form-select" required>
                <option value="" disabled selected>Select Pickup Location</option>
                <option value="Colombo">Colombo</option>
                <option value="Gampaha">Gampaha</option>
                <option value="Kandy">Kandy</option>
                <!-- Add all districts -->
            </select>
        </div>
        <div class="mb-3">
            <label for="drop_location" class="form-label">Drop Location</label>
            <select id="drop_location" name="drop_location" class="form-select" required>
                <option value="" disabled selected>Select Drop Location</option>
                <option value="Colombo">Colombo</option>
                <option value="Gampaha">Gampaha</option>
                <option value="Kandy">Kandy</option>
                <!-- Add all districts -->
            </select>
        </div>

        <!-- Contact Info -->
        <div class="mb-3">
            <label for="phone1" class="form-label">Phone Number 1</label>
            <input type="text" id="phone1" name="phone1" class="form-control" placeholder="Enter primary phone number" required>
        </div>
        <div class="mb-3">
            <label for="phone2" class="form-label">Phone Number 2</label>
            <input type="text" id="phone2" name="phone2" class="form-control" placeholder="Enter secondary phone number" required>
        </div>

        <!-- Driver Option -->
        <div class="mb-3">
            <label class="form-label">Do you need a driver?</label><br>
            <div class="form-check form-check-inline">
                <input type="radio" id="driver_yes" name="need_driver" value="yes" class="form-check-input" required>
                <label for="driver_yes" class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="driver_no" name="need_driver" value="no" class="form-check-input" required>
                <label for="driver_no" class="form-check-label">No</label>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" id="agree_terms" class="form-check-input" required>
                <label for="agree_terms" class="form-check-label">I agree to the terms and conditions</label>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-custom" onclick="submitBookingForm()">Next</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>

<script>
    // Minimum date logic
    const pickupDate = document.getElementById('pickup_date');
    const dropDate = document.getElementById('drop_date');
    const today = new Date();
    today.setDate(today.getDate() + 2);
    const minPickupDate = today.toISOString().split('T')[0];
    pickupDate.min = minPickupDate;

    pickupDate.addEventListener('change', function () {
        const selectedDate = new Date(this.value);
        selectedDate.setDate(selectedDate.getDate() + 1);
        dropDate.min = selectedDate.toISOString().split('T')[0];
    });

    // Submit form using JavaScript to redirect to confirm_booking.php
    function submitBookingForm() {
        document.getElementById('bookingForm').submit();
    }
</script>

</body>
</html>

