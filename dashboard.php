<?php
// Include the session start at the top of the file
session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// You can include other necessary files or perform additional checks here

// Admin is logged in, continue with the dashboard content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add your CSS styles or include Bootstrap CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Add your dashboard content here -->

    <p>Welcome, <?php echo $_SESSION['admin_username']; ?>! You are now logged in.</p>
    <?php 
    include 'product_list.php'
    ?>

    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Include Bootstrap JS and any additional scripts here -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
