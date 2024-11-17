<?php
require_once '../includes/auth_check.php';
checkRole('customer');
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - Winny Thrift Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

    <h2>Your Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php
            $customer_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT orders.order_id, products.name, orders.quantity, orders.order_status, orders.order_date FROM orders JOIN products ON orders.product_id = products.product_id WHERE orders.customer_id=?");
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while($order = $result->fetch_assoc()){
                echo "<tr>
                        <td>".$order['order_id']."</td>
                        <td>".$order['name']."</td>
                        <td>".$order['quantity']."</td>
                        <td>".$order['order_status']."</td>
                        <td>".$order['order_date']."</td>
                      </tr>";
            }
            $stmt->close();
        ?>
    </table>

    <h2>Update Profile</h2>
    <!-- Implement profile update form -->

    <a href="../auth/logout.php">Logout</a>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
