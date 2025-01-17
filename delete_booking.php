<?php
    require_once 'connectDB.php';
    if (!isset($_SESSION['user_id']) ) {
        header("Location: login.php");
        exit;
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    $delete_query = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        header("Location: myprofile.php?message=Booking deleted successfully");
        exit();
    } else {
        echo "Error deleting booking.";
    }
}
?>
