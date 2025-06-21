<?php
session_start();
?>
<?php
include('header.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Your Shopping Portal</title>
    <link rel="stylesheet" href="about.css"> <!-- Link to your CSS file -->
</head>
<body>
   

    <main>
        <section>
            <h2>Our Mission</h2>
            <p>
                Our mission is to empower consumers by offering a wide selection of quality products at competitive prices. 
                We believe that shopping should be easy, efficient, and enjoyable. 
                Whether you are looking for the latest gadgets, trendy apparel, or home essentials, 
                ShopPortal is your one-stop destination.
            </p>
        </section>

        <section>
            <h2>What We Offer</h2>
            <ul>
                <li><strong>Diverse Product Range:</strong> Explore a vast collection of products across various categories.</li>
                <li><strong>User-Friendly Interface:</strong> Our website is designed with you in mind for easy navigation.</li>
                <li><strong>Secure Transactions:</strong> We utilize advanced encryption technologies to protect your information.</li>
                <li><strong>Exceptional Customer Service:</strong> Our dedicated support team is here to assist you.</li>
            </ul>
        </section>

        <section>
            <h2>Our Values</h2>
            <ol>
                <li><strong>Integrity:</strong> Conducting business with honesty and transparency.</li>
                <li><strong>Customer Focus:</strong> Your satisfaction is our top priority.</li>
                <li><strong>Innovation:</strong> Embracing new ideas and technologies.</li>
                <li><strong>Community:</strong> Supporting local initiatives and giving back.</li>
            </ol>
        </section>

        <section>
            <h2>Join Us on Our Journey</h2>
            <p>
                As we continue to grow, we invite you to join us on this exciting journey. 
                Explore our platform, discover amazing products, and experience the convenience of online shopping with ShopPortal.
            </p>
            <p>Thank you for choosing us! If you have any questions or need assistance, please reach out through our contact page.</p>
        </section>

        <section>
            <h2>Stay Connected</h2>
            <p>
                Follow us on our social media channels for the latest updates, promotions, and special offers:
            </p>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Twitter</a></li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> ShopPortal. All Rights Reserved.</p>
    </footer>
</body>
</html>
