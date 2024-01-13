<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นราธิป e-book</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
    .sakura {
        position: fixed;
        top: -50px;
        left: 0;
        width: 100%;
        height: 100vh;
        background-image: url('image/sakura.png');
        background-size: cover;
        background-repeat: no-repeat;
        animation: fallDown 3600000ms linear forwards; /* 1 hour duration */
    }

    @keyframes fallDown {
        to {
            top: 100%;
        }
    }
</style>

</head>
<body>
<div id="sakura" class="sakura"></div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var sakura = document.getElementById('sakura');
        sakura.parentNode.removeChild(sakura);
    }, 99999999999999999); /* ตั้งค่าเวลาที่ต้องการให้ดอกซากุระหายไป (3 วินาที) */
});

</script>
    <?php 
    require_once 'dbconnect.php';
    include 'nav.php';
    // Retrieve products from the database
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="container mt-5">

    <div class="row">
        <?php foreach ($products as $product) : ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <?php if (!empty($product['Img'])) : ?>
                        <img src="<?php echo $product['Img']; ?>" class="card-img-top" alt="Product Image">
                    <?php else : ?>
                        <!-- รูปว่าง -->
                        <img src="path/to/placeholder-image.jpg" class="card-img-top" alt="Product Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['ProductName']; ?></h5>
                        <p class="card-text">ราคา: ฿<?php echo number_format($product['Price']); ?> บาท</p>
                        <div class="text-center">
                            <a href="purchase.php?productName=<?php echo urlencode($product['ProductName']); ?>&productPrice=<?php echo urlencode($product['Price']); ?>" class="btn btn-primary btn-buy">ซื้อสินค้า</a>

                            <!-- เพิ่ม form สำหรับเพิ่มสินค้าลงตะกร้า -->
                            <form method="post" class="mt-3" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="btn btn-info">เพิ่มลงตะกร้าสินค้า</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .btn-buy {
        width: 100%; /* ปรับขนาดปุ่มให้เต็ม width */
    }
    .card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: scale(1.05);
}

</style>
<script>
    // ใช้ JavaScript เพื่อเพิ่มและลบคลาส 'hover' เมื่อมีการเรียกใช้งานเหตุการณ์ mouseenter และ mouseleave
    document.addEventListener("DOMContentLoaded", function () {
        var cards = document.querySelectorAll('.card');

        cards.forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                card.classList.add('hover');
            });

            card.addEventListener('mouseleave', function () {
                card.classList.remove('hover');
            });
        });
    });
</script>



 <?php
  include 'footer.php'
  ?>

    <!-- Include Bootstrap JS and any additional scripts here -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
