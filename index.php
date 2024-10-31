<?php session_start(); ?>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>IVY Roots Tech</title>
</head>
<body>
    <header>
        <h1>BTech IT Store</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
                <a href="cart.php">Cart</a>
                <a href="index.php">Home</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h2>Products</h2>
        <div class="product-list">
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="'.$row['image'].'" alt="'.$row['name'].'">';
                echo '<h3>'.$row['name'].'</h3>';
                echo '<p>'.$row['description'].'</p>';
                echo '<p>$'.$row['price'].'</p>';
                echo '<form action="cart.php" method="POST">';
                echo '<input type="hidden" name="product_id" value="'.$row['id'].'">';
                echo '<input type="submit" value="Add to Cart">';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> IVY Roots, Department of BTech IT Level 3. All rights reserved.</p>
    </footer>
</body>
</html>
