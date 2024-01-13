<?php
    // เริ่มต้น session
    session_start();

    // ตรวจสอบว่ามีการ login หรือไม่
    if (isset($_SESSION['customer_id'])) {
        // ถ้ามีการ login แสดง navbar สำหรับผู้ใช้ที่ login
        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="#">Naratip Prachai</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                   
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">ออกจากระบบ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shopping_cart.php">ตะกร้าสินค้า</a>
                        </li>
                    </ul>
                </div>
              </nav>';
    } else {
        // ถ้ายังไม่ได้ login แสดง navbar สำหรับผู้ที่ยังไม่ได้ login
        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="#">Naratip Prachai Online Store</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">ลงชื่อเข้าใช้</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">สมัครสมาชิก</a>
                        </li>
                    </ul>
                </div>
              </nav>';
    }
    ?>