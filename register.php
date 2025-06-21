<?php
include('db.php');
?>

<?php
include('header.php');
?>

<?php
include('script.php');
?>
<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="register.css">
</head>

<body>



  <!--====registration form====-->
  <div class="container1">
    <div class="container">
      <h2 class="text-center">Create a New Account</h2>

      <?php echo $valid; ?></p>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!--//first name//-->

        <label for="fname">Full Name</label>

        <input type="text" placeholder="Enter Full Name" id="fname" name="fname" value="<?php echo $set_firstName; ?>">
        <p class="err-msg">

          <?php if ($fnameErr != 1) {
            echo $fnameErr;
          } ?>
        </p>


        <label for="mobileno">Mobile No.:</label>

        <input type="text" placeholder="Enter Mobile No" id="mobileno" name="mobileno"
          value="<?php echo $set_mobileno; ?>">
        <p class="err-msg">

          <?php if ($mobilenoErr != 1) {
            echo $mobilenoErr;
          } ?>
        </p>


        <!--// Email//-->

        <label for="email">Email:</label>
        <input type="text" id="email" placeholder="Enter email" class="email" name="email"
          value="<?php echo $set_email; ?>">
        <p class="err-msg">
          <?php if ($emailErr != 1) {
            echo $emailErr;
          } ?>
        </p>



        <!--//Password//-->

        <label for="password">Password:</label>
        <input type="password" placeholder="Enter password" id="password" name="password">
        <p class="err-msg">

          <?php if ($passErr != 1) {
            echo $passErr;
          } ?>
        </p>

        <!--//Confirm Password//-->

        <label for="cCpassword">Confirm Password:</label>


        <input type="password" placeholder="Enter Confirm password" id="cCpassword" name="cCpassword">
        <p class="err-msg">

          <?php if ($cpassErr != 1) {
            echo $cpassErr;
          } ?>
        </p>

        <label for="address">Address:</label>
        <textarea id="address" name="address" placeholder="Enter your address"
          rows="3"><?php echo isset($set_address) ? htmlspecialchars($set_address) : ''; ?></textarea>
        <p class="err-msg">
          <?php if (isset($addressErr) && $addressErr != 1) {
            echo $addressErr;
          } ?>
        </p>


        <button type="submit" class="btn btn-danger" value="Register" name="register">Register Now</button>
        <p style="text-align: center; margin-top: 15px;">
          Already have an account? <a href="login.php">Login here</a>
        </p>
      </form>

    </div>
  </div>





</body>

</html>