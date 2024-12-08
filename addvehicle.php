<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $name = $_POST['name'] ?? ''; 
    $email = $_POST['email'] ?? ''; 
    $model = $_POST['model'] ?? ''; 
    $year = $_POST['year'] ?? null; 
    $color = $_POST['color'] ?? ''; 
    $price = $_POST['price'] ?? 0.0; 
    $phone1 = $_POST['phone1'] ?? null; 
    $phone2 = $_POST['phone2'] ?? null; 
    $description = $_POST['description'] ?? ''; 
    
    // Get the selected category
    $category = $_POST['category'] ?? ''; 

    // If no category is selected, show an error
    if (empty($category)) {
        echo "<script>alert('Please select a category for the vehicle.'); window.location.href='addvehicle.php';</script>";
        exit;
    }

    $approved = 0;  // Default is not approved
    $status = 'available';  // Default status is available
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    
    // Create uploads directory if it does not exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Validate file type (only allow images)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['image']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Invalid file type. Only JPEG, PNG, and GIF are allowed.'); window.location.href='addvehicle.php';</script>";
        exit;
    }
    
    // Set the target file path
    $target_file = $target_dir . basename($image);
    
    // Upload the image
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo "<script>alert('Failed to upload image. Please try again later.'); window.location.href='addvehicle.php';</script>";
        exit;
    }
    
    // Prepare the SQL query to insert the vehicle data
    $sql = "INSERT INTO vehicles (owner_id, model, year, color, price, description, approved, status, created_at, phone1, phone2, image, category) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo "<script>alert('Server error. Please contact support.'); window.location.href='addvehicle.php';</script>";
        exit;
    }
    
    // Bind parameters to the SQL query
    // Use "isssdsdssss" for the data types
    $stmt->bind_param("isssdsdssss", $_SESSION['user_id'], $model, $year, $color, $price, $description, $approved, $status, $phone1, $phone2, $image, $category);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Vehicle successfully added for approval.'); window.location.href='vehicles.php';</script>";
    } else {
        echo "<script>alert('Failed to add vehicle. Try again.'); window.location.href='addvehicle.php';</script>";
    }
    
    // Close the statement and connection
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
        border-radius: 20px;
      }

      .btn-theme:hover {
        background-color: #0099cc;
      }
      section.container {
        margin-top: 80px; 
        margin-bottom: 30px;
      }
      .blur-effect {
        background: rgba(255, 255, 255, 0.2); 
        backdrop-filter: blur(10px); 
        padding: 40px;
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
      }
    </style>
  </head>
<body>
    <div class="container">
        <h2>Add Vehicle</h2>
        <form method="POST" enctype="multipart/form-data">
            <!-- Vehicle Name -->
            <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required class="form-control">
            </div>

            <!-- Vehicle Email -->
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>

            <!-- Vehicle Model -->
            <div class="mb-3">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" required class="form-control">
            </div>

            <!-- Vehicle Year -->
            <div class="mb-3">
                <label for="year">Year</label>
                <input type="number" id="year" name="year" required class="form-control">
            </div>

            <!-- Vehicle Color -->
            <div class="mb-3">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" required class="form-control">
            </div>

            <!-- Vehicle Price -->
            <div class="mb-3">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required class="form-control">
            </div>

            <!-- Phone Numbers -->
            <div class="mb-3">
                <label for="phone1">Phone 1</label>
                <input type="text" id="phone1" name="phone1" class="form-control">
            </div>

            <div class="mb-3">
                <label for="phone2">Phone 2</label>
                <input type="text" id="phone2" name="phone2" class="form-control">
            </div>

            <!-- Vehicle Description -->
            <div class="mb-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" class="form-control"></textarea>
            </div>

            <!-- Category Selection -->
            <h4>Vehicle Category</h4>
            <div class="mb-3">
              <input type="radio" name="category" value="Car" id="car" required> <label for="car">Car</label>
              <input type="radio" name="category" value="Van" id="van" required> <label for="van">Van</label>
              <input type="radio" name="category" value="Scooter" id="scooter" required> <label for="scooter">Scooter</label>
              <input type="radio" name="category" value="Motorbike" id="motorbike" required> <label for="motorbike">Motorbike</label>
              <input type="radio" name="category" value="Three-Wheel" id="three-wheel" required> <label for="three-wheel">Three-Wheel</label>
            </div>

            <!-- Vehicle Image Upload -->
            <div class="mb-3">
                <label for="image">Upload Vehicle Image</label>
                <input type="file" id="image" name="image" accept="image/*" required class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Add Vehicle</button>
        </form>
    </div>
</body>
</html>
