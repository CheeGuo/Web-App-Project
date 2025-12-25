<?php
session_start();
include("include/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $pass = $_POST["password"];
    $bool = False ;
    $statement = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $statement->bind_param("s", $username);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows === 1) {
        $bool = True ; 
        $statement->bind_result($hashed_password);
        $statement->fetch();

        if (password_verify($pass, $hashed_password)) {
            $_SESSION["username"] = $username;
            header("location:index.php");
            exit();
        } else {
          if($bool)
            $error = "Wrong password";
        }
    } else {
      if($bool)
        $error = "Wrong username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Arngren</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
  <div><br></div>

  <div class="login-container">
    <div class="login-card">
      <div class="login-title">
        <img src="pic/arngren logo.png">
        <h2>Log In</h2>
      </div>

      <form method="POST">
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <input class="login-btn" type="submit" value="Login">
      </form>

      <?php if (isset($error)) echo "<p>$error</p>"; ?>

      <div class="login-links">
        <a href="#">Forgot password?</a>
        <a href="signup.php">New user?</a>
      </div>
    </div>
  </div>

<?php include("include/footer.php"); ?>
</body>
</html>
