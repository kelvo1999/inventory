<?php
require_once '../includes/auth_check.php';
checkRole('admin');
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Kiboko Body Builders</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Admin Dashboard</h1>

    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php
            $stmt = $conn->prepare("SELECT user_id, username, email, role FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            while($user = $result->fetch_assoc()){
                echo "<tr>
                        <td>".$user['username']."</td>
                        <td>".$user['email']."</td>
                        <td>".$user['role']."</td>
                        <td>
                            <a href='edit_user.php?id=".$user['user_id']."'>Edit</a> |
                            <a href='delete_user.php?id=".$user['user_id']."' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
                      </tr>";
            }
            $stmt->close();
        ?>
    </table>

    <h2>View Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php
            $stmt = $conn->prepare("SELECT orders.order_id, users.username, products.name, orders.quantity, orders.order_status, orders.order_date FROM orders JOIN users ON orders.customer_id = users.user_id JOIN products ON orders.product_id = products.product_id");
            $stmt->execute();
            $result = $stmt->get_result();
            while($order = $result->fetch_assoc()){
                echo "<tr>
                        <td>".$order['order_id']."</td>
                        <td>".$order['username']."</td>
                        <td>".$order['name']."</td>
                        <td>".$order['quantity']."</td>
                        <td>".$order['order_status']."</td>
                        <td>".$order['order_date']."</td>
                      </tr>";
            }
            $stmt->close();
            $conn->close();
        ?>
    </table>

    <a href="../auth/logout.php">Logout</a>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
