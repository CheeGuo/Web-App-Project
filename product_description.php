<?php
include('include/db.php');
include('include/header.php');

$id = $_GET['id'] ?? '';

if ($id === '') {
    die('No product selected');
}

$stmt = $conn->prepare("SELECT product_name, price, description, url FROM product WHERE product_id = ? AND is_active = 1");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die('Product not found');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($product['product_name']) ?></title>
<link rel="stylesheet" href="product_style.css">
</head>
<body>

<div class="product-detail-container">

    <div class="product-left">
        <img src="<?= htmlspecialchars($product['url']) ?>" alt="Product Image">
    </div>

    <div class="product-right">
        <h1><?= htmlspecialchars($product['product_name']) ?></h1>
        <h2><?= number_format($product['price'], 0) ?> kr</h2>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    <form action="add_cart.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $id ?>">
        <button type="submit" class="add-cart-btn">Add To Cart</button>
    </form>
    </div>

</div>

</body>
</html>

<?php include('include/footer.php'); ?>
