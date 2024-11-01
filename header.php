<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize as an empty array
}

// Include database connection
include 'db.php';
?>
<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IVY Tech Shop</title>
    <link rel="stylesheet" href="header.css"> <!-- Link to your CSS file -->
</head>
<body>
<header class="header">
    <a href="index.php" class="site-logo">
        <img src="images/logo.png" alt="Ivy Tech Shop Logo" id="site-logo">
    </a>
    <nav class="nav">
        <a href="index.php" class="nav-link">Home</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="products.php" class="nav-link">Products</a>
            
            <!-- Cart link that redirects to the cart page -->
            <a href="cart.php" class="nav-link">Cart</a>
            
            <!-- Cart icon with item count next to it -->
            <div class="cart">
                <a href="#" class="cart-link">
                    <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                    <!-- ... Existing header code ... -->
<span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
<!-- ... Existing header code ... -->

                </a>
                <div id="cart-menu" class="cart-menu">
                    <h3>Your Cart</h3>
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        echo '<ul class="cart-items">';
                        foreach ($_SESSION['cart'] as $id => $quantity) {
                            $result = $conn->query("SELECT * FROM products WHERE id = $id");
                            if ($result) {
                                $product = $result->fetch_assoc();
                                echo '<li>' . htmlspecialchars($product['name']) . ' - $' . htmlspecialchars($product['price']) . ' (x' . $quantity . ')</li>';
                            } else {
                                echo '<li>Product not found.</li>'; // Handle query failure
                            }
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>Your cart is empty.</p>';
                    }
                    ?>
                </div>
            </div>

            <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link">Login</a>
            <a href="register.php" class="nav-link">Register</a>
        <?php endif; ?>
    </nav>
</header>

<!-- JavaScript for the cart menu -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartIcon = document.querySelector('.cart-link');
        const cartMenu = document.getElementById('cart-menu');

        // Toggle the display of the cart menu when the cart icon is clicked
        cartIcon.addEventListener('click', function (e) {
            e.preventDefault();
            cartMenu.style.display = cartMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close the cart menu if the user clicks outside of it
        window.addEventListener('click', function (event) {
            if (!cartMenu.contains(event.target) && !cartIcon.contains(event.target)) {
                cartMenu.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
