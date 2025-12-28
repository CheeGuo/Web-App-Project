<?php
include('include/db.php');

$filter = $_GET['filter'] ?? 'daily';
$date   = $_GET['date'] ?? date('Y-m-d');
$year   = date('Y', strtotime($date));

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

/* ===============================
   CSV HEADERS
================================ */
$filename = "sales_{$filter}_{$date}.csv";
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=$filename");

$output = fopen("php://output", "w");

/* ===============================
   CSV COLUMN TITLES
================================ */
if ($filter === 'weekly') {
    fputcsv($output, ['Week', 'Order ID', 'Account ID', 'Username', 'Product', 'Product ID', 'Total (RM)', 'Date']);
} elseif ($filter === 'monthly') {
    fputcsv($output, ['Month', 'Order ID', 'Account ID', 'Username', 'Product', 'Product ID', 'Total (RM)', 'Date']);
} else {
    fputcsv($output, ['Order ID', 'Account ID', 'Username', 'Product', 'Product ID', 'Total (RM)', 'Date']);
}

/* ===============================
   CSV DATA
================================ */
while ($row = mysqli_fetch_assoc($result)) {

    if ($filter === 'weekly') {
        fputcsv($output, [
            'Week '.$row['week_no'],
            $row['order_id'],
            $row['user_id'],
            $row['username'],
            $row['product_name'],
            $row['product_id'],
            number_format($row['total_amount'],2),
            date("j/n/Y", strtotime($row['display_date']))
        ]);
    }

    elseif ($filter === 'monthly') {
        fputcsv($output, [
            $row['month_name'],
            $row['order_id'],
            $row['user_id'],
            $row['username'],
            $row['product_name'],
            $row['product_id'],
            number_format($row['total_amount'],2),
            date("j/n/Y", strtotime($row['display_date']))
        ]);
    }

    else {
        fputcsv($output, [
            $row['order_id'],
            $row['user_id'],
            $row['username'],
            $row['product_name'],
            $row['product_id'],
            number_format($row['total_amount'],2),
            date("j/n/Y", strtotime($row['display_date']))
        ]);
    }
}

fclose($output);
exit;
