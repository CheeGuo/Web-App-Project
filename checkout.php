<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
      <p class="required">
    <a href="choose_address.php">*Select shipping address</a>
    </p>

      <hr>

      <p><strong>Shipping Method:</strong> -</p>
      <p><strong>Estimated Shipping Fee:</strong> -</p>
    </div>

    <!-- STEP 2 -->
    <div class="checkout-box">
      <h3>② Payment Method</h3>

      <div class="payment-options">
        <label><input type="radio" name="payment"> Credit / Debit Card</label>
        <label><input type="radio" name="payment"> Online Banking</label>
        <label><input type="radio" name="payment"> PayPal</label>
        <label><input type="radio" name="payment"> Vipps</label>
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
      <a href="cart.php" class="btn cancel">Cancel</a>
      <form action="confirm_order.php" method="POST" style="display:inline;">
        <button class="btn confirm">Confirm</button>
      </form>
    </div>

  </div>

</div>

<?php include('include/footer.php'); ?>
</body>
</html>
