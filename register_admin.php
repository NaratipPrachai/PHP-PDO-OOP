<?php
require_once 'dbconnect.php';

// เริ่ม session
session_start();

// ตรวจสอบว่าผู้ใช้ลงทะเบียนหรือยัง
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php"); // ถ้าลงทะเบียนแล้วให้ redirect ไปที่หน้า dashboard.php
    exit();
}

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO Admins (UserName, Password) VALUES (:username, :password)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        echo "ลงทะเบียนสำเร็จ!";
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Add your CSS styles or include Bootstrap CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Admin Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="admin_login.php">Login here</a></p>
</div>

<!-- Include Bootstrap JS and any additional scripts here -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
