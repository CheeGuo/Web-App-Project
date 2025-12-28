<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$shipping_address = $_SESSION['shipping_address'] ?? null;
$shipping_method  = $_SESSION['shipping_method'] ?? '-';
$shipping_fee     = $_SESSION['shipping_fee'] ?? 12;

/* ===============================
   HANDLE CONFIRM CLICK
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {

    $payment_method = $_POST['payment_method'] ?? 'Online Banking';

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

    $grand_total = $total + $shipping_fee;

    $order_id   = uniqid("ORD");
    $payment_id = uniqid("P");

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

    $conn->query("DELETE FROM cart_item WHERE cart_id = '$cart_id'");

    header("Location: receipt.php?order_id=$order_id");
    exit();
}

/* ===============================
   FETCH CART FOR DISPLAY
================================ */
$sql = "
SELECT 
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

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
    $items[] = $row;
}

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

<form method="POST">
<input type="hidden" name="confirm_order" value="1">

<div class="checkout-container">

  <div class="checkout-left">

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

    <div class="checkout-box">
      <h3>② Payment Method</h3>
      <label><input type="radio" name="payment_method" value="Credit/Debit" required> Credit / Debit Card</label><br>
      <label><input type="radio" name="payment_method" value="Online Banking"> Online Banking</label><br>
      <label><input type="radio" name="payment_method" value="PayPal"> PayPal</label><br>
      <label><input type="radio" name="payment_method" value="Vipps"> Vipps</label>
    </div>

  </div>

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
      <p>Shipping Fee: <span><?= $shipping_fee ?> kr</span></p>
      <h4>Total (RM): <span>RM <?= number_format($total_rm,2) ?></span></h4>
    </div>

    <div class="checkout-actions">
      <a href="index.php" class="btn cancel">Cancel</a>
      <button class="btn confirm">Confirm</button>
    </div>

  </div>

</div>

</form>

<?php include('include/footer.php'); ?>
</body>
</html>
