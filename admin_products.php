<?php
include('include/admin_header.php');
include('include/db.php');


$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM product WHERE is_active = 1";
if ($category !== '') {
    $sql .= " WHERE category = '$category'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class style="height:80px";>
    <div class="top-bar">
    <div class="left" style="align-items:flex-start">
        <a href="admin_index.php" class="back-btn"">←</a>
        <form method="GET">
            <select name="category" onchange="this.form.submit()">
                <option value="">Category</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Hobby and Leisure">Hobby and Leisure</option>
            </select>
        </form>
    </div>
    <h2>Edit Product</h2>

    <a href="add_product.php" class="add-btns">＋ Add Product</a>
    
    </div>
</div>

<div class="product-grid">

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="product-card">

        <img src="<?php echo $row['url']; ?>">

        <div class="info">
            <div class="id">ID: <?php echo $row['product_id']; ?></div>
            <div class="name"><?php echo $row['product_name']; ?></div>
            <div class="price"><?php echo number_format($row['price'], 2); ?> kr</div>
        </div>

        <div class="actions">
            <a href="admin_edit_product.php?id=<?php echo $row['product_id']; ?>">✎</a>
            <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" onclick="return confirm('Delete this product?')">✕</a>
        </div>

    </div>
<?php } ?>

</div>
<footer>
<div class="pager-box">
    <a style="text-align: right;" href="#">← Previous</a>
    <a style="text-align: left;" href="#">Next →</a>
</div>

</footer>
</body>
</html>
<?php include('include/footer.php'); ?>