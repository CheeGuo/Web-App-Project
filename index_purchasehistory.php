<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.total_amount,
        p.payment_date,
        p.payment_method
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    WHERE c.user_id = ?
    ORDER BY p.payment_date DESC
");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$payments = $stmt->get_result();
?>

<link rel="stylesheet" href="style.css">

<main class="history-page">
  <h2 style="text-align:center;">Purchase History</h2>

  <?php if ($payments->num_rows === 0): ?>
    <p style="text-align:center;">No purchase history found.</p>
  <?php endif; ?>

  <?php while ($pay = $payments->fetch_assoc()): ?>
    <div class="order-card">

      <div class="order-header">
        <strong>Order ID:</strong> <?= $pay['payment_id'] ?>
        <span><?= date('d/m/Y', strtotime($pay['payment_date'])) ?></span>
      </div>

      <?php
      $stmt2 = $conn->prepare("
          SELECT 
              pr.name,
              pi.quantity,
              pi.subtotal
          FROM payment_item pi
          JOIN product pr ON pi.product_id = pr.product_id
          WHERE pi.payment_id = ?
      ");
      $stmt2->bind_param("s", $pay['payment_id']);
      $stmt2->execute();
      $items = $stmt2->get_result();
      ?>

      <table class="order-table">
        <?php while ($item = $items->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>x<?= $item['quantity'] ?></td>
            <td><?= number_format($item['subtotal'], 2) ?> kr</td>
          </tr>
        <?php endwhile; ?>
      </table>

      <div class="order-footer">
        <strong>Total:</strong> <?= number_format($pay['total_amount'], 2) ?> kr
        <span><?= $pay['payment_method'] ?></span>
      </div>

    </div>
  <?php endwhile; ?>

</main>

<?php include('include/footer.php'); ?>
