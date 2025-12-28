<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$shipping_address = $_SESSION['shipping_address'] ?? null;
$shipping_method  = $_SESSION['shipping_method'] ?? '-';
$shipping_fee     = $_SESSION['shipping_fee'] ?? 0;
$user_id = $_SESSION['user_id'];

/* ===== Fetch cart items ===== */
$sql = "
SELECT 
    ci.cartitem_id,
    p.product_name,
    p.price,
    ci.quantity
FROM cart c
JOIN cart_item ci ON c.cart_id = ci.cart_id
JOIN product p ON ci.product_id = p.product_id
WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

/* ===== Calculate total ===== */
$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

/* Example conversion NOK → RM (static rate for now) */
$rate = 0.41;
$total_rm = $total * $rate;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<link rel="stylesheet" href="payment.css">
</head>
<body>

<div class="checkout-container">

  <!-- LEFT SIDE -->
  <div class="checkout-left">
    <!-- STEP 1 -->
    <div class="checkout-box">
  <h3>① Select shipping address</h3>

  <?php if ($shipping_address): ?>
    <p><?= nl2br(htmlspecialchars($shipping_address)) ?></p>
  <?php else: ?>
    <p class="required">
      <a href="choose_address.php">*Select shipping address</a>
    </p>
  <?php endif; ?>

  <hr>

  <p><strong>Shipping Method:</strong> <?= $shipping_method ?></p>
  <p><strong>Estimated Shipping Fee:</strong> <?= $shipping_fee ?> kr</p>
</div>


    <!-- STEP 2 -->
    <div class="checkout-box">
      <h3>② Payment Method</h3>

      <div class="payment-options" method="POST">
        <label><input type="radio" name="payment" value="Credit/Debit"> Credit / Debit Card</label>
        <label><input type="radio" name="payment" value="Online Banking"> Online Banking</label>
        <label><input type="radio" name="payment" value="PayPal"> PayPal</label>
        <label><input type="radio" name="payment" value="Vipps"> Vipps</label>
      </div>
    </div>

  </div>

  <!-- RIGHT SIDE -->
  <div class="checkout-right">

    <h3>③ Order review</h3>

    <table class="review-table">
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
      </tr>

      <?php foreach ($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= number_format($item['price'],2) ?> kr</td>
        <td><?= $item['quantity'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div class="summary">
      <p>Cart Total: <span><?= number_format($total,2) ?> kr</span></p>
      <p>Shipping Fee: <span>-</span></p>
      <h4>Total (RM): <span>RM <?= number_format($total_rm,2) ?></span></h4>
    </div>

    <div class="checkout-actions">
      <a href="index.php" class="btn cancel">Cancel</a>
      <form action="confirm_order.php" method="POST" style="display:inline;">
        <button class="btn confirm">Confirm</button>
      </form>
    </div>

  </div>

</div>

<?php 
include('include/footer.php');

?>
</body>
</html>

<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===============================
   1. Get user's cart
================================ */
$sql = "
SELECT 
    c.cart_id,
    ci.product_id,
    ci.quantity,
    p.price
FROM cart c
JOIN cart_item ci ON c.cart_id = ci.cart_id
JOIN product p ON ci.product_id = p.product_id
WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cart is empty");
}

$cart_items = [];
$total = 0;
$cart_id = null;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $cart_id = $row['cart_id'];
    $total += $row['price'] * $row['quantity'];
}

/* ===============================
   2. Add shipping fee (FIXED)
================================ */
$shipping_fee = 12;
$grand_total = $total + $shipping_fee;

/* ===============================
   3. Generate IDs
================================ */
$order_id   = uniqid("ORD");
$payment_id = uniqid("P");

/* ===============================
   4. Insert into payment table
================================ */
$payment_method = $_POST['payment_method'] ?? 'Online Banking';

$sql = "
INSERT INTO payment 
(payment_id, cart_id, order_id, total_amount, payment_date, payment_status, payment_method)
VALUES (?, ?, ?, ?, NOW(), 1, ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssds",
    $payment_id,
    $cart_id,
    $order_id,
    $grand_total,
    $payment_method
);
$stmt->execute();

/* ===============================
   5. Insert payment_item records
================================ */
$sql = "
INSERT INTO payment_item 
(paymentitem_id, payment_id, product_id, quantity, subtotal)
VALUES (?, ?, ?, ?, ?)
";
$stmt = $conn->prepare($sql);

foreach ($cart_items as $item) {
    $paymentitem_id = uniqid("PI");
    $subtotal = $item['price'] * $item['quantity'];

    $stmt->bind_param(
        "sssii",
        $paymentitem_id,
        $payment_id,
        $item['product_id'],
        $item['quantity'],
        $subtotal
    );
    $stmt->execute();
}

/* ===============================
   6. Clear cart
================================ */
$conn->query("DELETE FROM cart_item WHERE cart_id = '$cart_id'");
$conn->query("DELETE FROM cart WHERE cart_id = '$cart_id'");

/* ===============================
   7. Redirect to receipt
================================ */
header("Location: receipt.php?order_id=$order_id");
exit();
?>