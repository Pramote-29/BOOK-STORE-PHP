<?php
include("includes/config.php");
session_start();

// ตรวจสอบว่าเป็น admin หรือไม่
if ($_SESSION['role'] != 'admin') {
    header('location: sign_in.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า id มาจากหน้า dashboard หรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลหนังสือจากฐานข้อมูลเพื่อนำมาแสดงในฟอร์ม
    $sql = "SELECT * FROM books WHERE id = '$id'";
    $result = $conn->query($sql);
    $book = $result->fetch_assoc();
}

// เมื่อกดปุ่ม submit ในฟอร์มเพื่ออัปเดตข้อมูล
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $img_src = $target_file;

        // อัปเดตรูปภาพด้วย
        $sql = "UPDATE books SET title='$title', author='$author', price='$price', image='$img_src' WHERE id='$id'";
    } else {
        // อัปเดตข้อมูลทั่วไปโดยไม่อัปเดตรูปภาพ
        $sql = "UPDATE books SET title='$title', author='$author', price='$price' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: admin.php'); // กลับไปหน้า dashboard หลังอัปเดตสำเร็จ
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Book</title>
</head>
<body class="container bg-light">
    <!-- ฟอร์มอัปเดตข้อมูลหนังสือ -->
    <div class="container mt-5">
        <h2 class="text-center fw-bold">Update Book</h2>
        <form action="updatebook.php?id=<?= $book['id'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $book['title'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Book Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $book['author'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Book Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $book['price'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Book Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <!-- แสดงรูปปัจจุบัน -->
                <div class="mt-3">
                    <img src="<?= $book['image'] ?>" alt="Book Image" width="150">
                </div>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>