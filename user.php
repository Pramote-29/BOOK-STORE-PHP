<?php
    include("includes/config.php");
    session_start();
    if($_SESSION['role'] != 'user'){
        header('location: sign_in.php');
        exit();
    }
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>users dashboard</title>
</head>
<body class="container">
    <?php include "includes/navout.php"; ?>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            // แสดงข้อมูลเป็น card
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem; margin-bottom: 20px;">
                        <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text"> <?php echo $row['author']; ?></p>
                            <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                            <a href="#" class="btn btn-primary">More Info</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No books available.";
        }
        ?>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>