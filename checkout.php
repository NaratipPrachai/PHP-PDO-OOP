<?php
session_start();

// Include the database connection
require_once 'dbconnect.php';

// Ensure $conn is available in this scope
global $conn;

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$cartItems = getCartItems();
$totalPrice = calculateTotalPrice($cartItems);

// Handle the form submission for the checkout process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Add your logic to process the checkout here
    // For example, you may want to insert an order into the database
    // and clear the shopping cart after successful checkout
    // ...

    // Redirect to a thank you page or order summary page
    header("Location: thank_you.php");
    exit();
}

function getCartItems() {
    global $conn;

    $cartItems = array();

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $sql = "SELECT * FROM products WHERE ProductID = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_id', $item['product_id']);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $cartItems[] = array(
                    'product_id' => $item['product_id'],
                    'product_name' => $product['ProductName'],
                    'quantity' => $item['quantity'],
                    'price' => $product['Price'],
                    'total' => $product['Price'] * $item['quantity'],
                    'img' => $product['Img']
                );
            }
        }
    }

    return $cartItems;
}

function calculateTotalPrice($items) {
    $totalPrice = 0;

    foreach ($items as $item) {
        $totalPrice += $item['total'];
    }

    return $totalPrice;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <form method="post" action="">
        <ul class="list-group">
            <?php foreach ($cartItems as $item) : ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo $item['img']; ?>" class="card-img-top" alt="Product Image">
                        </div>
                        <div class="col-md-8">
                            <h6><?php echo $item['product_name']; ?></h6>
                            <p>ราคา: <?php echo number_format($item['price']); ?> บาท - จำนวน: <?php echo $item['quantity']; ?></p>
                            <p>รวม: <?php echo number_format($item['total']); ?> บาท</p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Display the total price -->
        <h4 class="mt-4">รวมทั้งหมด: <?php echo number_format($totalPrice); ?> บาท</h4>

        <!-- Add your checkout form fields here (e.g., name, address, payment info, etc.) -->
        <!-- ...

        <!-- Add a submit button to process the checkout -->
        <button type="submit" name="checkout" class="btn btn-primary">ดำเนินการชำระเงิน</button>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
