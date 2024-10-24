<?php
    include("includes/config.php");
    session_start();
    // ดึงข้อมูลจากtable books
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class="container bg-light">
    <?php include "includes/navbar.php"; ?>
    <div class="container mt-5">
        <h1 class="fs-2 fw-bold text-primary">Welcome to Book.store</h1>
        <p>ที่นี่คือสถานที่ที่คุณจะได้พบกับโลกแห่งการอ่านที่ไม่มีที่สิ้นสุด เรามีหนังสือมากมายจากหลากหลายแนว ทั้งวรรณกรรมคลาสสิก นิยายฟันธง หนังสือเรียน และหนังสือเด็ก ที่จะเปิดประตูสู่จินตนาการและความรู้ใหม่ๆนอกจากนี้เรายังมีบริการยืมหนังสือ!
        หากคุณไม่แน่ใจว่าหนังสือเล่มไหนเหมาะกับคุณ หรือคุณแค่อยากทดลองอ่านก่อนตัดสินใจซื้อ บริการยืมหนังสือของเราเป็นทางเลือกที่ยอดเยี่ยม!ไม่ว่าคุณจะเป็นนักอ่านตัวยง หรือแค่เริ่มต้นเพิ่งจะสนใจการอ่าน เราขอต้อนรับคุณเข้าสู่โลกของหนังสือที่เต็มไปด้วยความรู้และความสนุก เราหวังว่าคุณจะสนุกกับการเลือกหนังสือ และพบกับการอ่านที่น่าตื่นเต้น!
มาร่วมเป็นส่วนหนึ่งของร้านหนังสือของเรา และค้นพบโลกใบใหม่ผ่านหน้ากระดาษกันเถอะ!</p>
    </div>
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
                            <?php if(isset($_SESSION['username'])) { ?>
                                <a href="#" class="btn btn-primary">Buy</a>
                                <a href="#" class="btn btn-success">Borrow</a>
                            <?php } else { ?>
                                <a href="sign_in.php" class="btn btn-primary" onclick="return alert('กรุณา LOGIN ก่อนดำเนินการซื้อ');">Buy</a>
                                <a href="sign_in.php" class="btn btn-success" onclick="return alert('กรุณา LOGIN ก่อนดำเนินการยืม');">Borrow</a>
                            <?php } ?>
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