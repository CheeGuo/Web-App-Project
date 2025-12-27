<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET name=?, phone=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $phone, $address, $user_id);
    $stmt->execute();

    header("Location: index_address.php");
    exit();
}
?> 
<main class="address-page">
<link rel="stylesheet" href="product_style.css">
<div class="address-card">

<h2 class="address-title">New Address</h2>

<form method="post" class="address-form">
  <input type="text" name="name" placeholder="Name" required>
  <input type="text" name="phone" placeholder="Phone No" required>
  <textarea name="address" placeholder="Address" rows="4" required></textarea>
  <button type="submit">Confirm</button>
</form>

</div>

</main>

<?php include('include/footer.php'); ?>
