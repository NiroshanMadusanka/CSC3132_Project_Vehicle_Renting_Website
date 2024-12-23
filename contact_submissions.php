<?php
session_start();
require_once "connectDB.php";


if (isset($_POST['mark_as_read'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE contact_submissions SET reviewed = 1 WHERE id = $id";
    $conn->query($sql);
}


if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM contact_submissions WHERE id = $id";
    $conn->query($sql);
}


$sql_not_reviewed = "SELECT * FROM contact_submissions WHERE reviewed = 0";
$result_not_reviewed = $conn->query($sql_not_reviewed);


$sql_reviewed = "SELECT * FROM contact_submissions WHERE reviewed = 1";
$result_reviewed = $conn->query($sql_reviewed);
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
        /*background: url("img/admin_back.jpg") no-repeat center center fixed;*/
        background-size: cover;
        padding-top: 70px;
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
<div class="container mt-5">
    <h1 class="mb-4" align = "center">Messages</h1>

    
    <h2 class="mb-3">Not Reviewed</h2>
    <div class="row">
        <?php if ($result_not_reviewed->num_rows > 0): ?>
            <?php while ($row = $result_not_reviewed->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">From: <?php echo $row['name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Email: <?php echo $row['email']; ?></h6>
                            <p class="card-text"><?php echo $row['message']; ?></p>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="mark_as_read" class="btn btn-success">Mark as Read</button>
                            </form>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No new messages to review.</p>
        <?php endif; ?>
    </div>

   
    <h2 class="mb-3">Reviewed</h2>
    <div class="row">
        <?php if ($result_reviewed->num_rows > 0): ?>
            <?php while ($row = $result_reviewed->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">From: <?php echo $row['name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Email: <?php echo $row['email']; ?></h6>
                            <p class="card-text"><?php echo $row['message']; ?></p>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No reviewed messages.</p>
        <?php endif; ?>
    </div>
</div>
<footer class="bg-dark text-white text-center py-4 mt-4">
    <div>
        <a href="https://www.facebook.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-facebook fa-2x"></i>
        </a>
        <a href="https://www.twitter.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-twitter fa-2x"></i>
        </a>
        <a href="https://www.instagram.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-instagram fa-2x"></i>
        </a>
        <a href="https://www.linkedin.com/" target="_blank" class="text-white mx-2">
            <i class="fab fa-linkedin fa-2x"></i>
        </a>
    </div>
    <p>&copy; 2024 Rent & Ride. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
