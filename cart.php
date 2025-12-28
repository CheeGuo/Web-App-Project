<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['cartitem_id'];
    $action = $_POST['action'];

    if ($action === 'inc') {
        $sql = "
        UPDATE cart_item ci
        JOIN product p ON ci.product_id = p.product_id
        SET ci.quantity = ci.quantity + 1
        WHERE ci.cartitem_id = ?
        AND ci.quantity < p.stock
        ";
    } elseif ($action === 'dec') {
        $sql = "
        UPDATE cart_item
        SET quantity = GREATEST(quantity - 1, 1)
        WHERE cartitem_id = ?
        ";
    } elseif ($action === 'delete') {
        $sql = "
        DELETE FROM cart_item
        WHERE cartitem_id = ?
        ";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT ci.cartitem_id, p.product_name, p.price, ci.quantity
FROM cart c
JOIN cart_item ci ON c.cart_id = ci.cart_id
JOIN product p ON ci.product_id = p.product_id
WHERE c.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<main class="cart-page">

<h2 class="cart-title">Shopping Cart</h2>

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

<?php
$total = 0;
while ($row = $result->fetch_assoc()):
$subtotal = $row['price'] * $row['quantity'];
$total += $subtotal;
?>

<tr>
<td><?= $row['product_name'] ?></td>
<td><?= number_format($row['price'],2) ?> kr</td>

<td class="qty-cell">
<form method="post" style="display:inline;">
<input type="hidden" name="cartitem_id" value="<?= $row['cartitem_id'] ?>">
<input type="hidden" name="action" value="dec">
<button>−</button>
</form>

<?= $row['quantity'] ?>

<form method="post" style="display:inline;">
<input type="hidden" name="cartitem_id" value="<?= $row['cartitem_id'] ?>">
<input type="hidden" name="action" value="inc">
<button>+</button>
</form>
</td>

<td><?= number_format($subtotal,2) ?> kr</td>

<td>
<form method="post">
<input type="hidden" name="cartitem_id" value="<?= $row['cartitem_id'] ?>">
<input type="hidden" name="action" value="delete">
<button class="delete-btn">Delete</button>
</form>
</td>
</tr>

<?php endwhile; ?>
</tbody>
</table>

<div class="cart-total">
Total Cost (nok): <strong><?= number_format($total,2) ?> kr</strong>
</div>
<div class="cart-actions">
    <a href="index.php" class="cart-btn">Continue Shopping</a>
    <a href="checkout.php" class="cart-btn checkout">Check Out</a>
</div>

</main>

<?php include('include/footer.php'); ?>
