<?php
include("../includes/config.php");
session_start();

// ตรวจสอบว่าเป็นผู้ใช้ที่เข้าสู่ระบบหรือไม่
if ($_SESSION['role'] != 'user') {
    header('location: ../sign_in.php');
    exit();
}

// check error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ดึงข้อมูลการยืมหนังสือของผู้ใช้
$user_id = $_SESSION['user_id'];
$sql = "SELECT borrow_records.id AS borrow_id, books.title, books.author, books.price, borrow_records.borrow_date, borrow_records.return_date 
        FROM borrow_records 
        JOIN books ON borrow_records.book_id = books.id 
        WHERE borrow_records.user_id = '$user_id' AND borrow_records.status = 'borrowed'";
$result = $conn->query($sql);

// เมื่อกดปุ่มคืนหนังสือ
if (isset($_POST['return'])) {
    $borrow_id = $_POST['borrow_id'];
    
    // อัปเดตสถานะของการยืมให้เป็น "returned"
    $return_sql = "UPDATE borrow_records SET status = 'returned' WHERE id = '$borrow_id'";
    
    if ($conn->query($return_sql) === TRUE) {
        echo '<div class="alert alert-success" id="alert-box" role="alert">Book returned successfully!</div>';
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
    <title>Return Book</title>
</head>
<body class="container">
    <?php include "../includes/return-navbar.php"; ?>
    <h1 class="text-center mt-5">Return Book</h1>

    <!-- ตารางแสดงรายการยืมหนังสือ -->
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['author'] . "</td>";
                    echo "<td>" . $row['borrow_date'] . "</td>";
                    echo "<td>" . $row['return_date'] . "</td>";
                    echo '<td>
                            <form method="POST">
                                <input type="hidden" name="borrow_id" value="' . $row['borrow_id'] . '">
                                <button type="submit" name="return" class="btn btn-danger">Return</button>
                            </form>
                          </td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No books borrowed.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script>
        setTimeout(function() {
            var alertBox = document.getElementById('alert-box');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 3000);// 3 วินาที
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
