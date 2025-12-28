<?php
include('include/db.php');
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user address */
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

/* When confirm is clicked */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['shipping_address'] = $address;
    $_SESSION['shipping_method']  = 'Air Shipping';
    $_SESSION['shipping_fee']     = 12;

    header("Location: checkout.php");
    exit();
}
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
  <form method="POST">
    <?php if ($address): ?>
      <div class="address-box">
        <label class="address-item">
          <input type="radio" name="address" checked required>
          <strong><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($phone) ?>)</strong><br>
          <?= nl2br(htmlspecialchars($address)) ?>
        </label>
      </div>
      <button type="submit" class="add-btn">Confirm</button>
    <?php else: ?>
      <p style="color:red;">No address saved.</p>
    <?php endif; ?>

    <button type="button"
      class="add-btn"
      onclick="window.location.href='index_address.php'">
      Add
    </button>

  </form>
</div>

<?php include('include/footer.php'); ?>
</body>
</html>
