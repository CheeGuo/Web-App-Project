<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Arngren</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
  <div>
    <br>
  </div>
  <div class="login-container">
    <div class="login-card">
      <div class="login-title">
        <img src="pic/arngren logo.png">
        <h2>Log In</h2>
      </div>
    <form name="login" method="POST">
      <input type="text" placeholder="Username" name="username">
      <input type="pass" placeholder="Password" name="password">
      <input class="login-btn" type="submit" value="Login">
    </form>
      <div class="login-links">
        <span>Forgot password?</span> <! Should be a link>
        <a href="signup.php">New user?</a>
      </div>  
    </div>
  </div>
</body>
</html>
<?php
include("include/footer.php");
include("include/db.php");
$username =$_POST["username"];
$password = password_hash($_POST["username"],PASSWORD_DEFAULT);

$_POST["password"];

?>