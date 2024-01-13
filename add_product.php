<?php
require_once 'dbconnect.php';

// Start session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    // If not an admin, redirect to login page or display an error message
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = $_POST['productName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stockQuantity'];

    // File upload handling (assuming you are storing file names in the 'Img' column)
    $imgFileName = $_FILES['img']['name'];
    $imgTempName = $_FILES['img']['tmp_name'];
    $imgPath = 'image/' . $imgFileName; // Assuming there is an 'uploads' directory

    // Move uploaded file to the specified directory
    move_uploaded_file($imgTempName, $imgPath);

    // Insert data into the 'products' table
    $sql = "INSERT INTO products (ProductName, Description, Price, StockQuantity, Img) 
            VALUES (:productName, :description, :price, :stockQuantity, :img)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':productName', $productName);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stockQuantity', $stockQuantity);
    $stmt->bindParam(':img', $imgPath);
    $stmt->execute();

    echo "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Add your CSS styles or include Bootstrap CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Add Product</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name:</label>
            <input type="text" class="form-control" name="productName" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="text" class="form-control" name="price" required>
        </div>
        <div class="mb-3">
            <label for="stockQuantity" class="form-label">Stock Quantity:</label>
            <input type="text" class="form-control" name="stockQuantity" required>
        </div>
        <div class="mb-3">
            <label for="img" class="form-label">Product Image:</label>
            <input type="file" class="form-control" name="img" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<!-- Include Bootstrap JS and any additional scripts here -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
