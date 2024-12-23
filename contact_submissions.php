<?php
require_once "connectDB.php";

// Mark as Reviewed
if (isset($_POST['mark_as_read'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE contact_submissions SET reviewed = 1 WHERE id = $id";
    $conn->query($sql);
}

// Delete Submission
if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM contact_submissions WHERE id = $id";
    $conn->query($sql);
}

// Fetch Not Reviewed Submissions
$sql_not_reviewed = "SELECT * FROM contact_submissions WHERE reviewed = 0";
$result_not_reviewed = $conn->query($sql_not_reviewed);

// Fetch Reviewed Submissions
$sql_reviewed = "SELECT * FROM contact_submissions WHERE reviewed = 1";
$result_reviewed = $conn->query($sql_reviewed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Contact Submissions</h1>

    <!-- Not Reviewed Section -->
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

    <!-- Reviewed Section -->
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
