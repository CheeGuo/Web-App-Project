<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'include/env.php';

$host = getenv('SMTP_HOST');
$port = getenv('SMTP_PORT');
$env_email = getenv('SMTP_EMAIL');
$password = getenv('SMTP_PASSWORD');

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

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

        $link = "http://localhost/Web-App-Project/reset_password_pass.php?token=$token";
        try{
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $env_email;
        $mail->Password = $password;
        $mail->SMTPSecure = "tls";
        $mail->Port = $port;

        $mail->setFrom($env_email, "Arngren Support");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Reset Your Password";
        $mail->Body = "Click the link below to reset your password:<br><br><a href='$link'>$link</a>";

        $mail->send();
        }catch(Exception $e){
          echo "
          <script>
          alert('Something when wrong ');
          </script>
          ";
        }
                     echo "
    <script>
    alert('If the email is exist , then the link will send it to your own email');
    window.location.href = 'index.php';
    </script>
    ";
    }


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
