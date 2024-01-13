<?php
session_start();
require_once 'dbconnect.php';

// Check if the form is submitted and the add_to_cart button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    addToCart();
}

function addToCart() {
    global $conn;

    if (!isset($_SESSION['customer_id'])) {
        // Redirect to the login page with a return URL
        header("Location: login.php?return_url=shopping_cart.php");
        exit();
    }

    // Validate and sanitize input
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

    // Check if the product is already in the cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If the product is not in the cart, add it
    if (!$found) {
        try {
            // Use prepared statement to prevent SQL injection
            $sql = "SELECT * FROM products WHERE ProductID = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $cart_item = array(
                    'product_id' => $product_id,
                    'product_name' => $product['ProductName'],
                    'price' => $product['Price'],
                    'quantity' => $quantity
                );

                $_SESSION['cart'][] = $cart_item;
            }
        } catch (PDOException $e) {
            // Handle the exception (log, display an error message, etc.)
            // Example: echo "Error: " . $e->getMessage();
        }
    }

    // Redirect back to the shopping cart page
    header("Location: shopping_cart.php");
    exit();
}
?>
