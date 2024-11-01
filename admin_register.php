<?php 
session_start();
include 'db.php'; // Make sure to include your database connection

// Handle admin registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert the new admin
    $stmt = $conn->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<p>New admin registered successfully!</p>";
        header("Location: admin.php"); // Redirect to admin dashboard
        exit(); // Stop further execution to ensure redirect
    } else {
        echo "<p>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register New Admin</title>
</head>
<body>
    <header>
        <h1>Register New Admin</h1>
        <nav>
            <a href="admin.php">Dashboard</a> <!-- Link to admin dashboard or another page -->
            <a href="logout.php">Logout</a> <!-- Link to admin logout -->
        </nav>
    </header>
    <main>
        <form action="admin_register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <button type="submit">Register Admin</button>
        </form>
    </main>
</body>
</html>
