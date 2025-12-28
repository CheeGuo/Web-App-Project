<!DOCTYPE html>
<html>
    <footer>
        <a href="adminfirst.php">
            <button>Back to main menu</button>
        </a>
    </footer>
</html>
<?php
include('include/db.php');
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$query = "
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS payment_item;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS cart_item;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS users;
DROP TABLE  password_resets ;
SET FOREIGN_KEY_CHECKS = 1;
";

if (mysqli_multi_query($conn, $query)) {
  echo "Drop Table Successful";
} else {
  echo "Error: " . mysqli_error($conn);
}
?>
