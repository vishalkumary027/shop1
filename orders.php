<?php
session_start();
require_once 'db.php'; // Database connection

include('header.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user's email from the session
$user_email = $_SESSION['user_id'];


// Fetch user details
$stmt = $conn->prepare("SELECT fname, email, address, mobileno FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch order details for the user
$stmt = $conn->prepare("
    SELECT o.id, o.product_id, p.name, p.price, o.order_date 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    WHERE o.customer_email = ?
");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$orderResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Simple CSS for demonstration */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .return {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .return:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Orders</h2>
    <p>Name: <?= htmlspecialchars($user['fname']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Mobile No: <?= htmlspecialchars($user['mobileno']) ?></p>
    <p>Address: <?= htmlspecialchars($user['address']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orderResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['name']) ?></td>
                <td><?= htmlspecialchars($order['price']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

   <div class="return"> <a href="index12.php">Return to Products</a></div>
</div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
