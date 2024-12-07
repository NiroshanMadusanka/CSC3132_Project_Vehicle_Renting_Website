<?php

include('connectDB.php');


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    
    $sql = "UPDATE users SET role = 'admin' WHERE id = ?";
    
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id); // "i" stands for integer
        if ($stmt->execute()) {
            // If successful, redirect back to the user management page
            header("Location: manage_users.php?message=User+made+Admin");
            exit();
        } else {
            // If there's an error, display a message
            echo "Error updating record: " . $conn->error;
        }
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
