<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Assuming the user ID is stored in the session
$user_id = $_SESSION['user_id'];

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. Please add items to your cart before checking out.</p>";
    exit();
}

// Calculate the total cost of the order
$total_cost = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $result = $conn->query("SELECT price FROM products WHERE id = $product_id");
    $product = $result->fetch_assoc();
    $total_cost += $product['price'] * $quantity;
}

// Capture the form data
$address = $_POST['address'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$phone = $_POST['phone']; // New phone number input
$cardholder = $_POST['cardholder'];
$cardnumber = $_POST['cardnumber'];
$expiry = $_POST['expiry'];
$cvv = $_POST['cvv'];

// Prepare the SQL statement to insert the order
$stmt = $conn->prepare("INSERT INTO orders (user_id, address, city, zip, phone, total_cost, cardholder, cardnumber, expiry, cvv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssss", $user_id, $address, $city, $zip, $phone, $total_cost, $cardholder, $cardnumber, $expiry, $cvv);

// Execute the statement
if ($stmt->execute()) {
    // Payment processing using Mocky API
    $paymentData = [
        'amount' => $total_cost,
        'currency' => 'USD',
        'cardholder' => $cardholder,
        'cardnumber' => $cardnumber,
        'expiry' => $expiry,
        'cvv' => $cvv
    ];

    // Convert the payment data to JSON
    $paymentJson = json_encode($paymentData);

    // Initialize cURL for payment processing
    $ch = curl_init('https://run.mocky.io/v3/c2ab122f-c1ae-4fce-9898-1d0f6a00481b');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $paymentJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($paymentJson)
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Handle payment response
    if ($httpCode == 200) {
        // Payment succeeded
        echo "<p>Payment successful! Your order has been placed.</p>";
        // Optionally, clear the cart or redirect to a success page
        unset($_SESSION['cart']); // Clear the cart after successful payment
        // header("Location: success.php"); // Redirect to a success page
    } else {
        // Payment failed
        echo "<p>Payment failed. Please try again.</p>";
    }
} else {
    // Handle SQL error
    echo "<p>There was an error processing your order. Please try again.</p>";
}

// Close the statement
$stmt->close();
$conn->close();
?>
