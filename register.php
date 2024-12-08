<?php
session_start();

include 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'register.php';</script>";
        exit();
    }

    
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and contain an uppercase letter and a number.'); window.location.href = 'register.php';</script>";
        exit();
    }

   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_email_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Please use a different email or log in.'); window.location.href = 'register.php';</script>";
        exit();
    }

   
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $role = 'user';  
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Unable to complete registration. Try again later.'); window.location.href = 'register.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RENT & RIDE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
      body {
        background: url("img/login_background.jpg") no-repeat center center fixed;
        background-size: cover;
        padding-top: 70px;
      }
      .navbar, .navbar-nav .nav-link, .form-label, footer {
        color: white;
      }
      input, button {
        color: black;
      }
      h2, p {
        color: #f0f0f0;
      }
      form {
        background: rgba(0, 0, 0, 0.5);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
      }
      button {
        background-color: #00cdfe;
      }
      button:hover {
        background-color: #0099cc;
      }
      h2, p, .navbar-nav .nav-link {
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
      }
    </style>
  </head>

  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <img
            src="img/LogoNew.jpg"
            alt="Rent & Ride Logo"
            style="height: 40px; margin-right: 10px"
          />
          Rent & Ride
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="vehicles.php">Vehicles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>

            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link btn btn-warning text-white" href="myadmin.php"
                >Admin Dashboard</a
              >
            </li>
            <?php endif; ?>

           
            <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link btn btn-secondary text-white" href="logout.php"
                >Logout</a
              >
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link btn btn-primary text-white" href="login.php"
                >Login</a
              >
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <section class="container my-5">
      <h2 class="text-center mb-4">Sign Up for Rent & Ride</h2>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form action="register.php" method="POST" onsubmit="return validatePasswords()">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required />
            </div>
            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
          </form>
          <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
        </div>
      </div>
    </section>

    <script>
      function validatePasswords() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (password !== confirmPassword) {
          alert("Passwords do not match! Please try again.");
          return false;
        }

        if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
          alert("Password must be at least 8 characters long and contain an uppercase letter and a number.");
          return false;
        }

        return true;
      }
    </script>

    <footer class="bg-dark text-white text-center py-4">
      <div>
        <a href="https://www.facebook.com/" target="_blank" class="text-white mx-2"><i class="fab fa-facebook fa-2x"></i></a>
        <a href="https://www.twitter.com/" target="_blank" class="text-white mx-2"><i class="fab fa-twitter fa-2x"></i></a>
        <a href="https://www.instagram.com/" target="_blank" class="text-white mx-2"><i class="fab fa-instagram fa-2x"></i></a>
        <a href="https://www.linkedin.com/" target="_blank" class="text-white mx-2"><i class="fab fa-linkedin fa-2x"></i></a>
      </div>
      <p>&copy; 2024 Rent & Ride. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
