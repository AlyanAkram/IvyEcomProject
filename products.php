<?php 
session_start();
include 'db.php'; // Include database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="products.css"> <!-- Link to products.css -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <title>Products - BTech IT Store</title>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <h2>All Products</h2>
    <div class="product-list">
    <?php
    $result = $conn->query("SELECT * FROM products");
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-card">
                  <div style="position: relative; overflow: hidden; border-radius: 8px;">
                      <img src="'.$row['image'].'" alt="'.$row['name'].'">
                  </div>
                  <h3>'.$row['name'].'</h3>
                  <p>'.$row['description'].'</p>
                  <p class="price">$'.$row['price'].'</p>';
        
        echo '<form class="add-to-cart-form" data-product-id="'.$row['id'].'">
                <input type="hidden" name="product_id" value="'.$row['id'].'">
                <input type="submit" value="Add to Cart">
              </form>';
        echo '</div>';
    }
    ?>
    </div>
</main>

<?php include 'footer.php'; ?>

<!-- JavaScript to handle AJAX form submission -->
<script>
$(document).ready(function() {
    $('.add-to-cart-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        const productId = $(this).data('product-id'); // Get product ID from data attribute

        $.ajax({
            url: 'cart.php', // URL to send the request to
            type: 'POST',
            data: { product_id: productId, _: new Date().getTime() }, // Add timestamp to the request
            success: function(response) {
                const result = JSON.parse(response); // Parse the JSON response
                $('.cart-count').text(result.cartCount); // Update cart count in header
                alert('Item added to cart!'); // Notify user that item was added
            },
            error: function(xhr, status, error) {
                console.error('Error adding item to cart:', error);
            }
        });
    });
});
</script>


</body>
</html>
