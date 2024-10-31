<?php session_start(); ?>
<?php include 'db.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Initialize quantity to 1 if not in cart
    } else {
        $_SESSION['cart'][$product_id]++; // Increment quantity if already in cart
    }
}

// Handle quantity increase, decrease, and removal
if (isset($_GET['action'])) {
    $product_id = $_GET['product_id'];
    if ($_GET['action'] == 'increase') {
        $_SESSION['cart'][$product_id]++;
    } elseif ($_GET['action'] == 'decrease') {
        if ($_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        }
    } elseif ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$product_id]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Shopping Cart</title>
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
        <nav>
            <a href="logout.php">Logout</a>
            <a href="index.php">Home</a>
        </nav>
    </header>
    <main>
        <h2>Your Cart</h2>
        <?php
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo '<table>';
            echo '<tr><th>Product</th><th>Image</th><th>Price</th><th>Quantity</th><th>Total</th><th>Actions</th></tr>';
            
            $total_cost = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
                $product = $result->fetch_assoc();
                $product_total = $product['price'] * $quantity;
                $total_cost += $product_total;
                
                echo '<tr>';
                echo '<td>'.$product['name'].'</td>';
                echo '<td><img src="'.$product['image'].'" alt="'.$product['name'].'" width="50"></td>';
                echo '<td>$'.$product['price'].'</td>';
                echo '<td>'.$quantity.'</td>';
                echo '<td>$'.$product_total.'</td>';
                echo '<td>';
                echo '<a href="cart.php?action=decrease&product_id='.$product_id.'">-</a> ';
                echo '<a href="cart.php?action=increase&product_id='.$product_id.'">+</a> ';
                echo '<a href="cart.php?action=remove&product_id='.$product_id.'">Remove</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="4" style="text-align:right;">Total Cost:</td><td>$'.$total_cost.'</td></tr>';
            echo '</table>';
        }
        ?>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> IVY Roots, Department of BTech IT Level 3. All rights reserved. 2024-25</p>
    </footer>
</body>
</html>
