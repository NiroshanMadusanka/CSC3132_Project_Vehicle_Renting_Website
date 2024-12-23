<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Vehicle - RENT & RIDE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .booking-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .booking-header {
            color: #00cdfe;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #00cdfe;
            border: none;
        }
        .btn-primary:hover {
            background-color: #009ec3;
        }
        .form-control:focus {
            border-color: #00cdfe;
            box-shadow: 0px 0px 5px rgba(0, 205, 254, 0.5);
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="booking-form">
                    <h2 class="booking-header text-center mb-4">Book Your Vehicle</h2>
                    <form action="book_vehicle.php" method="POST">
                        <!-- Hidden field for vehicle ID -->
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">

                        <div class="form-group">
                            <label for="start_date">Pick-up Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Return Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>

                        <div class="form-group">
                            <label for="user_name">Your Name:</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $_SESSION['username']; ?>" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Confirm Booking</button>
                        <a href="vehicles.php" class="btn btn-secondary btn-block">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
