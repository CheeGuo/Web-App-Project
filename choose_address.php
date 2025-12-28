<?php
include('include/db.php');
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT name, phone, address
    FROM users
    WHERE user_id = ?
");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($name, $phone, $address);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Select Shipping Address</title>
<link rel="stylesheet" href="payment.css">

</head>

<body>

<div class="address-page">
  <h2>Select shipping address</h2>

  <form method="post" action="checkout.php">
    <div class="address-box">

      <?php if ($address): ?>
        <div class="address-item">
          <input type="radio" name="use_address" value="1" checked required>

          <label>
            <strong><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($phone) ?>)</strong><br>
            <?= nl2br(htmlspecialchars($address)) ?>
          </label>
        </div>
      <?php else: ?>
        <p style="color:red;">No address saved.</p>
      <?php endif; ?>

      <button type="submit" class="add-btn">Confirm</button>

      <button type="button"
        class="add-btn"
        onclick="window.location.href='index_address.php'">
        Add
      </button>

    </div>
  </form>
</div>

<?php include('include/footer.php'); ?>
</body>
</html>
