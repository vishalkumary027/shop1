<?php
// Database connection
session_start();
require_once 'db.php';

include('header.php');

// Validate input
$user_email = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fname, email, address,mobileno FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$product_id = intval($_POST['product_id']);

$stmt = $conn->prepare("INSERT INTO orders (product_id, customer_name, customer_email, shipping_address,mobileno) VALUES (?, ?, ?, ?,?)");
$stmt->bind_param("issss", $product_id, $user['fname'], $user['email'], $user['address'],$user['mobileno']);
//$r=$stmt->execute();
//print_r($r);


// Execute the statement
if ($stmt->execute()) {
    echo "<div class='container'>";
    echo "<h2>Thank you for your purchase!</h2>";
    echo "<p>Product ID: " . htmlspecialchars($product_id) . "</p>";
    echo "<p>Name: " . htmlspecialchars($user['fname']) . "</p>";
    echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
    echo "<p>Mobile No: " . htmlspecialchars($user['mobileno']) . "</p>";
    echo "<p>Address: " . htmlspecialchars($user['address']) . "</p>";
    echo "<div class='return'>";
    echo"<a href='index12.php'>Return to Products</a>";
    echo"</div>";
    echo "</div>";
} else {
    echo "Error: " . $stmt->error;
}


// Close connections
$stmt->close();
$conn->close();
?>
<html>
    <head>
        <link rel="stylesheet" href="process_purchase.css">
    </head>
</html>

