<?php
require_once "connectDB.php"

// Fetching form data
$pickup_location = $_GET['pickup_location'];
$drop_location = $_GET['drop_location'];
$pickup_date = $_GET['pickup_date'];
$drop_date = $_GET['drop_date'];
$category = $_GET['category'];
$near_me = $_GET['near_me'];

// Base SQL query
$sql = "SELECT * FROM vehicles WHERE is_booked = 0 AND category = '$category'";

// If "Find Vehicles Near Me" is "Yes", filter by pickup location
if ($near_me == "yes") {
    $sql .= " AND pickup_location = '$pickup_location'";
}

// If "Find Vehicles Near Me" is "No", don't add location filter
// Optionally, you can add more filters here (e.g., distance radius, etc.)

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Available Vehicles</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="row mt-4">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="images/<?php echo $row['image']; ?>" class="card-img-top" alt="Vehicle Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['vehicle_name']; ?></h5>
                                <p class="card-text">Category: <?php echo $row['category']; ?></p>
                                <p class="card-text">Location: <?php echo $row['pickup_location']; ?></p>
                                <p class="card-text">Price: $<?php echo $row['price']; ?> per day</p>
                               
                                <a href="book.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No vehicles found matching your search criteria.</p>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
