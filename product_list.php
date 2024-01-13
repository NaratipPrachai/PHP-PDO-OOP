<?php
require_once 'dbconnect.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch the list of products from the database
$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Product List</h2>
  <a href="add_product.php" class="btn btn-success">เพิ่ม</a>
    <table class="table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Image</th>
                <th>Actions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo $product['ProductID']; ?></td>
                    <td><?php echo $product['ProductName']; ?></td>
                    <td><?php echo $product['Description']; ?></td>
                    <td><?php echo $product['Price']; ?></td>
                    <td><?php echo $product['StockQuantity']; ?></td>
                    <td>
                    <img src="<?php echo $product['Img']; ?>" class="card-img-top w-50" alt="Product Image">
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['ProductID']; ?>" class="btn btn-warning">Edit</a> <br>
                        
                    </td>
                    <td>
                    <a href="delete_product.php?id=<?php echo $product['ProductID']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
