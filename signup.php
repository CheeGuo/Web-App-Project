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

  <input type="text" name="username" placeholder="Username">

  <input type="email" name="email" placeholder="E-mail">

  <input type="text" name="fullname" placeholder="Full Name">

  <input type="text" name="gender" placeholder="Gender">

  <input type="tel" name="phone" placeholder="Phone No">

  <input type="password" name="password" placeholder="Password">

  <input type="date" name="registered_date">

  <input type="submit" name="submit" value="Confirm">

</form>


    <button class="login-btn">Login</button>  <! Should be a link>
  </div>
</div>

</body>
</html>
<?php include("footer.php"); ?>

