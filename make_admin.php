<?php

include('connectDB.php');
if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    
    $sql = "UPDATE users SET role = 'admin' WHERE id = ?";
    
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id); 
        if ($stmt->execute()) {
            
            header("Location: manage_users.php?message=User+made+Admin");
            exit();
        } else {
            
            echo "Error updating record: " . $conn->error;
        }
    }

    $stmt->close();
}


$conn->close();
?>
