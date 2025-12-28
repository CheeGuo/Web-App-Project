<?php
include('include/db.php');

$id = $_GET['id'] ?? '';

if ($id === '') {
    die('Invalid product ID');
}

/*
  DO NOT DELETE PRODUCT
  Just deactivate it
*/
$sql = "UPDATE product SET is_active = 0 WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

/* Redirect back */
header("Location: admin_products.php");
exit;
