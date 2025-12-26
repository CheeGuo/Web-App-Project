<?php
include("include/db.php");

if (!isset($_GET["token"])) {
    die("Invalid request");
}

$token = $_GET["token"];

$stmt = $conn->prepare("SELECT user_id, token_hash, expires_at FROM password_resets");
$stmt->execute();
$result = $stmt->get_result();

$user_id = false;

while ($row = $result->fetch_assoc()) {
    if (password_verify($token, $row["token_hash"]) && strtotime($row["expires_at"]) > time()) {
        $user_id = $row["user_id"];
        break;
    }
}

if (!$user_id) {
    die("Token expired or invalid");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $new_pass, $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    echo "Password reset successful";
    
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
<div class="login-container">
  <div class="login-card">
    <h2>Reset Password</h2>
    <form method="POST">
      <input type="password" name="password" placeholder="New password" required>
      <input class="button_global" type="submit" value="Confirm">
    </form>
  </div>
</div>
</body>
</html>

<?php include("include/footer.php"); ?>
