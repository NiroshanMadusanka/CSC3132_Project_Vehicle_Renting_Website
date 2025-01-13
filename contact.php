<?php
session_start();

require_once "connectDB.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $message = $conn->real_escape_string($_POST['message']);

  // Insert data into the contact_submissions table
  $sql = "INSERT INTO contact_submissions (name, email, message) VALUES ('$name', '$email', '$message')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Thank you for contacting us! Your message has been received.');
            window.location.href = 'contact.php'; // Redirect after the alert
          </script>";
} else {
    echo "<script>
            alert('There was an error submitting your message: " . addslashes($conn->error) . "');
            window.location.href = 'contact.php'; // Redirect after the alert
          </script>";
}

}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RENT & RIDE</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <style>
      body {
        background: url("img/contact\ us\ background.jpg") no-repeat center
          center fixed;
        background-size: cover;
        padding-top: 70px;
      }
      .fab:hover {
        transform: scale(1.1);
        transition: transform 0.3s ease, color 0.3s ease;
      }
    </style>
  </head>

  <body>
  <?php
    require_once 'navbar.php';
    ?>
    <section class="container my-5">
      <h2 class="text-center mb-4">Get in Touch</h2>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="text-center">
            <p class="mb-4">
              Have questions? Reach out to us through any of the following
              platforms:
            </p>
            <div class="d-flex justify-content-center">
              <a
                href="https://www.facebook.com"
                target="_blank"
                class="text-primary mx-4"
              >
                <i class="fab fa-facebook fa-3x"></i>
              </a>
              <a
                href="https://www.instagram.com"
                target="_blank"
                class="text-danger mx-4"
              >
                <i class="fab fa-instagram fa-3x"></i>
              </a>
              <a
                href="https://www.twitter.com"
                target="_blank"
                class="text-info mx-4"
              >
                <i class="fab fa-twitter fa-3x"></i>
              </a>
              <a
                href="https://www.linkedin.com"
                target="_blank"
                class="text-primary mx-4"
              >
                <i class="fab fa-linkedin fa-3x"></i>
              </a>
              <a
                href="https://www.youtube.com"
                target="_blank"
                class="text-danger mx-4"
              >
                <i class="fab fa-youtube fa-3x"></i>
              </a>
            </div>
            <div class="mt-5">
              <p class="mb-3">
                <i class="fas fa-envelope fa-2x text-warning"></i>
                <a
                  href="mailto:info@rentride.com"
                  class="text-decoration-none fs-5 ms-2"
                >
                  Mail Us
                </a>
              </p>
              <p>
                <i class="fas fa-phone fa-2x text-success"></i>
                <a
                  href="tel:+94000000000"
                  class="text-decoration-none fs-5 ms-2"
                >
                  +94 00 000 0000
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="container mt-5">
        <h1 class="text-center mb-4">Contact Us</h1>
        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </section>
    <footer class="bg-dark text-white text-center py-4">
      <div>
        <a
          href="https://www.facebook.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-facebook fa-2x"></i>
        </a>
        <a
          href="https://www.twitter.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-twitter fa-2x"></i>
        </a>
        <a
          href="https://www.instagram.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-instagram fa-2x"></i>
        </a>
        <a
          href="https://www.linkedin.com/"
          target="_blank"
          class="text-white mx-2"
        >
          <i class="fab fa-linkedin fa-2x"></i>
        </a>
      </div>
      <p>&copy; 2024 Rent & Ride. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
