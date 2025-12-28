<?php
include('include/db.php');
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   GET USER INFO
========================= */
$stmt = $conn->prepare("
    SELECT name, email, address
    FROM users
    WHERE user_id = ?
");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* =========================
   GET LATEST PAYMENT
========================= */
$stmt = $conn->prepare("
    SELECT p.payment_id, p.order_id, p.total_amount, p.payment_method, p.payment_date
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    WHERE c.user_id = ?
    ORDER BY p.payment_date DESC
    LIMIT 1
");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$payment) {
    die("No receipt found.");
}

/* =========================
   GET PAYMENT ITEMS
========================= */
$stmt = $conn->prepare("
    SELECT pr.product_name, pi.quantity, pi.subtotal
    FROM payment_item pi
    JOIN product pr ON pi.product_id = pr.product_id
    WHERE pi.payment_id = ?
");
$stmt->bind_param("s", $payment['payment_id']);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

/* =========================
   SHIPPING (STATIC FOR NOW)
========================= */
$shipping_method = "Air Shipping";
$shipping_fee = 12.00;
$total_rm = $payment['total_amount'] + $shipping_fee;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Receipt</title>
<link rel="stylesheet" href="payment.css">
</head>

<body>

<div class="receipt-container">

  <h2 class="receipt-title">Receipt</h2>
  <p class="receipt-subtitle">Thank you for buying, your order has been placed.</p>

  <div class="receipt-box">

    <!-- LEFT -->
    <div class="receipt-left">
      <p><strong>Order ID</strong><br><?= htmlspecialchars($payment['order_id']) ?></p>
      <p><strong>Date</strong><br><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></p>

      <p><strong>Full Name</strong><br><?= htmlspecialchars($user['name']) ?></p>
      <p><strong>Email</strong><br><?= htmlspecialchars($user['email']) ?></p>

      <p><strong>Address</strong><br><?= nl2br(htmlspecialchars($user['address'])) ?></p>

      <p><strong>Payment Method</strong><br><?= htmlspecialchars($payment['payment_method']) ?></p>
      <p><strong>Shipping Method</strong><br><?= $shipping_method ?></p>
    </div>

    <!-- RIGHT -->
    <div class="receipt-right">
      <table class="receipt-table">
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
        </tr>

        <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= number_format($row['subtotal'],2) ?> kr</td>
          <td><?= $row['quantity'] ?></td>
        </tr>
        <?php endwhile; ?>
      </table>

      <div class="receipt-summary">
        <p>Cart Total: <span><?= number_format($payment['total_amount'],2) ?> kr</span></p>
        <p>Shipping Fee: <span><?= number_format($shipping_fee,2) ?> kr</span></p>
        <h3>Total (RM): <span>RM <?= number_format($total_rm,2) ?></span></h3>
      </div>

      <div class="receipt-actions">
        <a href="index.php" class="btn done">Done viewing</a>
        <button onclick="window.print()" class="btn pdf">Download PDF</button>
      </div>
    </div>

  </div>
</div>

<?php include('include/footer.php'); ?>
</body>
</html>
