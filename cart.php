<?php
session_start();
require_once 'dbconnect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['customer_id'])) {
    // ถ้ายังไม่ได้ล็อกอิน ให้ redirect ไปที่หน้าล็อกอิน
    header("Location: login.php");
    exit();
}

// ตรวจสอบการลบสินค้าออกจากตะกร้า
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['product_id'])) {
    removeProductFromCart($_GET['product_id'], $_SESSION['customer_id']);
}

// ดึงข้อมูลสินค้าในตะกร้าจากฐานข้อมูล
$cartItems = getCartItems($_SESSION['customer_id']);

function removeProductFromCart($product_id, $customer_id) {
    global $conn;

    // ทำการลบสินค้าออกจากตะกร้า
    $sql = "DELETE FROM cart WHERE product_id = :product_id AND customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();

    // ลิ้งค์กลับไปที่หน้า cart.php
    header("Location: cart.php");
    exit();
}

function getCartItems($customer_id) {
    global $conn;

    // ดึงข้อมูลสินค้าในตะกร้าจากฐานข้อมูล
    $sql = "SELECT products.ProductName AS product_name, products.Price AS price, cart.quantity, cart.product_id
            FROM cart
            INNER JOIN products ON cart.product_id = products.ProductID
            WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $cartItems;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <!-- เพิ่ม CSS styles หรือรวม Bootstrap CSS ที่นี่ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">ตะกร้าสินค้า</h2>

    <!-- แสดงรายการสินค้าที่อยู่ในตะกร้า -->
    <ul class="list-group">
        <?php foreach ($cartItems as $item) : ?>
            <li class="list-group-item">
                <?php echo $item['product_name']; ?> - 
                ราคา: <?php echo number_format($item['price']); ?> บาท - 
                จำนวน: <?php echo $item['quantity']; ?> 
                <a href="cart.php?action=remove&product_id=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm float-end">ลบ</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- รวม Bootstrap JS และสคริปต์เพิ่มเติมที่นี่ -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
