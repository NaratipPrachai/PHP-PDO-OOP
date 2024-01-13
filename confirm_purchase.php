<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['customer_id'])) {
    // ถ้ายังไม่ได้ล็อกอิน ให้ redirect ไปที่หน้าล็อกอิน
    header("Location: login.php");
    exit();
}

// ตรวจสอบถ้า $_SESSION['cart'] ไม่มีค่าหรือเป็นตัวแปรที่ไม่ใช่ array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // ถ้าตะกร้าว่างเปล่า ให้ redirect กลับไปที่หน้า index.php
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ามีการยืนยันการซื้อสินค้าหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_purchase'])) {
    // ทำการยืนยันการซื้อสินค้า
    confirmPurchase();
}

// ฟังก์ชั่นยืนยันการซื้อสินค้า
function confirmPurchase() {
    // ทำการประมวลผลการซื้อสินค้าที่นี่
    // ...

    // ล้างข้อมูลในตะกร้าสินค้าหลังการซื้อ
    unset($_SESSION['cart']);

    // สามารถทำการบันทึกข้อมูลการซื้อเพิ่มเติมได้
    // ...

    // แสดงข้อความหลังการซื้อสินค้า
    echo "การซื้อสินค้าเสร็จสมบูรณ์! ขอบคุณที่ใช้บริการ";

    // สามารถเพิ่มการส่งออกในรูปแบบ HTML หรือแก้ไขตามต้องการ
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการซื้อสินค้า</title>
    <!-- เพิ่ม CSS styles หรือรวม Bootstrap CSS ที่นี่ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">ยืนยันการซื้อสินค้า</h2>
    
    <!-- แสดงรายการสรุปรายการซื้อสินค้าที่ลูกค้าจะได้รับ -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">สินค้า</th>
                <th scope="col">ราคา</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $product) : ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td>฿<?php echo number_format($product['price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <button type="submit" name="confirm_purchase" class="btn btn-primary">ยืนยันการซื้อสินค้า</button>
    </form>
</div>

<!-- รวม Bootstrap JS และสคริปต์เพิ่มเติมที่นี่ -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
