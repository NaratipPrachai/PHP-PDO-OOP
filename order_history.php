<?php
require_once 'dbconnect.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT order_details.detail_id, orders.OrderID, customers.FirstName, customers.LastName, orders.OrderDate
        FROM order_details
        INNER JOIN orders ON order_details.order_id = orders.OrderID
        INNER JOIN customers ON orders.CustomerID = customers.CustomerID";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orderHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อสินค้า</title>
    <!-- เพิ่ม CSS styles หรือรวม Bootstrap CSS ที่นี่ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">ประวัติการสั่งซื้อสินค้า</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Product Name</th>
                <th>Product Image</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderHistory as $order): ?>
                <tr>
                    <td><?php echo $order['OrderID']; ?></td>
                    <td><?php echo $order['FirstName'] . ' ' . $order['LastName']; ?></td>
                    <td><?php echo $order['OrderDate']; ?></td>
                    <?php
                    // ดึงข้อมูลสินค้าจากตาราง order_detailss และ productss
                    $orderID = $order['OrderID'];
                    $sqlDetails = "SELECT products.ProductName, products.Img
                                   FROM order_detailss
                                   JOIN products ON order_detailss.ProductID = products.ProductID
                                   WHERE order_detailss.OrderID = ?";
                    $stmtDetails = $conn->prepare($sqlDetails);
                    $stmtDetails->execute([$orderID]);
                    $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <td>
                        <?php foreach ($orderDetails as $detail): ?>
                            <?php echo $detail['ProductName'] . '<br>'; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($orderDetails as $detail): ?>
                            <!-- ใช้ $detail['Img'] แทน products.ProductImage -->
                            <img src="<?php echo $detail['Img']; ?>" alt="Product Image" style="max-width: 50px; max-height: 50px;"><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- รวม Bootstrap JS และสคริปต์เพิ่มเติมที่นี่ -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
