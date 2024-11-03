<?php session_start(); 
include 'db.php'; // Include database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="success.css"> <!-- Link to the success CSS file -->
    <title>Payment Successful - BTech IT Store</title>
    <meta http-equiv="refresh" content="5;url=index.php"> <!-- Redirects after 5 seconds -->
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <div class="success-container">
        <h2>Payment Successful!</h2>
        <p>Thank you for your purchase! Your order has been processed successfully.</p>
        <p>You will be redirected to the homepage in a few seconds.</p>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
