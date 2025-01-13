<?php
// Include database connection
include 'connectDB.php';

// Check if vehicle_id is passed in the URL
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];

    // Fetch the vehicle details from the database
    $sql = "SELECT * FROM vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the vehicle is found, fetch its details
    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    } else {
        // If the vehicle is not found, redirect to manage vehicles page
        header("Location: managevehicle.php");
        exit();
    }
}

// Handle form submission to update vehicle details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $phone1 = $_POST['phone1'];
    $phone2 = $_POST['phone2'];
    $category = $_POST['category'];
    $location = $_POST['location'];

    // Update the vehicle details in the database
    $sql = "UPDATE vehicles SET model = ?, year = ?, color = ?, price = ?, description = ?, status = ?, phone1 = ?, phone2 = ?, category = ?, location = ? WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdssssssi", $model, $year, $color, $price, $description, $status, $phone1, $phone2, $category, $location, $vehicle_id);

    if ($stmt->execute()) {
        // Redirect back to manage vehicles page with success message
        header("Location: manage_vehicles.php?message=Vehicle updated successfully");
        exit();
    } else {
        // Error in updating the vehicle
        $error_message = "Error updating vehicle: " . $conn->error;
        // Redirect with error message
        header("Location: manage_vehicles.php?message=" . urlencode($error_message));
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - RENT & RIDE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-custom {
            border-radius: 50px;
            font-weight: bold;
        }
    </style>
</head>
<body>


<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Vehicle</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Vehicle Edit Form -->
    <form action="editvehicle.php?vehicle_id=<?php echo $vehicle_id; ?>" method="POST">
        <div class="form-row">
            <!-- Vehicle Model -->
            <div class="form-group col-md-6">
                <label for="model">Vehicle Model:</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $vehicle['model']; ?>" required placeholder="Enter vehicle model">
            </div>

            <!-- Year -->
            <div class="form-group col-md-6">
                <label for="year">Year:</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo $vehicle['year']; ?>" required placeholder="Enter vehicle year">
            </div>
        </div>

        <div class="form-row">
            <!-- Color -->
            <div class="form-group col-md-6">
                <label for="color">Color:</label>
                <input type="text" class="form-control" id="color" name="color" value="<?php echo $vehicle['color']; ?>" required placeholder="Enter vehicle color">
            </div>

            <!-- Price -->
            <div class="form-group col-md-6">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $vehicle['price']; ?>" required placeholder="Enter vehicle price">
            </div>
        </div>

        <div class="form-group">
            <!-- Description -->
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter vehicle description"><?php echo $vehicle['description']; ?></textarea>
        </div>

        <div class="form-row">
            <!-- Status -->
            <div class="form-group col-md-6">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" <?php echo ($vehicle['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="rented" <?php echo ($vehicle['status'] == 'rented') ? 'selected' : ''; ?>>Rented</option>
                </select>
            </div>

            <!-- Phone 1 -->
            <div class="form-group col-md-6">
                <label for="phone1">Phone 1:</label>
                <input type="text" class="form-control" id="phone1" name="phone1" value="<?php echo $vehicle['phone1']; ?>" required placeholder="Enter primary contact number">
            </div>
        </div>

        <div class="form-row">
            <!-- Phone 2 -->
            <div class="form-group col-md-6">
                <label for="phone2">Phone 2 (Optional):</label>
                <input type="text" class="form-control" id="phone2" name="phone2" value="<?php echo $vehicle['phone2']; ?>" placeholder="Enter secondary contact number">
            </div>

            <!-- Category -->
            <div class="form-group col-md-6">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo $vehicle['category']; ?>" required placeholder="Enter vehicle category">
            </div>
        </div>

        <div class="form-group">
            <!-- Location -->
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo $vehicle['location']; ?>" required placeholder="Enter vehicle location">
        </div>

        <div class="form-row">
            <!-- Update Button -->
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-custom btn-block">Update Vehicle</button>
            </div>

            <!-- Cancel Button -->
            <div class="col-md-6">
                <a href="managevehicle.php" class="btn btn-secondary btn-custom btn-block">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
