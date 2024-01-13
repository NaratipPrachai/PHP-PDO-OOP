<?php
require_once 'dbconnect.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the ProductID is provided in the URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $productName = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stockQuantity = $_POST['stock_quantity'];

        try {
            // Check if a new image file is uploaded
            if ($_FILES['img']['size'] > 0) {
                $imgName = $_FILES['img']['name'];
                $imgTmpName = $_FILES['img']['tmp_name'];
                $imgType = $_FILES['img']['type'];
                $imgSize = $_FILES['img']['size'];
                $imgError = $_FILES['img']['error'];

                // Process the uploaded image
                $imgDestination = "uploads/" . $imgName;
                move_uploaded_file($imgTmpName, $imgDestination);

                // Update the product with the new image
                $sql = "UPDATE products SET ProductName = :productName, Description = :description, Price = :price, StockQuantity = :stockQuantity, Img = :img WHERE ProductID = :productId";
            } else {
                // Update the product without changing the image
                $sql = "UPDATE products SET ProductName = :productName, Description = :description, Price = :price, StockQuantity = :stockQuantity WHERE ProductID = :productId";
            }

            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':productName', $productName);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stockQuantity', $stockQuantity);
            $stmt->bindParam(':productId', $productId);

            // If a new image is uploaded, bind its name to the statement
            if ($_FILES['img']['size'] > 0) {
                $stmt->bindParam(':img', $imgName);
            }

            $stmt->execute();

            header("Location: product_list.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Fetch existing product details
    $sql = "SELECT * FROM products WHERE ProductID = :productId";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            // Redirect if the product is not found
            header("Location: product_list.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if ProductID is not provided
    header("Location: product_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Add your CSS styles or include Bootstrap CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Edit Product</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $productId); ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name:</label>
            <input type="text" class="form-control" name="product_name" value="<?php echo $product['ProductName']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description"><?php echo $product['Description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="text" class="form-control" name="price" value="<?php echo $product['Price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock_quantity" class="form-label">Stock Quantity:</label>
            <input type="text" class="form-control" name="stock_quantity" value="<?php echo $product['StockQuantity']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="img" class="form-label">Product Image:</label>
            <input type="file" class="form-control" name="img">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!-- Include Bootstrap JS and any additional scripts here -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
