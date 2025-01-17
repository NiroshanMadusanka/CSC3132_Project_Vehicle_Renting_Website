<?php
session_start();
require_once 'connectDB.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID and role
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Check if the vehicle ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vehicle_id = intval($_GET['id']);

    // Check if the user is the owner of the vehicle or an admin
    $query = "SELECT owner_id FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();

        
        if ($vehicle['owner_id'] == $user_id || $user_role === 'admin') {
            
            $delete_query = "DELETE FROM vehicles WHERE vehicle_id = ? ";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $vehicle_id);

            
            if ($stmt->execute()) {
                
                header("Location: managevehicle.php?message=Vehicle deleted successfully");
                exit();
            } else {
                
                header("Location: managevehicle.php?error=Unable to delete vehicle");
                exit();
            }
        } else {
            
            header("Location: managevehicle.php?error=You are not authorized to delete this vehicle");
            exit();
        }
    } else {
        
        header("Location: managevehicle.php?error=Vehicle not found");
        exit();
    }
} else {
    
    header("Location: managevehicle.php?error=Invalid vehicle ID");
    exit();
}
?>
