<?php
include('header.php');
?>


<?php
session_start();
require_once 'db.php'; // Assume this file contains database connection details



// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact_messages (first_name, last_name, email, mobile, message) 
            VALUES ('$firstName', '$lastName', '$email', '$mobile', '$message')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Message sent successfully!";
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shopping Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        .contactUs {
            background: linear-gradient(90deg,#0e3959 0%,#0e3959 30%,#03a9f5 30%,#03a9f5 100%);
            position: relative;
            width: 100vw;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .contactUs a span {
            position: absolute;
            top: 0;
            right: 0;
            width: 45px;
            height: 45px;
            background: black;
            font-size: 2em;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom-left-radius: 20px;
            cursor: pointer;
            z-index: 1;
        }
        .contactUs .title {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
        }
        .contactUs .title h2 {
            color: #fff;
            font-weight: 500;
        }
        .form {
            grid-area: form;
        }
        .info {
            grid-area: info;
        }
        .map {
            height: 40vh;
            width: 30vw;
            grid-area: map;
        }
        .contact {
            padding: 40px;
            background: #fff;
            box-shadow: 0 5px 35px rgba(0, 0, 0, 0.15);
        }
        .box {
            position: relative;
            display: grid;
            grid-template-columns: auto auto;
            grid-template-rows: auto auto;
            grid-template-areas: "form info" "form map";
            grid-gap: 20px;
            margin-top: 20px;
        }
        .contact h3 {
            color: #0e3959;
            font-weight: 500;
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        /* form */
        .formBox {
            position: relative;
            width: 100%;
        }
        .formBox .row50 {
            display: flex;
            gap: 20px;
        }
        .inputBox {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            width: 50%;
        }
        .formBox .row100 .inputBox {
            width: 100%;
        }
        .inputBox span {
            color: #18b7ff;
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .inputBox input {
            padding: 10px;
            font-size: 1.1em;
            outline: none;
            border: 1px solid #333;
        }
        .inputBox textarea {
            padding: 10px;
            font-size: 1.1em;
            outline: none;
            border: 1px solid #333;
            resize: none;
            min-height: 220px;
            margin-bottom: 10px;
        }
        .inputBox input[type="submit"] {
            background: #ff578b;
            color: #fff;
            border: none;
            font-size: 1.1em;
            max-width: 120px;
            font-weight: 500;
            cursor: pointer;
            padding: 14px 15px;
        }
        .inputBox ::placeholder {
            color: #999;
        }
        /* info */
        .info {
            width: 30vw;
            background: #0e3959;
        }
        .info h3 {
            color: #fff;
        }
        .info .infoBox div {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .info .infoBox div span {
            min-width: 40px;
            height: 40px;
            color: #fff;
            background: #18b7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5em;
            border-radius: 50%;
            margin-right: 15px;
        }
        .info .infoBox div p {
            color: #fff;
            font-size: 1.1em;
        }
        .info .infoBox div a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
        }
        .sci {
            margin-top: 40px;
            display: flex;
        }
        .sci li {
            list-style: none;
            margin-right: 15px;
        }
        .sci li a {
            color: #fff;
            font-size: 2em;
            color: #ccc;
        }
        .sci li a:hover {
            color: #fff;
        }
        .map {
            padding: 0;
        }
        .map iframe {
            height: 100%;
            width: 100%;
        }
        
        /* Additional styles for header and footer */
         .footer {
            background-color: #0e3959;
            color: white;
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
    </style>
</head>
<body>
   

    <div class="contactUs">
        <div class="title">
            <h2>Get in Touch</h2>
        </div>
        <?php
        if (isset($successMessage)) {
            echo "<p style='color: green;'>$successMessage</p>";
        }
        if (isset($errorMessage)) {
            echo "<p style='color: red;'>$errorMessage</p>";
        }
        ?>
        <div class="box">
            <div class="contact form">
                <h3>Send a Message</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="formBox">
                        <div class="row50">
                            <div class="inputBox">
                                <span>First Name</span>
                                <input type="text" name="firstName" placeholder="First Name" required pattern="^[a-zA-Z ]*$">
                            </div>
                            <div class="inputBox">
                                <span>Last Name</span>
                                <input type="text" name="lastName" placeholder="Last Name" required pattern="^[a-zA-Z ]*$">
                            </div>
                        </div>
                        <div class="row50">
                            <div class="inputBox">
                                <span>Email</span>
                                <input type="email" name="email" placeholder="abc1@gmail.com" required>
                            </div>
                            <div class="inputBox">
                                <span>Mobile</span>
                                <input type="text" name="mobile" placeholder="+91 987 654 3210" required pattern="^(\+91[\-\s]?)?[6789]\d{9}$">
                            </div>
                        </div>
                        <div class="row100">
                            <div class="inputBox">
                                <span>Message</span>
                                <textarea name="message" placeholder="Write your message here..."></textarea>
                            </div>
                        </div>
                        <div class="row100">
                            <div class="inputBox">
                                <input type="submit" value="Send">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="contact info">
                <h3>Contact Info</h3>
                <div class="infoBox">
                    <div>
                        <span><ion-icon name="location"></ion-icon></span>
                        <p>Saswad, Pune, Maharashtra 412301<br>India</p>
                    </div>
                    <div>
                        <span><ion-icon name="mail-sharp"></ion-icon></span>
                        <a href="mailto:abc1@gmail.com">abc1@gmail.com</a>
                    </div>
                    <div>
                        <span><ion-icon name="call"></ion-icon></span>
                        <a href="tel:+919876543210">+91 987 654 3210</a>
                    </div>
                    <ul class="sci">
                        <li><a href="#"><ion-icon name="logo-facebook"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-twitter"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-linkedin"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-instagram"></ion-icon></a></li>
                    </ul>
                </div>
            </div>

            <div class="contact map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30295.518232721864!2d74.01242940850881!3d18.35003159346228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2ef88682aff03%3A0x8f1183045ce2366!2sSaswad%2C%20Maharashtra%20412301!5e0!3m2!1sen!2sin!4v1707137367702!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Shopping Portal. All rights reserved.</p>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>