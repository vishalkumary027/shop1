<?php
session_start();
require_once 'db.php';
?>
<?php



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    //print_r($_SESSION['user_id']);
    if (!isset($_SESSION["user_id"])) {
        header('location: login.php');

    }
    $user_id = $_SESSION['user_id']; // You need to have the user's ID available
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    // print_r($_POST);
    addToCart($user_id, $product_id, $quantity);

    exit;
}

// Function to get product details (use in both files)
function getProductDetails($product_id)
{
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

// Function to add item to cart
function addToCart($user_id, $product_id, $quantity = 1)
{
    global $conn;

    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Check if the item is already in the cart
    $userquery = $conn->prepare("SELECT id FROM users WHERE email = ? ");
    $userquery->bind_param("i", $user_id);
    $userquery->execute();
    $userqueryresult = $userquery->get_result();
    $row = $userqueryresult->fetch_assoc();
    // print_r($row);
    $stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $row['id'], $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Update quantity if item exists
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $row['id']);
        $_SESSION['message'] = "Product quantity updated in the cart. New quantity: " . $new_quantity;
        header("Location: index12.php");
        // echo "<script>alert('product already added to cart');</script>";
    } else {
        // Insert new item if it doesn't exist
        $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $row['id'], $product_id, $quantity);
        $_SESSION['message'] = "Product added to cart successfully!";
        header("Location: index12.php");
        // echo `alert("Product added to cart $row['id'] ")`;
    }

    $stmt->execute();
    $stmt->close();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Portal</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        .header {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: #555;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #007bff;
        }

        .profile-link {
            display: flex;
            align-items: center;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .profile-link:hover {
            color: #007bff;
        }

        .profile-icon {
            font-size: 24px;
            margin-right: 8px;
        }

        /* Hero Section Styles */
        .hero {
            background-image: url('hero-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: #fff;
            text-align: center;
        }

        .hero-title {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-description {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .hero-button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .hero-button:hover {
            background-color: #0056b3;
        }

        /* Product Grid Styles */
        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            border-radius: 8px;
        }

        .product-card h2 {
            font-size: 1.5em;
            margin: 10px 0;
        }

        .price {
            color: #28a745;
            font-size: 1.2em;
            margin: 10px 0;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .add-to-cart,
        .buy-now {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin: 0 5px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #0056b3;
        }

        .buy-now {
            background-color: #28a745;
        }

        .buy-now:hover {
            background-color: #218838;
        }

        .message {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo">ShopPortal</a>
                <nav class="nav-menu">
                    <a href="index12.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link">About Us</a>
                    <a href="contact.php" class="nav-link">Contact Us</a>
                    <a href="cart.php" class="nav-link">Cart</a>
                    <a href="orders.php" class="nav-link">My Order</a>
                </nav>
                <a href="porfile.php" class="profile-link">
                    <i class="profile-icon">👤</i>
                    Profile
                </a>
            </div>
        </div>
    </header>

    <main>
        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Products</title>
            <link rel="stylesheet" href="styles.css">
        </head>

        <body>
            <header>
                <h1 style="text-align:center;">Our Products</h1>
            </header>
            <main>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message" id="message">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <script>
                        // Remove the message after 4 seconds
                        setTimeout(function () {
                            document.getElementById('message').style.display = 'none';
                        }, 4000);
                    </script>
                    <?php unset($_SESSION['message']); // Clear the message after displaying ?>
                <?php endif; ?>
                <div class="product-container">

                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="product-card">
                                <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                                <h2><?php echo $row['name']; ?></h2>
                                <p class="price">Rs<?php echo number_format($row['price'], 2); ?></p>
                                <p><?php echo $row['description']; ?></p>
                                <div class="button-container">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <input type="hidden" name="user_id"
                                            value="<?php isset($_SESSION["user_id"]) ? htmlspecialchars($_SESSION["user_id"]) : "" ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $row["id"]; ?>">
                                        <input type="submit" class="add-to-cart" name="add_to_cart" value="Add to Cart">
                                    </form>
                                    <a href="buy.php?product_id=<?= $row["id"]; ?>" class="buy-now">Buy Now</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No products available.</p>
                    <?php endif; ?>
                </div>
            </main>
            <footer style="text-align:center;">
                <p>&copy; 2024 Your Company</p>
            </footer>
        </body>

        </html>

        <?php $conn->close(); ?>