<?php
session_start();
require_once 'db.php';

include('header.php');


// Initialize variables
$success_message = $error_message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    // Validate and sanitize input
    $product_name = filter_input(INPUT_POST, 'product_name');
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description');

    // Validate image
    $image_name = $_FILES['product_image']['name'];
    $image_temp = $_FILES['product_image']['tmp_name']; 
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if ($product_name && $price && $description && $image_name) {
        if (in_array($file_extension, $allowed_extensions) ) { 
            $upload_dir = 'uploads/';
            $image_path = $upload_dir . $product_name . '.' . $file_extension;

            if (move_uploaded_file($image_temp, $image_path)) {
                // Prepare the insert statement
                $insert_stmt = $conn->prepare("INSERT INTO products (name, price, description, image_path) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("sdss", $product_name, $price, $description, $image_path);

                if ($insert_stmt->execute()) {
                    $success_message = "Product added successfully!";
                } else {
                    $error_message = "Error adding product. Please try again.";
                }
                $insert_stmt->close();
            } else {
                $error_message = "Failed to upload image. Please try again.";
            }
        } else {
            $error_message = "Invalid file. Please upload a JPG, JPEG, PNG, or GIF file under 5MB.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>
        
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>

            <label for="description">Short Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required>

            <input type="submit" name="add_product" value="Add Product">
        </form>
    </div>
</body>
</html>