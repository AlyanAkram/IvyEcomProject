<?php session_start(); ?>
<?php include 'db.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$total_cost = 0;
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. Please add items to your cart before checking out.</p>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="checkout.css">
    <title>Checkout - BTech IT Store</title>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h2>Checkout</h2>
    <div class="checkout-container">
        <h3>Your Order Summary</h3>
        <table>
            <tr><th>Product</th><th>Image</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
            <?php
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
                $product = $result->fetch_assoc();
                $product_total = $product['price'] * $quantity;
                $total_cost += $product_total;

                echo '<tr>';
                echo '<td>'.$product['name'].'</td>';
                echo '<td><img src="'.$product['image'].'" alt="'.$product['name'].'" width="100"></td>';
                echo '<td>$'.$product['price'].'</td>';
                echo '<td>'.$quantity.'</td>';
                echo '<td>$'.$product_total.'</td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <td colspan="4" style="text-align:right;">Total Cost:</td>
                <td>$<?php echo $total_cost; ?></td>
            </tr>
        </table>
        
        <form action="process_checkout.php" method="post">
            <h3>Shipping Information</h3>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="zip">ZIP Code:</label>
            <input type="text" id="zip" name="zip" required>

            <button type="submit">Complete Purchase</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
