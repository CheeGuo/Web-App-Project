<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Arngren</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="login-container">
  <div class="login-card">
    <div class="login-title">
      <img src="pic/arngren logo.png">
      <h2>Sign Up</h2>
    </div>
  
  <form method="POST" name="signup">
  <div class="input_form" >  
    <label >Username</label>
    <input type="text" name="username" placeholder="Username" required> 
    <label >Email</label>
    <input type="email" name="email" placeholder="E-mail"  required> 
    <label >Full Name</label>
    <input type="text" name="fullname" placeholder="Full Name" required> 
    <label >Phone number</label>
    <input type="tel" name="phone" placeholder="Phone No" required pattern="[0-9]{10,11}">
    <label >Password</label>
    <input type="password" name="password" placeholder="Password" required minlength="6" maxlength="8" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])\S{6,8}$" title="6-8 characters, 1 uppercase letter, 1 number, 1 special character, no spaces">
  </div>

  <label class="gender-option">
    <input type="radio" name="gender" value="Male" required>
    <span>Male</span>
    <input type="radio" name="gender" value="Female">
    <span>Female</span>
  </label>

  <input type="submit" name="submit" value="Confirm" required>
    <div class="gender-group">
</div>
</form>
  </div>
</div>
</body>
</html>
<?php 
include("include/footer.php"); 
include("include/db.php");
$bool = False ; 
if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["fullname"]) && isset($_POST["gender"]) && isset($_POST["phone"]) &&isset($_POST["password"])){
$bool = True ; 
$username = $_POST["username"];
$email = $_POST["email"];
$fullname = $_POST["fullname"];
$gender = $_POST["gender"];
$phone = $_POST["phone"];
$pass = $_POST["password"];
$date = date("Y-m-d H:i:s");
$role = "customer";

$pass = password_hash($pass,PASSWORD_DEFAULT);
$query = "SELECT COUNT(*) AS total FROM USERS";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);
$num = $row["total"];
$user_id = "A" . str_pad($num, 4, "0", STR_PAD_LEFT);

$query = "INSERT IGNORE INTO USERS
(user_id,username,name,gender,email,password,phone,address,role,date_registered)
VALUES (?, ? ,?, ?, ?, ?, ?, ?, ?,? )";

$stmt = mysqli_prepare($conn,$query);
$address = NULL ; 
mysqli_stmt_bind_param(
  $stmt ,
  "ssssssssss",
  $user_id,
  $username,
  $fullname,
  $gender , 
  $email , 
  $pass,
  $phone , 
  $address , 
  $role,
  $date 
);
mysqli_stmt_execute($stmt);
    $cart_id = "C" . str_pad($num, 4, "0", STR_PAD_LEFT);

    $stmt = $conn->prepare("
        INSERT INTO cart (cart_id, user_id)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ss", $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();

header("location:login.php");
}else if($bool) {
  echo "
  <script>
  alert('Something when wrong.Please try again');
  </script>
  " ;
}

?>