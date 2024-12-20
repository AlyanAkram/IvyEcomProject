<?php
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize it as an empty array
}

// Other header code...
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
    <button class="hamburger" onclick="toggleMenu()">&#9776;</button>
    <nav class="nav">
        <a href="index.php" class="nav-link">Home</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="products.php" class="nav-link">Products</a>
            <a href="cart.php" class="nav-link">Cart</a>
            <div class="cart">
                <a href="#" class="cart-link">
                    <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
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

<!-- JavaScript for the cart menu and hamburger toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartIcon = document.querySelector('.cart-link');
        const cartMenu = document.getElementById('cart-menu');
        const nav = document.querySelector('.nav');
        const hamburger = document.querySelector('.hamburger');

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

        // Function to handle hamburger menu toggle
        window.toggleMenu = function() {
            nav.style.display = nav.style.display === 'block' ? 'none' : 'block';
        };

        // Reset the navigation display on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 582) {
                nav.style.display = 'flex'; // Show nav links in large screen
            } else {
                nav.style.display = 'none'; // Hide nav links in small screen
            }
        });

        // Initialize nav display based on current window size
        if (window.innerWidth > 582) {
            nav.style.display = 'flex';
        } else {
            nav.style.display = 'none';
        }
    });
</script>

</body>
</html>
