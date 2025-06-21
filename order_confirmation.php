<?php
session_start();
require_once 'db.php';
include('header.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header("Location: index12.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders1 WHERE id = ? AND user_id = ?");
$stmt->bind_param("is", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header("Location: index12.php");
    exit();
}

$order = $order_result->fetch_assoc();
$stmt->close();

// Fetch order items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image_path FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
$order_items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .order-summary {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-items {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-name {
            font-weight: bold;
        }
        .item-price {
            color: #27ae60;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            font-size: 1.2em;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Order Confirmation</h1>
    
    <div class="order-summary">
        <h2>Order Summary</h2>
        <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
        <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
        <p><strong>Total Amount:</strong> Rs<?php echo number_format($order['total_amount'], 2); ?></p>
    </div>
    
    <div class="order-items">
        <h2>Order Items</h2>
        <?php foreach ($order_items as $item): ?>
            <div class="item">
                <img class="item-image" src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="item-details">
                    <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div>Quantity: <?php echo $item['quantity']; ?></div>
                </div>
                <div class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
            </div>
        <?php endforeach; ?>
        
        <div class="total">
            Total: Rs<?php echo number_format($order['total_amount'], 2); ?>
        </div>
    </div>
    
    <a href="index12.php" class="back-link">Back to Home</a>
</body>
</html>