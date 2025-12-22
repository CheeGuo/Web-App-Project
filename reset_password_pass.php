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
      <h2 style="text-align:left">Reset Password</h2>
    </div>
    <label class = "registeredPassword"> Enter new password </label>
    <form name="reset_password" method="POST"> 
    <input type="password" name ="password" placeholder="Password">
    <input class="button_global" type="submit" name="submit_password" value="Confirm"> <! should be connect to email >
    </form>
  </div>
</div>

</body>
</html>

<?php include("include/footer.php"); ?>
