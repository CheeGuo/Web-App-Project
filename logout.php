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
      <span>You have logged out!</span>
    </div>
  </div>

</div>
</body>
</html>
<?php
 include("include/footer.php"); 
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();

?>
