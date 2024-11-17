<?php
require_once 'includes/db.php';
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Home - Winny Thrift Shop</title>
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>

    <body>
        <?php include 'includes/header.php'; ?>

        <h1>Welcome to Winny Thrift Shop</h1>
        <div class="search-filter">
            <input type="text" id="search" placeholder="Search products...">
            <select id="category">
            <option value="">All Categories</option>
            <option value="clothing">Clothing</option>
            <option value="accessories">Accessories</option>
            <option value="shoes">Shoes</option>
            <!-- Add more categories as needed -->
        </select>
            <button onclick="filterProducts()">Filter</button>
        </div>

        <div class="products">
            <?php
            $sql = "SELECT products.*, users.username FROM products JOIN users ON products.seller_id = users.user_id";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<div class='product'>
                            <img src='assets/images/".$row['image']."' alt='".$row['name']."'>
                            <h3>".$row['name']."</h3>
                            <p>".$row['description']."</p>
                            <p>Price: $".$row['price']."</p>
                            <p>Seller: ".$row['username']."</p>
                            <a href='product.php?id=".$row['product_id']."'>View Details</a>
                          </div>";
                }
            } else {
                echo "<p>No products available.</p>";
            }
        ?>
        </div>

        <?php include 'includes/footer.php'; ?>

        <script src="assets/js/scripts.js"></script>
    </body>

    </html>