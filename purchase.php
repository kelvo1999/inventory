<?php
session_start();
require_once 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer'){
        header("Location: auth/login.php");
        exit();
    }

    $customer_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Check product availability
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock_quantity);
    $stmt->fetch();
    $stmt->close();

    if($quantity > $stock_quantity){
        $_SESSION['error'] = "Not enough stock available.";
        header("Location: product.php?id=".$product_id);
        exit();
    }

    // Reduce stock
    $new_stock = $stock_quantity - $quantity;
    $stmt = $conn->prepare("UPDATE products SET stock_quantity=? WHERE product_id=?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    $stmt->execute();
    $stmt->close();

    // Create order
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $customer_id, $product_id, $quantity);
    if($stmt->execute()){
        $_SESSION['success'] = "Purchase successful!";
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }
    $stmt->close();
    $conn->close();

    header("Location: customer/dashboard.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
