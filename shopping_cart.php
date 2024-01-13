<?php
session_start();
require_once 'dbconnect.php';

global $conn;

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    if ($_GET['action'] === 'remove' && isset($_GET['product_id'])) {
        removeProductFromCart($_GET['product_id']);
    } elseif ($_GET['action'] === 'decrease' && isset($_GET['product_id'])) {
        decreaseProductQuantity($_GET['product_id']);
    }
}

$cartItems = getCartItems();
$totalPrice = 0;

function removeProductFromCart($product_id) {
    if (!isset($_SESSION['cart'])) {
        return;
    }

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    header("Location: shoppingcart.php");
    exit();
}

function decreaseProductQuantity($product_id) {
    if (!isset($_SESSION['cart'])) {
        return;
    }

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            // Decrease quantity by 1
            $_SESSION['cart'][$key]['quantity'] = max(1, $_SESSION['cart'][$key]['quantity'] - 1);
            break;
        }
    }

    header("Location: shoppingcart.php");
    exit();
}

function getCartItems() {
    global $conn;
    global $totalPrice;

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

                $totalPrice += ($product['Price'] * $item['quantity']);
            }
        }
    }

    return $cartItems;
}
?>

<!-- Your HTML code remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<!-- ... Your existing HTML code ... -->

<div class="container mt-5">
    <h2 class="mb-4">ตะกร้าสินค้า</h2>

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
                    <div class="col-md-2">
                        <a href="shopping_cart.php?action=remove&product_id=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm float-end">ลบ</a>
                        <!-- Add the "Buy Now" button -->
                        <a href="checkout.php?product_id=<?php echo $item['product_id']; ?>&quantity=<?php echo $item['quantity']; ?>" class="btn btn-success btn-sm float-end ms-2">ซื้อทันที</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- ... Your existing JavaScript code ... -->


<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
