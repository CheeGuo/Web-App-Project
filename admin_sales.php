<?php
include('include/admin_header.php');
include('include/db.php');

$year = $_GET['year'] ?? date('Y');

$sql = "
SELECT 
    MONTHNAME(p.payment_date) AS month,
    p.order_id,
    u.user_id,
    u.username,
    pr.product_name,
    pi.product_id,
    p.total_amount,
    DATE(p.payment_date) AS pay_date
FROM payment p
JOIN cart c ON p.cart_id = c.cart_id
JOIN users u ON c.user_id = u.user_id
JOIN payment_item pi ON p.payment_id = pi.payment_id
JOIN product pr ON pi.product_id = pr.product_id
WHERE YEAR(p.payment_date) = '$year'
ORDER BY p.payment_date ASC
";

$result = mysqli_query($conn, $sql);
$currentMonth = '';
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="admin.css">
<title>Sales History</title>
</head>
<body>

<div class="sales-container">
<h2>Sales History</h2>

<div class="filter-bar">
<span>Filter:</span>
<button class="filter-btn">Daily</button>
<button class="filter-btn">Weekly</button>
<button class="filter-btn active">Monthly</button>

<form method="GET">
<span>Year:</span>
<select name="year" onchange="this.form.submit()">
<?php
for ($y = 2023; $y <= date('Y'); $y++) {
$sel = $y == $year ? 'selected' : '';
echo "<option $sel>$y</option>";
}
?>
</select>
</form>
</div>

<table class="sales-table">
<tr>
<th>Month</th>
<th>Order ID</th>
<th>Account ID</th>
<th>Username</th>
<th>Product</th>
<th>Product ID</th>
<th>Total (RM)</th>
<th>Date</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['month'] ?></td>
<td><?= $row['order_id'] ?></td>
<td><?= $row['user_id'] ?></td>
<td><?= $row['username'] ?></td>
<td><?= $row['product_name'] ?> x1</td>
<td><?= $row['product_id'] ?></td>
<td><?= number_format($row['total_amount'],2) ?></td>
<td><?= date("j/n/Y", strtotime($row['pay_date'])) ?></td>
</tr>
<?php } ?>
</table>

<div class="action-bar">
<a href="admin_dashboard.php" class="back-btn">Back</a>
<a href="export_sales.php?year=<?= $year ?>" class="csv-btn">Download CSV</a>
</div>

</div>

</body>
</html>
