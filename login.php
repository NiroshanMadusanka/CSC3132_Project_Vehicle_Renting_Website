<?php
include 'connectDB.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email'])); 
    $password = htmlspecialchars($_POST['password']); 

    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); 
        
        
        if (password_verify($password, $user['password'])) {
            
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            
            header("Location: index.html");
            exit();
        } else {
            
            header("Location: login.html?error=invalid_password");
            exit();
        }
    } else {
        
        header("Location: login.html?error=email_not_found");
        exit();
    }

    
    $stmt->close();
    $conn->close();
}
?>
