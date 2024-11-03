<?php
session_start();
include 'db.php'; // Include your database connection file
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="faq.css"> <!-- Link to the new FAQ CSS file -->
    <title>FAQ - IVY Roots Tech Shop</title>
</head>
<body>

<main>
    <h2>Frequently Asked Questions</h2>

    <div class="faq-item">
        <h3 class="faq-question">How can I track my order?</h3>
        <p class="faq-answer">You can track your order by logging into your account and visiting the "Orders" section.</p>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">What is the return policy?</h3>
        <p class="faq-answer">We offer a 30-day return policy for most products. Please refer to our <a href="returns.php">Returns Policy</a> for details.</p>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">How can I contact customer support?</h3>
        <p class="faq-answer">Contact us via the <a href="contact.php">Contact Us</a> page, or email us at support@ivyroots.com.</p>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">Do you offer international shipping?</h3>
        <p class="faq-answer">Yes, we ship internationally. Shipping charges may apply depending on your location.</p>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            answer.classList.toggle('active'); // Show/hide answer
            // Optional: Toggle the question style for a better user experience
            question.classList.toggle('active-question');
        });
    });
});
</script>



</html>
