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
    <script>
        function formatCardNumber(input) {
            // Remove non-digit characters
            let value = input.value.replace(/\D/g, '');
            // Format as "XXXX-XXXX-XXXX-XXXX"
            let formatted = value.replace(/(.{4})/g, '$1-').trim();
            // Remove trailing dash if present
            if (formatted.endsWith('-')) {
                formatted = formatted.slice(0, -1);
            }
            input.value = formatted;
        }

        function formatPhoneNumber(input) {
            // Remove non-digit characters
            let value = input.value.replace(/\D/g, '');
            // Format as "XXXX-XXXXXXX"
            if (value.length > 4) {
                input.value = value.slice(0, 4) + '-' + value.slice(4, 11);
            } else {
                input.value = value;
            }
        }

        function formatExpiry(input) {
            // Remove non-digit characters
            let value = input.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.slice(0, 4); // Limit to 4 characters
            }
            // Format as "MM/YY"
            if (value.length >= 3) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            input.value = value; // Update the input value
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h2>Checkout</h2>
    <div class="checkout-container">
        <h3>Your Order Summary</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
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
                <td>$<?php echo number_format($total_cost, 2); ?></td>
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

            <label for="phone">Phone Number (XXXX-XXXXXXX):</label>
            <input type="text" id="phone" name="phone" required oninput="formatPhoneNumber(this);" placeholder="XXXX-XXXXXXX">

            <h3>Payment Information</h3>
            <label for="cardholder">Cardholder Name:</label>
            <input type="text" id="cardholder" name="cardholder" required>

            <label for="cardnumber">Card Number:</label>
            <input type="text" id="cardnumber" name="cardnumber" required maxlength="19" oninput="formatCardNumber(this)" placeholder="XXXX-XXXX-XXXX-XXXX">

            <label for="expiry">Expiration Date (MM/YY):</label>
            <input type="text" id="expiry" name="expiry" required maxlength="5" placeholder="MM/YY" oninput="formatExpiry(this)">

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>

            <button type="submit">Complete Purchase</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
