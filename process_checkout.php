<?php 
session_start(); 
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Gather input data from the form
$user_id = $_SESSION['user_id'];
$address = $_POST['address'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$phone = $_POST['phone']; // New phone number input
$cardholder = $_POST['cardholder'];
$cardnumber = $_POST['cardnumber'];
$expiry = $_POST['expiry'];
$cvv = $_POST['cvv'];

// Calculate total cost from the session cart
$total_cost = 0;
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. Please add items to your cart before checking out.</p>";
    exit();
}

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $product = $result->fetch_assoc();
    $total_cost += $product['price'] * $quantity;
}

// Prepare the SQL statement to insert the order
$stmt = $conn->prepare("INSERT INTO orders (user_id, address, city, zip, phone, total_cost, cardholder, cardnumber, expiry, cvv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssss", $user_id, $address, $city, $zip, $phone, $total_cost, $cardholder, $cardnumber, $expiry, $cvv);

// Execute the statement and handle payment processing
if ($stmt->execute()) {
    // Payment processing (Mock API call)
    $response = file_get_contents('https://run.mocky.io/v3/c2ab122f-c1ae-4fce-9898-1d0f6a00481b');

    if ($response) {
        // Redirect to success page
        header("Location: success.php");
        exit();
    } else {
        // Handle payment failure
        echo "Payment failed. Please try again.";
    }
} else {
    // Handle SQL execution error
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
