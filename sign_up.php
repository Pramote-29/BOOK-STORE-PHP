<?php
    include("includes/config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'];
        $password = password_hash($_POST['password'],PASSWORD_BCRYPT);
        $email = $_POST['email'];
        $role = $_POST['role'];

        //เข้าถึงsql
        $sql = "INSERT INTO users (username,email,password,role) VALUES ('$username','$email','$password','$role')";
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
?>
<!DOCTYPE html>
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
        <form action="" method="POST" id="signupform">
            <h2 class="fs-3 fw-bold">Sign up</h2>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Select Role</label>
                <select name="role" class="form-control">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-3">sign up</button>
            <p>alreay have an account ?<a href="sign_in.php">Click here</a>to sign in</p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>