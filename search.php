<?php
include("include/header.php");
include("include/db.php");

$keyword = $_GET['keyword'] ?? '';
$sort = $_GET['sort'] ?? '';

$orderBy = "ORDER BY product_name ASC";

if ($sort === "price_asc") {
    $orderBy = "ORDER BY price ASC";
} elseif ($sort === "price_desc") {
    $orderBy = "ORDER BY price DESC";
}

$sql = "
SELECT * FROM product
WHERE is_active = 1
AND product_name LIKE ?
$orderBy
";

$stmt = $conn->prepare($sql);
$like = "%".$keyword."%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Result</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="product_style.css">
</head>
<body>

<div class="product-page">

<p style="text-align:center">Search Result for "<strong><?= htmlspecialchars($keyword) ?></strong>"</p>

<form method="get" style="text-align:center;">
    <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
    <select name="sort" onchange="this.form.submit()">
        <option value="">Sort by price</option>
        <option value="price_asc" <?= $sort==="price_asc"?"selected":"" ?>>Low → High</option>
        <option value="price_desc" <?= $sort==="price_desc"?"selected":"" ?>>High → Low</option>
    </select>
</form>

<div class="products">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="product">
        <a href="product_description.php?id=<?= $row['product_id'] ?>" class="product-link">
            <img src="<?= $row['url'] ?>">
            <p><?= $row['product_name'] ?></p>
            <p class="price"><?= number_format($row['price'],2) ?> kr</p>
        </a>
    </div>
<?php endwhile; ?>
</div>

</div>

</body>
</html>

<?php include("include/footer.php"); ?>
