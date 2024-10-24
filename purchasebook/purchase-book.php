<?php 
    include "../includes/config.php"; 
    session_start();
    // ตรวจสอบว่า login ยัง
    if (!isset($_SESSION['user_id'])) {
        header('location: ../sign_in.php');
        exit();
    
    }
    // ตรวจสอบ error
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // ดึงข้อมูลหนังสือจาก database
    if(isset($_GET['book_id'])){
        $book_id = $_GET['book_id'];

        $sql ="SELECT * FROM books WHERE id = '$book_id'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
        }else {
            echo "Book not found.";
            exit();
        }
    }
    if(isset($_POST["purchase"])){
        $user_id = $_SESSION["user_id"];
        $shipping_address = $_POST["shipping_address"];
        $quantity = 1;

        $sql = "INSERT INTO purchases (user_id,book_id,quantity,shipping_address,status) VALUES ('$user_id','$book_id','$quantity','$shipping_address','pending')";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success" id="alert-box"  role="alert">Order placed successfully!</div>';
        } else {
            echo '<div class="alert alert-danger" id="alert-box" role="alert">Error: ' . $conn->error . '</div>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Purchase book</title>
</head>
<body class="container">
    <?php include "../includes/purchase-nav.php"; ?>
    <h1 class="text-center mt-5">Place Order</h1>

    <!-- ตารางแสดงข้อมูลหนังสือ -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card" style="width: 18rem; margin: auto;">
                <img src="../<?php echo $row['image']; ?>" class="card-img-top" alt="Book Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['title']; ?></h5>
                    <p class="card-text">Author: <?php echo $row['author']; ?></p>
                    <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                </div>
            </div>
        </div>

        <!-- ฟอร์มกรอกที่อยู่และสั่งซื้อ -->
        <div class="col-md-6">
            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <label for="shipping_address" class="form-label">Shipping Address</label>
                    <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required></textarea>
                </div>
                <div>
                    <p class="card-text fs-3">Price: $<?php echo $row['price']; ?></p>
                </div>
                <button type="submit" name="purchase" class="btn btn-success w-100">Place Order</button>
            </form>
        </div>
    </div>
    <script>
        setTimeout(function() {
            var alertBox = document.getElementById('alert-box');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 3000);  // 3 วิ
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>