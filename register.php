<?php
include 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'register.html';</script>";
        exit();
    }

    
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain an uppercase letter and a number.'); window.location.href = 'register.html';</script>";
        exit();
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_email_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Please use a different email or log in.'); window.location.href = 'register.html';</script>";
        exit();
    }

    
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $role = 'user';  
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'login.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Unable to complete registration. Try again later.'); window.location.href = 'register.html';</script>";
    }

    
    $stmt->close();
    $conn->close();
}
?>
