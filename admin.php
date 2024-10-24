<?php
include("includes/config.php");
session_start();
// ตรวจสอบว่าเป็นadminไหม
if($_SESSION['role'] != 'admin'){
    header('location: sign_in.php');
    exit();
}


if(isset($_POST['submit'])){
    $title = $_POST ['title'];
    $author = $_POST ['author'];
    $price = $_POST['price'];   
    $target_dir = "uploads/"; 
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "";
        $uploadOk = 0;
    }
    if ($_FILES["image"]["size"] > 50000000000) { 
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $img_src = $target_file;
            $sql = "INSERT INTO books (title,author,price, image) VALUES ('$title','$author','$price','$img_src')"; 

            if ($conn->query($sql) === TRUE) {   
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    }
    // ดึงข้อมูลจาก table books
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin dashboard</title>
</head>
<body class="container bg-light ">
    <!--navbar logout -->
    <?php include "includes/navout.php"; ?>
    <!--ฟอร์ม เพิ่มข้อมูลหนังสือ -->
    <div class="">
        <h2 class="text-center fw-bold fs-2">ADD BOOK</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Book Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Book Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Book Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" name="submit" class="btn btn-success">add</button>
        </form>
    </div>
    <!--list ดูข้อมูลหนังสือที่เพิ่มมาจากform-->
    <div class="container">
        <h2 class="text-center fw-bold fs-1 mt-5 text-primary">Books List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['author'] ?></td>
                        <td><?= $row['price'] ?></td>
                        <td><img src="<?= $row['image'] ?>" alt="Book Image" width="100"></td>
                        <td>
                            <a href="updatebook.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="deletebook.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
