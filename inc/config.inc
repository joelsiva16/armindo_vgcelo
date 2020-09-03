<?php

    $host = "localhost";
    $username = "root";
    $password = "usbw";
    $db_name = "vgc_elo";

    // Create connection
$conn = new mysqli($host, $username, $password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
header("Content-type: text/html; charset=ISO-8859-1");
?>