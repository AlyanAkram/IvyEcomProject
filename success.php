<?php session_start(); ?>
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Order Successful - BTech IT Store</title>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h2>Order Successful!</h2>
    <p>Thank you for your purchase! Your order has been placed successfully.</p>
    <p>Your order details have been sent to your email.</p>
    <a href="index.php">Return to Home</a>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
