<?php
require_once '../includes/auth_check.php';
checkRole('seller');
require_once '../includes/db.php';

// Handle product addition
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])){
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);
    $category = htmlspecialchars(trim($_POST['category']));
    $stock_quantity = intval($_POST['stock_quantity']);
    $seller_id = $_SESSION['user_id'];

    // Handle image upload
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        $uploadOk = 0;
    }

    if($uploadOk){
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = basename($_FILES["image"]["name"]);

        $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price, category, stock_quantity, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdis", $seller_id, $name, $description, $price, $category, $stock_quantity, $image);
        if($stmt->execute()){
            $_SESSION['success'] = "Product added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add product.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid image file.";
    }
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Winny Thrift Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h1>Seller Dashboard</h1>
    <?php
        if(isset($_SESSION['error'])) {
            echo "<p class='error'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])) {
            echo "<p class='success'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
    ?>

    <h2>Add New Product</h2>
    <form action="dashboard.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required><br/>
        <textarea name="description" placeholder="Product Description" required></textarea><br/>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br/>
        <input type="text" name="category" placeholder="Category" required><br/>
        <input type="number" name="stock_quantity" placeholder="Stock Quantity" required><br/>
        <input type="file" name="image" accept="image/*" required><br/>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Your Products</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php
            $seller_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT product_id, name, price, stock_quantity FROM products WHERE seller_id=?");
            $stmt->bind_param("i", $seller_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while($product = $result->fetch_assoc()){
                echo "<tr>
                        <td>".$product['name']."</td>
                        <td>$".$product['price']."</td>
                        <td>".$product['stock_quantity']."</td>
                        <td>
                            <a href='edit_product.php?id=".$product['product_id']."'>Edit</a> |
                            <a href='delete_product.php?id=".$product['product_id']."' onclick=\"return confirm('Are you sure?')\">Delete</a>
                        </td>
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
