<?php
session_start();
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? '';

if ($product_id === '') {
    die("Invalid product");
}

/* 1️⃣ Get or create cart */
$stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $cart_id = $row['cart_id'];
} else {
    $cart_id = uniqid("C");
    $stmt = $conn->prepare("INSERT INTO cart (cart_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ss", $cart_id, $user_id);
    $stmt->execute();
}

/* 2️⃣ Check if product already in cart */
$stmt = $conn->prepare("
    SELECT quantity 
    FROM cart_item 
    WHERE cart_id = ? AND product_id = ?
");
$stmt->bind_param("ss", $cart_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Update quantity
    $stmt = $conn->prepare("
        UPDATE cart_item 
        SET quantity = quantity + 1 
        WHERE cart_id = ? AND product_id = ?
    ");
    $stmt->bind_param("ss", $cart_id, $product_id);
    $stmt->execute();
} else {
    // Insert new item
    $cart_item_id = uniqid("CT");
    $stmt = $conn->prepare("
        INSERT INTO cart_item (cartItem_id, cart_id, product_id, quantity)
        VALUES (?, ?, ?, 1)
    ");
    $stmt->bind_param("sss", $cart_item_id, $cart_id, $product_id);
    $stmt->execute();
}

/* 3️⃣ Redirect */
header("Location: cart.php");
exit;
