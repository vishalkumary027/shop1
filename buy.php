<?php
// Include database connection and getProductDetails function here
session_start();
require_once 'db.php';

include('header.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get product ID from URL parameter
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Function to get product details (use in both files)
function getProductDetails($product_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, name, price, description, image_path FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


// Fetch product details
$product = getProductDetails($product_id);

// If product not found, redirect to products page or show an error
if (!$product) {
    header("Location: index12.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy <?= htmlspecialchars($product['name']) ?></title>
    <style>
        .product-details {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .product-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .product-name {
            font-size: 24px;
            color: #333;
        }
        .product-price {
            font-size: 20px;
            color: #4CAF50;
            margin: 10px 0;
        }
        .product-description {
            color: #666;
            line-height: 1.6;
        }
        .confirm-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .confirm-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="product-details">
        <h1>Confirm Purchase</h1>
        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
        <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
        <p class="product-price">Price: Rs<?= number_format($product['price'], 2) ?></p>
        <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
        <form action="process_purchase.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" class="confirm-btn">Confirm Purchase</button>
        </form>
    </div>
</body>
</html>