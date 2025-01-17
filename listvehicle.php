<?php
session_start();
include 'connectDB.php';

if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}


$category = isset($_GET['category']) ? $_GET['category'] : '';


$sql = "SELECT vehicle_id, model, description, category,price, image FROM vehicles WHERE category = ? AND approved = 1 AND is_booked = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();


$vehicles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}
$stmt->close();
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
        background: url("img/background_Home.jpg") no-repeat center center fixed;
        background-size: cover;
        padding-top: 70px;
      }
      .btn-theme {
        background-color: #00cdfe;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 10px 20px;
        transition: background-color 0.3s ease;
      }

    .btn-theme:hover {
       background-color: #009cc0;
        color: white;
      }
      .card-img-top {
        width: 100%;  
        height: 200px; 
        object-fit: cover;  
        object-position: center; 
      }

    </style>
  </head>

  <body>
  <?php
    require_once 'navbar.php';
    ?>
    <section class="mt-5 mb-7">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Vehicles</h2>
        <div class="row">
            <?php if (!empty($vehicles)): ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                        <img src="uploads/<?php echo $vehicle['image']; ?>" class="card-img-top" alt="<?php echo $vehicle['category']; ?>">
                        <div class="card-body">
                                <h5 class="card-title"><?php echo $vehicle['model']; ?></h5>
                                <p class="card-text"><?php echo $vehicle['description']; ?></p>
                                <p class="card-text"><strong>Price per day:</strong> Rs. <?php echo $vehicle['price']; ?></p>
                                <a href="bookvehicle.php?id=<?php echo $vehicle['vehicle_id']; ?>" class="btn btn-theme">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <section class="text-center mt-5 mb-5">
                  <div class="py-4 px-3" style="background-color: #f8f9fa; border-radius: 10px;">
                    <h3 class="mb-4">No vehicles here right now!</h3>
                    <p class="mb-4">
                        You can try another category or let us know what you're looking for by contacting us directly. We're here to help!
                    </p>
                    <a href="contact.php" class="btn btn-primary btn-lg mx-2" style="border-radius: 30px;">Contact Us</a>
                    <a href="vehicles.php" class="btn btn-outline-primary btn-lg mx-2" style="border-radius: 30px;">Browse Other Categories</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
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
</body>
</html>
