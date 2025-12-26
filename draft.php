<?php
include("include/footer.php");
include("include/db.php");
$username =$_POST["username"];
$password = password_hash($_POST["username"],PASSWORD_DEFAULT);

$statement = $conn->prepare("SELECT password FROM users WHERE username = ? "); 
$statement->bind_param("s",$username); 
$statement->execute();
$statement->store_result();

if(isset($statement)){
  $statement->bind_result($hashed_password);
  $statement->fetch();
  if(password_verify($password,$hashed_password)){
    session_start();
    $_SESSION["username"]=$username;
    header("location:index.php");
    exit();
  }
  else{
    echo"invalid password";
  }
}
else {
  echo "Wrong Username";
}
?>