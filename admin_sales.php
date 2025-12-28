<?php
include('include/admin_header.php');
include('include/db.php');

$filter = $_GET['filter'] ?? 'daily';
$date = $_GET['date'] ?? date('Y-m-d');
$year = date('Y', strtotime($date));

if ($filter === 'daily') {
    $sql = "
    SELECT 
        p.order_id,
        u.user_id,
        u.username,
        pr.product_name,
        pi.product_id,
        p.total_amount,
        DATE(p.payment_date) AS display_date
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    JOIN users u ON c.user_id = u.user_id
    JOIN payment_item pi ON p.payment_id = pi.payment_id
    JOIN product pr ON pi.product_id = pr.product_id
    WHERE DATE(p.payment_date) = '$date'
    ORDER BY p.payment_date
    ";
}

if ($filter === 'weekly') {
    $sql = "
    SELECT 
        WEEK(p.payment_date,1) AS week_no,
        p.order_id,
        u.user_id,
        u.username,
        pr.product_name,
        pi.product_id,
        p.total_amount,
        DATE(p.payment_date) AS display_date
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    JOIN users u ON c.user_id = u.user_id
    JOIN payment_item pi ON p.payment_id = pi.payment_id
    JOIN product pr ON pi.product_id = pr.product_id
    WHERE YEAR(p.payment_date) = '$year'
    ORDER BY p.payment_date
    ";
}

if ($filter === 'monthly') {
    $sql = "
    SELECT 
        MONTHNAME(p.payment_date) AS month_name,
        p.order_id,
        u.user_id,
        u.username,
        pr.product_name,
        pi.product_id,
        p.total_amount,
        DATE(p.payment_date) AS display_date
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    JOIN users u ON c.user_id = u.user_id
    JOIN payment_item pi ON p.payment_id = pi.payment_id
    JOIN product pr ON pi.product_id = pr.product_id
    WHERE YEAR(p.payment_date) = '$year'
    ORDER BY p.payment_date
    ";
}

$result = mysqli_query($conn, $sql);
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
<a href="?filter=daily&date=<?= $date ?>" class="filter-btn <?= $filter==='daily'?'active':'' ?>">Daily</a>
<a href="?filter=weekly&date=<?= $date ?>" class="filter-btn <?= $filter==='weekly'?'active':'' ?>">Weekly</a>
<a href="?filter=monthly&date=<?= $date ?>" class="filter-btn <?= $filter==='monthly'?'active':'' ?>">Monthly</a>

<form method="GET">
<input type="hidden" name="filter" value="<?= $filter ?>">
<span>Date:</span>
<input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()">
</form>
</div>

<table class="sales-table">
<tr>
<?php if ($filter === 'weekly') echo "<th>Week</th>"; ?>
<?php if ($filter === 'monthly') echo "<th>Month</th>"; ?>
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
<?php if ($filter === 'weekly') echo "<td>Week ".$row['week_no']."</td>"; ?>
<?php if ($filter === 'monthly') echo "<td>".$row['month_name']."</td>"; ?>
<td><?= $row['order_id'] ?></td>
<td><?= $row['user_id'] ?></td>
<td><?= $row['username'] ?></td>
<td><?= $row['product_name'] ?> x1</td>
<td><?= $row['product_id'] ?></td>
<td><?= number_format($row['total_amount'],2) ?></td>
<td><?= date("j/n/Y", strtotime($row['display_date'])) ?></td>
</tr>
<?php } ?>
</table>

<div class="action-bar">
<a href="admin_index.php" class="back-btn">Back</a>
<a href="export_sales.php?filter=<?= $filter ?>&date=<?= $date ?>" class="csv-btn">Download CSV</a>
</div>

</div>

</body>
</html>
