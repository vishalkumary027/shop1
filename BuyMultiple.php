<?php
// Database connection
session_start();
require_once 'db.php';

include('header.php');

// Validate input
$user_email = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fname, email, address, mobileno FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check if product_id is an array for multiple purchases
if (isset($_POST['product_id']) && is_array($_POST['product_id'])) {
    $product_ids = $_POST['product_id']; // Array of product IDs
    $success = true; // Flag to check overall success

    echo "<div class='container'>";
    echo "<h2>Thank you for your purchases!</h2>";

    // Prepare the SQL statement outside the loop for efficiency
    $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_name, customer_email, shipping_address, mobileno) VALUES (?, ?, ?, ?, ?)");

    foreach ($product_ids as $product_id) {
        $product_id = intval($product_id); // Ensure it's an integer

        // Bind parameters
        $stmt->bind_param("issss", $product_id, $user['fname'], $user['email'], $user['address'], $user['mobileno']);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<p>Product ID: " . htmlspecialchars($product_id) . "</p>";
        } else {
            $success = false; // Mark as failed if any insert fails
            echo "Error processing Product ID: " . htmlspecialchars($product_id) . ". " . $stmt->error . "<br>";
        }
    }

    if ($success) {
        echo "<p>All purchases were successfully processed.</p>";
    } else {
        echo "<p>Some purchases encountered errors. Please check the above messages.</p>";
    }

    echo "<a href='index12.php'>Return to Products</a>";
    echo "</div>";

    // Close the statement
    $stmt->close();
} else {
    echo "<div class='container'>";
    echo "<h2>No products selected for purchase.</h2>";
    echo "<a href='index12.php'>Return to Products</a>";
    echo "</div>";
}

// Close connections
$conn->close();
?>
<html>
    <head>
        <link rel="stylesheet" href="process_purchase.css">
    </head>
</html>
