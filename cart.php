<?php session_start(); ?>
<?php include 'db.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
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
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $_SESSION['cart'][] = $product_id;
        }

        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is empty.</p>";
        } else {
            echo "<ul>";
            foreach ($_SESSION['cart'] as $id) {
                $result = $conn->query("SELECT * FROM products WHERE id = $id");
                $product = $result->fetch_assoc();
                echo '<li>'.$product['name'].' - $'.$product['price'].'</li>';
            }
            echo "</ul>";
        }
        ?>
    </main>
</body>
</html>
