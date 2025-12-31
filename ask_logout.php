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
      <img src="pic/arngren logo.png" alt="Arngren Logo">
      <span>Do you wish to logout?</span>
    </div>
      <button onclick="window.location.href='logout.php';" class="login-btn" style="outline: none;">Yes</button>
      <button onclick="javascript:history.back();" class="login-btn" style="outline: none;">No</button>
    </div>
    </div>
</body>
</html>
<?php
 include("include/footer.php"); 
?>
