<?php session_start(); ?>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Login</title>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form action="login.php" method="POST">
            <input type="text" name="identifier" placeholder="Email or Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identifier = $_POST['identifier']; // This can be either email or username
            $password = $_POST['password'];

            // Prepare statement to check both email and username
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: index.php"); // Redirect to the main page after login
                    exit();
                } else {
                    echo "<p>Invalid password.</p>";
                }
            } else {
                echo "<p>User not found.</p>";
            }
            $stmt->close();
        }
        ?>
    </main>
</body>
</html>
