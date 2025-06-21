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

// Get product IDs from POST request
if (isset($_POST['buy_all'])) {
    $product_ids = $_POST['products']; // This will be an array of product IDs
    $products = []; // Array to hold product details

    foreach ($product_ids as $product_id) {
        $product_details = getProductDetails($product_id);
        if ($product_details) {
            $products[] = $product_details; // Store valid product details
        }
    }

    // If no products found, redirect or show an error
    if (empty($products)) {
        header("Location: index12.php");
        exit();
    }
} else {
    header("Location: index12.php"); // Redirect if accessed without buying
}

// Function to get product details
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Purchase</title>
    <style>
        .product-details {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
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
        .button-container {
    display: flex;               /* Use flexbox for alignment */
    justify-content: center;     /* Center horizontally */
    align-items: center;         /* Center vertically (if needed) */
    height: 100%;                /* Ensure the container takes full height */
    
}

.confirm-btn {
    padding: 10px 20px;         /* Adjust padding as needed */
    background-color: #4CAF50;  /* Button color */
    color: white;                /* Text color */
    border: none;                /* No border */
    border-radius: 5px;         /* Rounded corners */
    cursor: pointer;             /* Pointer cursor */
    font-size: 20px;             /* Font size */
    width: 42vw;
    transition: background-color 0.3s; /* Transition for hover effect */
}

.confirm-btn:hover {
    background-color: #45a049;   /* Darker shade on hover */
}

    </style>
</head>
<body>
    <h1 style="text-align:center;">Confirm Your Purchases</h1>
    <form action="BuyMultiple.php" method="POST">
        <?php foreach ($products as $product): ?>
            <div class="product-details">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
                <p class="product-price">Price: Rs<?= number_format($product['price'], 2) ?></p>
                <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                <input type="hidden" name="product_id[]" value="<?= $product['id'] ?>"> <!-- Use an array to handle multiple products -->
            </div>
        <?php endforeach; ?>
        <div class="button-container">
    <button type="submit" class="confirm-btn">Confirm Purchase</button>
</div>

    </form>
</body>
</html>
