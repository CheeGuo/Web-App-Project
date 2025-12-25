<?php
include("include/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $token = bin2hex(random_bytes(32));
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $expires = date("Y-m-d H:i:s", time() + 900);

        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_id, $token_hash, $expires);
        $stmt->execute();

        echo "Reset link sent:<br>";
        echo "<a href='reset_password.php?token=$token'>Reset Password</a>";
        exit();
    }

    echo "If this email exists, a reset link will be sent.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
<div class="login-container">
  <div class="login-card">
    <h2>Forgot Password</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter registered email" required>
      <input class="button_global" type="submit" value="Send Reset Link">
    </form>
  </div>
</div>
</body>
</html>

<?php include("include/footer.php"); ?>
