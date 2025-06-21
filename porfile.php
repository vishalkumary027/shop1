<?php
session_start();
require_once 'db.php';

include('header.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_id']; // Assuming email is stored in session as 'user_id'

// Fetch user data from the database
$stmt = $conn->prepare("SELECT fname, email, mobileno, address FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Validate and sanitize input
    $fname = filter_input(INPUT_POST, 'fname' );
    $mobileno = filter_input(INPUT_POST, 'mobileno');
    $address = filter_input(INPUT_POST, 'address');

    if ($fname && $mobileno && $address) {
        // Prepare the update statement
        $update_stmt = $conn->prepare("UPDATE users SET fname = ?, mobileno = ?, address = ? WHERE email = ?");
        $update_stmt->bind_param("ssss", $fname, $mobileno, $address, $user_email);

        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $user['fname'] = $fname;
            $user['mobileno'] = $mobileno;
            $user['address'] = $address;
        } else {
            $error_message = "Error updating profile. Please try again.";
        }
        $update_stmt->close();
    } else {
        $error_message = "Invalid input. Please check your entries and try again.";
    }
}


if (isset($_POST['logout'])) {
    // Unset the user_id from the session
    unset($_SESSION['user_id']);
    
    // Optionally, you can destroy the entire session
    // session_destroy();
    
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Shopping Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .profile-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .profile-header {
            background-color: #3498db;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .profile-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #fff;
            margin: 0 auto 20px;
            display: block;
            object-fit: cover;
        }
        .profile-body {
            padding: 30px;
        }
        .profile-info {
            margin-bottom: 30px;
        }
        .info-group {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
        }
        .edit-profile-form label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            color: #666;
        }
        .edit-profile-form input[type="text"],
        .edit-profile-form input[type="email"],
        .edit-profile-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .edit-profile-form input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .edit-profile-form input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 500;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="./uploads/profile.jpg" alt="Profile Picture" class="profile-img">
                <h1><?php echo htmlspecialchars($user['fname']); ?></h1>
                <p>Shopping Portal Member</p>
            </div>
            
            <div class="profile-body">
                <?php if (isset($success_message)): ?>
                    <div class="message success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="profile-info">
                    <h2>Current Information</h2>
                    <div class="info-group">
                        <p class="info-label">Full Name</p>
                        <p class="info-value"><?php echo htmlspecialchars($user['fname']); ?></p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Email</p>
                        <p class="info-value"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Mobile No.</p>
                        <p class="info-value"><?php echo htmlspecialchars($user['mobileno']); ?></p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Address</p>
                        <p class="info-value"><?php echo htmlspecialchars($user['address']); ?></p>
                    </div>
                </div>

                <h2>Edit Profile</h2>
                <form class="edit-profile-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="fname">Full Name:</label>
                    <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label for="mobileno">Mobile No.:</label>
                    <input type="text" id="mobileno" name="mobileno" value="<?php echo htmlspecialchars($user['mobileno']); ?>" required>

                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>

                    <input type="submit" name="update_profile" value="Update Profile">
                    <button type="submit" name="logout" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>