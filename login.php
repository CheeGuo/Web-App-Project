<?php
session_start();
include("include/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $pass = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, username, password, profile_pic FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $db_username, $hashed_password, $profile_pic);
        $stmt->fetch();

        if (password_verify($pass, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $db_username;
            $_SESSION["profile_pic"] = $profile_pic ?: "pic/default-avatar.png";
            header("Location: index.php");
            exit();
        } else {
            $error = "Wrong password";
        }
    } else {
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

<div class="login-container">
  <div class="login-card">
    <div class="login-title">
      <a href="index.php">
        <img src="pic/arngren logo.png">
      </a>
      <h2>Log In</h2>
    </div>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input class="login-btn" type="submit" value="Login">
    </form>

    <?php if (isset($error)) echo "<p>$error</p>"; ?>

    <div class="login-links">
      <a href="reset_password.php">Forgot password?</a>
      <a href="signup.php">New user?</a>
    </div>
  </div>
</div>

<?php include("include/footer.php"); ?>

</body>
</html>
