<?php

include 'connectDB.php';

// Check if user_id is passed in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user details from the database
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user is found, fetch their details
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // If the user is not found, redirect to the manage users page
        header("Location: manage_users.php");
        exit();
    }
}

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // It's a good idea to hash the password before saving it
    $role = $_POST['role'];

    // Hash the password (if password is provided)
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // If no password is provided, use the existing password
        $hashed_password = $user['password'];
    }

    // Update the user details in the database
    $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $hashed_password, $role, $user_id);

    if ($stmt->execute()) {
        // Redirect back to manage users page with success message
        header("Location: manage_users.php?message=User updated successfully");
        exit();
    } else {
        // Error in updating the user
        $error_message = "Error updating user: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- HTML form to edit user -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f4f7fa;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: 600;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
    require_once 'navbar.php';
    ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit User</h2>
    
    <!-- Display success or error message -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Edit User Form -->
    <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" id="editUserForm">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <small class="form-text text-muted">Choose a unique username for the user.</small>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <small class="form-text text-muted">This will be used for login and notifications.</small>
        </div>

        <div class="form-group">
            <label for="password">Password (Leave empty if you don't want to change)</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password (optional)">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <small class="form-text text-muted">Choose the role for the user (User or Admin).</small>
        </div>

        <button type="submit" class="btn btn-primary btn-block" onclick="return confirmUpdate()">Update User</button>
        <a href="manage_users.php" class="btn btn-secondary btn-block">Cancel</a>
    </form>
</div>

<script>
    // JavaScript for confirming before updating the user
    function confirmUpdate() {
        return confirm("Are you sure you want to update this user's details?");
    }

    // Optional: Inline form validation
    $('#editUserForm').on('submit', function(e) {
        if ($('#username').val() === '' || $('#email').val() === '') {
            alert('Please fill in all required fields!');
            e.preventDefault(); // Prevent form submission
        }
    });
</script>

</body>
</html>
