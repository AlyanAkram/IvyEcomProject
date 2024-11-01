<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit();
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];

    // Handle image upload
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
    $image_tmp = $_FILES['product_image']['tmp_name'];
    $image_name = basename($_FILES['product_image']['name']);
    $upload_dir = 'uploads/'; // Ensure this directory exists and is writable

    // Check if uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true); // Create directory if it doesn't exist
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($image_tmp, $upload_dir . $image_name)) {
        $image_path = $upload_dir . $image_name;

        // Add new product to the database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image_path);
        if ($stmt->execute()) {
            $success_message = "Product added successfully!";
        } else {
            $error_message = "Error adding product: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_code = $_FILES['product_image']['error']; // Get error code
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                $error_message = "Uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $error_message = "Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_message = "Uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error_message = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error_message = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $error_message = "A PHP extension stopped the file upload.";
                break;
            default:
                $error_message = "Unknown upload error.";
                break;
        }
    }
} else {
    $error_message = "Error with image file. Error Code: " . $_FILES['product_image']['error'];
}

}

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];

    // Delete product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $success_message = "Product deleted successfully!";
    } else {
        $error_message = "Error deleting product: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all products to display
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Manage Products</h2>

        <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

        <!-- Add Product Form -->
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <h3>Add New Product</h3>
            <label for="product_name">Name:</label>
            <input type="text" name="product_name" id="product_name" required>
            
            <label for="product_description">Description:</label>
            <textarea name="product_description" id="product_description" required></textarea>
            
            <label for="product_price">Price:</label>
            <input type="number" step="0.01" name="product_price" id="product_price" required>
            
            <label for="product_image">Image:</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" required>
            
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <!-- Display Products -->
        <h3>Existing Products</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100"></td>
                <td>
                    <a href="admin.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
