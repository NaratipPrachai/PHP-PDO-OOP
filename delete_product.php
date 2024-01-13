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

    // Prepare SQL statement to delete the product
    $sql = "DELETE FROM products WHERE ProductID = :productId";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();

        header("Location: product_list.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if ProductID is not provided
    header("Location: product_list.php");
    exit();
}
?>
