<?php
include("../includes/config.php");
session_start();

// ตรวจสอบว่าเป็นผู้ใช้ที่เข้าสู่ระบบหรือไม่
if($_SESSION['role'] != 'user'){
    header('location: ../sign_in.php');
    exit();
}
if(!isset($_SESSION['user_id'])) {
    // ถ้าไม่มีค่า user_id ใน session ให้ส่งผู้ใช้ไปยังหน้า sign_in.php
    header('location: ../sign_in.php');
    exit();
}
// check error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET['book_id'])){
    $book_id = $_GET['book_id'];

    // ดึงข้อมูลหนังสือ
    $sql = "SELECT * FROM books WHERE id = '$book_id'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit();
    }
}

// เมื่อกดปุ่ม borrow
if(isset($_POST['borrow'])){
    $user_id = $_SESSION['user_id']; // assume user is logged in
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];

    // ตรวจสอบว่าหนังสือเล่มนี้มีการยืมอยู่หรือไม่
    $check_sql = "SELECT * FROM borrow_records WHERE book_id = '$book_id' AND status = 'borrowed'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo '<div class="alert alert-success" role="alert">
              หนังสือเล่มนี้ถูกยืมแล้ว
          </div>';
    } else {
        // บันทึกข้อมูลการยืมหนังสือ
        $sql = "INSERT INTO borrow_records (user_id, book_id, borrow_date, return_date, status) 
                VALUES ('$user_id', '$book_id', '$borrow_date', '$return_date', 'borrowed')";
        
        if ($conn->query($sql) === TRUE) {
            echo  '<div class="alert alert-success" role="alert">
            Book borrowed successfully!
        </div>';
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Borrow Book</title>
</head>
<body class="container bg-light">
    <!-- Navbar -->
    <?php include "../includes/bor-buy-navbar.php"; ?>

    <h1 class="text-center mt-5">Borrow Book</h1>
    <div class="row mt-5" >
    <div class="col-md-6"id="borrow-book">
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="borrow_date" class="form-label">Borrow Date</label>
                <input type="date" class="form-control" id="borrow_date" name="borrow_date" required>
            </div>
            <div class="mb-3">
                <label for="return_date" class="form-label">Return Date</label>
                <input type="date" class="form-control" id="return_date" name="return_date" required>
            </div>
            <button type="submit" name="borrow" class="btn btn-success w-100">Borrow</button>
        </form>
    </div>

    <div class="col-md-6">
        <div class="card" style="width: 18rem; margin: auto;">
            <img src="../<?php echo $book['image']; ?>" class="card-img-top" alt="Book Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo $book['title']; ?></h5>
                <p class="card-text">Author: <?php echo $book['author']; ?></p>
                <p class="card-text">Price: $<?php echo $book['price']; ?></p>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>