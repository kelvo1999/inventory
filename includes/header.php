<?php
// Check if user is logged in to show appropriate navigation
?>
<header>
    <nav>
        <ul>
            <li><a href="/index.php">Home</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <li><a href="/admin/dashboard.php">Admin Dashboard</a></li>
                <?php elseif($_SESSION['role'] == 'seller'): ?>
                    <li><a href="/seller/dashboard.php">Seller Dashboard</a></li>
                <?php elseif($_SESSION['role'] == 'customer'): ?>
                    <li><a href="/customer/dashboard.php">My Dashboard</a></li>
                <?php endif; ?>
                <li><a href="/auth/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/auth/login.php">Login</a></li>
                <li><a href="/auth/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
