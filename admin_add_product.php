<?php
include('include/admin_header.php');
include('include/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = floatval(str_replace(',', '', $_POST['price']));
    $stock = intval($_POST['stock']);
    $description = $_POST['description'];

    $product_id = 'PRD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

    $imgName = basename($_FILES['photo']['name']);
    $imgPath = 'product_pic/' . time() . '_' . $imgName;
    move_uploaded_file($_FILES['photo']['tmp_name'], $imgPath);

    $sql = "
    INSERT INTO product
    (product_id, product_name, category, price, stock, url, description,is_active)
    VALUES
    ('$product_id', '$product_name', '$category', '$price', '$stock', '$imgPath', '$description','1')
    ";

    mysqli_query($conn, $sql);

    header("Location: admin_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="edit-container">
    <h2>Add Product</h2>

    <form method="POST" enctype="multipart/form-data" class="edit-form">

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product_name" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Hobby and Leisure">Hobby and Leisure</option>
                <option value="Electronics">Electronics</option>
                <option value="Electronics Devices">Electronics Devices</option>
                <option value="Robot">Robot</option>
                <option value="Home Living">Hobby Living</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price (kr)</label>
            <input type="text" name="price" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="6" required></textarea>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="photo" required>
        </div>

        <div class="form-action">
            <button type="submit">Add Product</button>
            <a href="admin_products.php">Cancel</a>
        </div>

    </form>
</div>

</body>
</html>
