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

      <input type="text" placeholder="Username">
      <input type="password" placeholder="Password">

      <div class="login-links">
        <span>Forgot password?</span> <! Should be a link>
        <a href="signup.php">New user?</a>
      </div>

      <button class="login-btn">Login</button>  <! Should be a link>
    </div>
  </div>
  <?php include("include/footer.php"); ?>
</body>
</html>
<?
include("include/db.php")
?>