<?php
$db_server = "localhost";
$db_username = "root";
$db_pass = "";
$db_name = "webapp";

$conn = mysqli_connect($db_server, $db_username, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed");
}
