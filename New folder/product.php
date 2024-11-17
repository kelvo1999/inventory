<?php
require_once 'includes/db.php';
session_start();

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT products.*, users.username FROM products JOIN users ON products.seller_id = users.user_id WHERE product_id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Winny Thrift Shop</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="product-details">
        <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h2><?php echo $product['name']; ?></h2>
        <p><?php echo $product['description']; ?></p>
        <p>Price: $<?php echo $product['price']; ?></p>
        <p>Stock: <?php echo $product['stock_quantity']; ?></p>
        <p>Seller: <?php echo $product['username']; ?></p>
        
        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
            <form action="purchase.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="number" name="quantity" min="1" max="<?php echo $product['stock_quantity']; ?>" value="1" required>
                <button type="submit">Purchase</button>
            </form>
        <?php else: ?>
            <p>Please <a href="auth/login.php">login</a> as a customer to purchase.</p>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
