 <?php
  include 'db.php';
// by default, error messages are empty
$valid=$fnameErr=$mobilenoErr=$emailErr=$passErr=$cpassErr='';
// by default,set input values are empty
$set_firstName=$set_mobileno=$set_email='';    
 extract($_POST);
if(isset($_POST['register']))
{
   
   //input fields are Validated with regular expression
   $validName="/^[a-zA-Z ]*$/";
   $validMobileno="/^[6-9][0-9]{9}$/S";
   $validEmail="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
   $uppercasePassword = "/(?=.*?[A-Z])/";
   $lowercasePassword = "/(?=.*?[a-z])/";
   $digitPassword = "/(?=.*?[0-9])/";
   $spacesPassword = "/^$|\s+/";
   $symbolPassword = "/(?=.*?[#?!@$%^&*-])/";
   $minEightPassword = "/.{6,}/";
 //  First Name Validation
if(empty($fname)){
   $fnameErr="Full Name is Required"; 
}
else if (!preg_match($validName,$fname)) {
   $fnameErr="Digits are not allowed";
}else{
   $fnameErr=true;
}
//  Last Name Validation
if(empty($mobileno)){
   $mobilenoErr="Mobile No is Required"; 
}
else if (!preg_match($validMobileno,$mobileno)) {
   $mobilenoErr="Please enter the valid mobile no.";
}
else{
   $mobilenoErr=true;
}
//Email Address Validation
if(empty($email)){
  $emailErr="Email is Required"; 
}
else if (!preg_match($validEmail,$email)) {
  $emailErr="Invalid Email Address";
}
else{
  $emailErr=true;
}
    
// password validation 
if(empty($password)){
  $passErr="Password is Required"; 
} 
elseif (!preg_match($uppercasePassword,$password) || !preg_match($lowercasePassword,$password) || !preg_match($digitPassword,$password) || !preg_match($symbolPassword,$password) || !preg_match($minEightPassword,$password) || preg_match($spacesPassword,$password)) {
  $passErr="Password must be at least one uppercase letter, lowercase letter, digit, a special character with no spaces and minimum 6 length";
}
else{
   $passErr=true;
}
// form validation for confirm password
if($cCpassword!=$password){
   $cpassErr="Confirm Password doest Matched";
}
else{
   $cpassErr=true;
}
// check all fields are valid or not
if($fnameErr==1 && $mobilenoErr==1 && $emailErr==1 && $passErr==1 && $cpassErr==1)
{
   $valid="All fields are validated successfully";
   
   //legal input values
   $fname= legal_input($fname);
   $mobileno=  legal_input($mobileno);
   $email=  legal_input($email);
   $password=  legal_input($password);
   $address=  legal_input($address);
   // here you can write Sql Query to insert user data into  database table
   //echo("$fname,$mobileno");

   
  $sql="INSERT INTO `users`(`fname`, `mobileno`, `email`, `password`, `address`) VALUES ('$fname','$mobileno','$email','$password','$address')";
    
  mysqli_query($conn,$sql);
   $_SESSION['user_id']=$email;
  // $_SESSION['']="you are logged in";
   header('location: login.php');

   
  /*  if ($conn->query($sql) === TRUE) {
       echo "New record created successfully";
   } else {
      
       echo "Error: " . $sql . "<br>" . $conn->error;
   } */
   
   $conn->close();
}else{
     // set input values is empty until input field is invalid
    $set_firstName=$fname;
    $set_mobileno= $mobileno;
    $set_email=    $email;
}
}
// convert illegal input value to ligal value formate
function legal_input($value) {
  $value = trim($value);
  $value = stripslashes($value);
  $value = htmlspecialchars($value);
  return $value;
}
?>