<?php
// Start session and include database connection
session_start();
require 'connectDB.php';

// Check if the admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle actions (Confirm, Cancel)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $booking_id = intval($_POST['booking_id']);

    if ($action === 'confirm') {
        $sql = "UPDATE bookings SET status = 'Confirmed' WHERE booking_id = ? AND status = 'Pending'";
    } elseif ($action === 'cancel') {
        $sql = "UPDATE bookings SET status = 'Canceled' WHERE booking_id = ? AND status = 'Pending'";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
    }

    // Redirect back to the same page after action
    header("Location: manage_bookings.php");
    exit();
}

// Fetch bookings with user and vehicle details
$sql_pending = "SELECT b.*, u.username, u.email, v.model, v.image, v.color, v.year, v.price , b.total_fee,b.payment_method,v.vehicle_id,b.user_id,b.reference_number
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                WHERE b.status = 'Pending'";
$sql_confirmed = "SELECT b.*, u.username, u.email, v.model, v.image, v.color, v.year, v.price ,b.total_fee,b.payment_method,v.vehicle_id,b.user_id
                  FROM bookings b 
                  JOIN users u ON b.user_id = u.id 
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                  WHERE b.status = 'Confirmed'";
$sql_cancelled = "SELECT b.*, u.username, u.email, v.model, v.image, v.color, v.year, v.price ,b.total_fee,b.payment_method,v.vehicle_id,b.user_id
                  FROM bookings b 
                  JOIN users u ON b.user_id = u.id 
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                  WHERE b.status = 'Canceled'";
$result_pending = $conn->query($sql_pending);
$result_confirmed = $conn->query($sql_confirmed);
$result_cancelled = $conn->query($sql_cancelled);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .page-title {
            text-align: center;
            color: #00cdfe;
            margin-bottom: 20px;
        }
        .section {
            margin-top: 30px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .card img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .card-content {
            flex: 1;
        }
        .card h3 {
            margin: 0;
            color: #555;
            font-size: 16px;
        }
        .card p {
            margin: 5px 0;
            color: #777;
            font-size: 14px;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .confirm-btn {
            background-color: #28a745;
            color: #fff;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: #fff;
        }
        table td, table th {
        text-align: left;
    }

    </style>
</head>
<body>
<?php
    require_once 'navbar.php';
    ?>
    <div class="container">
        <h1 class="page-title">Manage Bookings</h1>

        <!-- Pending Bookings -->
        <section class="section">
            <h2>Pending Bookings</h2>
            <?php if ($result_pending->num_rows > 0): ?>
                <?php while ($row = $result_pending->fetch_assoc()): ?>
                    <div class="card">
                        <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Vehicle Image">
                        <div class="card-content">
                        <table class="table table-bordered">
    <tr>
        <th>Booking ID</th>
        <td><?= htmlspecialchars($row['booking_id']); ?></td>
    </tr>
    <tr>
        <th>User</th>
        <td><?= htmlspecialchars($row['username']); ?> (<?= htmlspecialchars($row['email']); ?>)</td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td><?= htmlspecialchars($row['model']); ?> (<?= htmlspecialchars($row['year']); ?>, <?= htmlspecialchars($row['color']); ?>)</td>
    </tr>
    <tr>
        <th>Price</th>
        <td>$<?= htmlspecialchars($row['price']); ?></td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td><?= htmlspecialchars($row['start_date']); ?></td>
    </tr>
    <tr>
        <th>Pickup</th>
        <td><?= htmlspecialchars($row['pickup_location']); ?></td>
    </tr>
    <tr>
        <th>Fee</th>
        <td>$<?= htmlspecialchars($row['total_fee']); ?></td>
    </tr>
    <tr>
        <th>Vehicle ID</th>
        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td><?= htmlspecialchars($row['payment_method']); ?></td>
    </tr>
    <tr>
        <th>Reference No</th>
        <td><?= htmlspecialchars($row['reference_number']); ?></td>
    </tr>
</table>


                            

                          
                        </div>
                        <button class="btn confirm-btn" onclick="handleAction('confirm', <?= $row['booking_id']; ?>)">Confirm</button>
                        <button class="btn cancel-btn" onclick="handleAction('cancel', <?= $row['booking_id']; ?>)">Cancel</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pending bookings found.</p>
            <?php endif; ?>
        </section>

        <!-- Confirmed Bookings -->
        <section class="section">
            <h2>Confirmed Bookings</h2>
            <?php if ($result_confirmed->num_rows > 0): ?>
                <?php while ($row = $result_confirmed->fetch_assoc()): ?>
                    <div class="card">
                        <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Vehicle Image">
                        <div class="card-content">
                        <table class="table table-bordered">
    <tr>
        <th>Booking ID</th>
        <td><?= htmlspecialchars($row['booking_id']); ?></td>
    </tr>
    <tr>
        <th>User</th>
        <td><?= htmlspecialchars($row['username']); ?> (<?= htmlspecialchars($row['email']); ?>)</td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td><?= htmlspecialchars($row['model']); ?> (<?= htmlspecialchars($row['year']); ?>, <?= htmlspecialchars($row['color']); ?>)</td>
    </tr>
    <tr>
        <th>Price</th>
        <td>$<?= htmlspecialchars($row['price']); ?></td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td><?= htmlspecialchars($row['start_date']); ?></td>
    </tr>
    <tr>
        <th>Pickup</th>
        <td><?= htmlspecialchars($row['pickup_location']); ?></td>
    </tr>
    <tr>
        <th>Fee</th>
        <td>$<?= htmlspecialchars($row['total_fee']); ?></td>
    </tr>
    <tr>
        <th>Vehicle ID</th>
        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td><?= htmlspecialchars($row['payment_method']); ?></td>
    </tr>
    <tr>
        <th>Reference No</th>
        <td><?= htmlspecialchars($row['reference_number']); ?></td>
    </tr>
</table>
                        </div>
                        <button class="btn confirm-btn" onclick="handleAction('confirm', <?= $row['booking_id']; ?>)">Confirm</button>
                        <button class="btn cancel-btn" onclick="handleAction('cancel', <?= $row['booking_id']; ?>)">Cancel</button>
 
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No confirmed bookings found.</p>
            <?php endif; ?>
        </section>

        <!-- Cancelled Bookings -->
        <section class="section">
            <h2>Cancelled Bookings</h2>
            <?php if ($result_cancelled->num_rows > 0): ?>
                <?php while ($row = $result_cancelled->fetch_assoc()): ?>
                    <div class="card">
                        <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Vehicle Image">
                        <div class="card-content">
                        <table class="table table-bordered">
    <tr>
        <th>Booking ID</th>
        <td><?= htmlspecialchars($row['booking_id']); ?></td>
    </tr>
    <tr>
        <th>User</th>
        <td><?= htmlspecialchars($row['username']); ?> (<?= htmlspecialchars($row['email']); ?>)</td>
    </tr>
    <tr>
        <th>Vehicle</th>
        <td><?= htmlspecialchars($row['model']); ?> (<?= htmlspecialchars($row['year']); ?>, <?= htmlspecialchars($row['color']); ?>)</td>
    </tr>
    <tr>
        <th>Price</th>
        <td>$<?= htmlspecialchars($row['price']); ?></td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td><?= htmlspecialchars($row['start_date']); ?></td>
    </tr>
    <tr>
        <th>Pickup</th>
        <td><?= htmlspecialchars($row['pickup_location']); ?></td>
    </tr>
    <tr>
        <th>Fee</th>
        <td>$<?= htmlspecialchars($row['total_fee']); ?></td>
    </tr>
    <tr>
        <th>Vehicle ID</th>
        <td><?= htmlspecialchars($row['vehicle_id']); ?></td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td><?= htmlspecialchars($row['payment_method']); ?></td>
    </tr>
    <tr>
        <th>Reference No</th>
        <td><?= htmlspecialchars($row['reference_number']); ?></td>
    </tr>
</table>
                        </div>
                        <button class="btn confirm-btn" onclick="handleAction('confirm', <?= $row['booking_id']; ?>)">Confirm</button>
                        <button class="btn cancel-btn" onclick="handleAction('cancel', <?= $row['booking_id']; ?>)">Cancel</button>
 
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No cancelled bookings found.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Hidden Form for Actions -->
    <form id="actionForm" method="post" style="display: none;">
        <input type="hidden" name="action" id="actionInput">
        <input type="hidden" name="booking_id" id="bookingIdInput">
    </form>

    <script>
        function handleAction(action, bookingId) {
            if (confirm(`Are you sure you want to ${action} this booking?`)) {
                document.getElementById('actionInput').value = action;
                document.getElementById('bookingIdInput').value = bookingId;
                document.getElementById('actionForm').submit();
            }
        }
    </script>
</body>
</html>
