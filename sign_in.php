<?php 
    include("includes/config.php");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // ตรวจสอบ email และ password
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
    
                if ($row['role'] == 'admin') {
                    header("Location:admin.php");
                } else {
                    header("Location:user.php");
                }
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with this email.";
        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/custom.css">
    <title>Sign up</title>
</head>
<body class="container">
    <?php include"includes/navbar.php"; ?>
    <div class="container">
        <form action="" method="POST" id="signinform">
            <h2 class="fs-3 fw-bold">Sign in</h2>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mb-3">sign in</button>
            <p>don't have an account ?<a href="sign_up.php">Click here</a>to sign up</p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>