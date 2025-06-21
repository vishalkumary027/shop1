<?php
session_start();
require_once 'db.php';
?>
<?php
include('header.php');
?>

<?php

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Function to get cart items
function getCartItems($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT ci.id,p.image_path, p.name, p.price, ci.quantity, p.id  as product_id
                            FROM cart_items ci 
                            JOIN products p ON ci.product_id = p.id 
                            WHERE ci.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
       // print_r($row);
        $cart_items[] = $row;
    }
    
    $stmt->close();
    return $cart_items;
}

// Handle remove from cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $user_id = 1; // Replace with actual user ID (e.g., from session)
    $product_id = $_POST['product_id'];
    
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



// Get cart items
$user_id = 1; // Replace with actual user ID (e.g., from session)
$cart_items = getCartItems($user_id);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        .body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .cart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-details {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 4px;
        }
        .item-info {
            flex-grow: 1;
        }
        .item-name {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .item-price {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.1em;
        }
        .item-quantity {
            color: #7f8c8d;
            margin-top: 5px;
        }
        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .remove-btn:hover {
            background-color: #c0392b;
        }
        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .total-price {
            font-size: 1.4em;
            font-weight: bold;
            color: #2c3e50;
        }
        .buy-all-btn {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.1em;
        }
        .buy-all-btn:hover {
            background-color: #27ae60;
        }
        .empty-cart {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 40px 0;
        }
    </style>
</head>
<body>
   <div class="body">
<h1>Your Shopping Cart</h1>
    <div class="cart-container">
        <?php if (empty($cart_items)): ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php else: ?>
            <?php 
            $total = 0;
            foreach ($cart_items as $item): 
                $total += $item['price'] * $item['quantity'];
            ?>
                <div class="cart-item">
                    <div class="item-details">
                        <img class="item-image" src="<?php echo htmlspecialchars("http://localhost/shopping/".$item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="item-price">Rs<?= number_format($item['price'], 2) ?></div>
                            <div class="item-quantity">Quantity: <?= $item['quantity'] ?></div>
                        </div>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                        <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <div class="total">
                <div class="total-price">
                    Total: Rs<?= number_format($total, 2) ?>
                </div>
                <form method="POST" action="BuyAll.php">
        <?php foreach ($cart_items as $item): ?>
            <input type="hidden" name="products[]" value="<?= htmlspecialchars($item['product_id']) ?>"> <!-- or use $item['name'], etc. -->
        <?php endforeach; ?>
        <button type="submit" name="buy_all" class="buy-all-btn">Buy All</button>
    </form>
            </div>
        <?php endif; ?>
    </div>
    </div> 
</body>
</html>