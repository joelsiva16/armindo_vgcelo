<?php

include 'inc/config.inc';

$name = $_POST['name'];
$password = $_POST['password'];


$checkpass = hash('sha256', $password);

$sql = "SELECT id, password FROM users WHERE name = '$name'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if($checkpass == $row['password']){

    session_start();

    $_SESSION['user'] = $row['id'];


    header("Location: index.php");
    
}else{

    header("Location: error.php");
}

?>