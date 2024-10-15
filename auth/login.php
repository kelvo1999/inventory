<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 1){
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();
        if(password_verify($password, $hashed_password)){
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['last_login'] = date("Y-m-d H:i:s");

            // Redirect based on role
            if($role == 'admin'){
                header("Location: ../admin/dashboard.php");
            } elseif($role == 'seller'){
                header("Location: ../seller/dashboard.php");
            } else {
                header("Location: ../customer/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid credentials.";
        }
    } else {
        $_SESSION['error'] = "Invalid credentials.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Winny Thrift Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Login</h2>
    <?php
        if(isset($_SESSION['error'])) {
            echo "<p class='error'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
    ?>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br/>
        <input type="password" name="password" placeholder="Password" required><br/>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
