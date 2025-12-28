<?php
include('include/admin_header.php');
include('include/db.php');

$id = $_GET['id'] ?? '';

if ($id === '') {
    die('No product ID provided.');
}

$result = mysqli_query($conn, "SELECT * FROM product WHERE product_id='$id'");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Product not found.');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- TOP BAR -->
<div class="edit-top">
    <a href="admin_products.php" class="edit-back">←</a>

    <div class="edit-title">Edit Product</div>

    <div class="edit-photo">
        <img src="<?= $row['url'] ?>" alt="Product Image">
        <label class="edit-photo-btn">
            Edit photo
            <input type="file" form="editForm" name="photo" hidden>
        </label>
    </div>
</div>

<!-- FORM -->
<form id="editForm" action="admin_update_product.php" method="POST" enctype="multipart/form-data" class="edit-form">

    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">

    <div class="form-row">
        <label>Product Name</label>
        <input type="text" name="product_name" value="<?= htmlspecialchars($row['product_name']) ?>" required>
        <span class="product-id"><?= $row['product_id'] ?></span>
    </div>

    <div class="form-group">
        <label>Price (kr)</label>
        <input type="text" name="price" value="<?= number_format($row['price'], 2) ?>" required>
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" value="<?= $row['stock'] ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="6" required><?= htmlspecialchars($row['description']) ?></textarea>
    </div>

    <div class="form-action">
        <button type="submit" class="done-btn">Done</button>
    </div>
</form>

<?php include('include/footer.php'); ?>
</body>
</html>
