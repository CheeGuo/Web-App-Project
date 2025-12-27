<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, phone, address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<main class="address-page">
<link rel="stylesheet" href="product_style.css">
<div class="address-card">

<h2 class="address-title">My Addresses</h2>

<?php if ($row['address']): ?>
<div class="address-item">
  <div class="address-text">
    <strong><?= $row['name'] ?> (<?= $row['phone'] ?>)</strong><br>
    <?= nl2br($row['address']) ?>
  </div>
  <a href="new_address.php" class="edit-icon">✎</a>
</div>
<?php else: ?>
<p>No address added yet</p>
<?php endif; ?>

<a href="new_address.php" class="add-btn">Add</a>

</div>

</main>

<?php include('include/footer.php'); ?>
