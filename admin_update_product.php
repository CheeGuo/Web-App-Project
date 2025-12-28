<?php
include('include/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request.');
}

$id = $_POST['product_id'];
$name = $_POST['product_name'];
$price = floatval(str_replace(',', '', $_POST['price']));
$stock = intval($_POST['stock']);
$description = $_POST['description'];

// Update text fields
$sql = "UPDATE product 
        SET product_name='$name',
            price='$price',
            stock='$stock',
            description='$description'
        WHERE product_id='$id'";

mysqli_query($conn, $sql);

// Handle image upload (optional)
if (!empty($_FILES['photo']['name'])) {
    $filename = time() . '_' . basename($_FILES['photo']['name']);
    $target = "product_pic/" . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        mysqli_query($conn, "UPDATE product SET url='$target' WHERE product_id='$id'");
    }
}

header("Location: admin_products.php");
exit;
