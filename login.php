<?php
session_start();
require_once 'db.php'; // Assume this file contains database connection details

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, password FROM users WHERE email = '$email' and password = '$password'";

    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
         
       $_SESSION['user_id']= $email;
       print_r(
        $_SESSION
       );
       header("Location: index12.php"); // Redirect to home page after login
        exit();
       
    } else {
        echo "Username or password is wrong";
        exit();
    }
    

    $conn->close();
}
?>

<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shopping Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f4f4f4;
        }
         .footer {
            background-color: #f8f9fa;
            color: black;
            padding: 20px 0;
            text-align: center;
        }
        .main-nav {
            display: flex;
            justify-content: center;
            list-style-type: none;
            padding: 0;
        }
        .main-nav li {
            margin: 0 15px;
        }
        .main-nav a {
            color: white;
            text-decoration: none;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0e3959;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #0e3959;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .form-group input[type="submit"] {
            background-color: #0e3959;
            color: white;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0a2d47;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    

    <div class="login-container">
        <h2>User Login</h2>
        <?php
        if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <p style="text-align: center; margin-top: 15px;">
        Does not have an account? <a href="register.php">Register here</a>
      </p>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Shopping Portal. All rights reserved.Only <a href="login1.php">Admin Login</a></p>
    </footer>
</body>
</html>