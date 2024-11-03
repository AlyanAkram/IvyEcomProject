<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insert data into the messages table
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Thank you, $name! Your message has been received. We will get back to you shortly.";
    } else {
        $_SESSION['message'] = "An error occurred while submitting your message. Please try again.";
    }
    
    // Close the statement and redirect back to the contact page
    $stmt->close();
    header("Location: contact.php");
    exit();
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="contact.css">
    <title>Contact Us - IVY Roots Tech Shop</title>
</head>
<body>

<main>
    <h2>Contact Us</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <p class="success-message"><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); // Clear message after displaying ?>
    <?php endif; ?>

    <p>If you have any questions, feel free to reach out to us using the form below.</p>

    <form action="" method="POST" class="contact-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Submit</button>
    </form>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
