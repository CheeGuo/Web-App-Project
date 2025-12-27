<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   GET USER CART
========================= */
$cart_id = null;

$stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($cart_id);
$stmt->fetch();
$stmt->close();

if (!$cart_id) {
    echo "<p style='text-align:center; padding:50px;'>Your cart is empty.</p>";
    include('include/footer.php');
    exit();
}

/* =========================
   GET CART ITEMS
========================= */
$stmt = $conn->prepare("
    SELECT 
        p.name,
        p.price,
        ci.quantity,
        (p.price * ci.quantity) AS subtotal
    FROM cart_item ci
    JOIN product p ON ci.product_id = p.product_id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("s", $cart_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<link rel="stylesheet" href="style.css">

<main class="cart-page">

  <h2 style="text-align:center; margin-bottom:20px;">Shopping Cart</h2>

  <table class="cart-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php $total += $row['subtotal']; ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= number_format($row['price'], 2) ?> kr</td>
          <td><?= $row['quantity'] ?></td>
          <td><?= number_format($row['subtotal'], 2) ?> kr</td>
          <td>
            <a href="#" style="color:red; text-decoration:none;">🗑 Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="cart-total">
    <strong>Total Cost (nok):</strong> <?= number_format($total, 2) ?> kr
  </div>

  <div class="cart-actions">
    <a href="index.php" class="cart-btn">Continue Shopping</a>
    <a href="checkout.php" class="cart-btn checkout">Check Out</a>
  </div>

</main>

<?php
$stmt->close();
include('include/footer.php');
?>
