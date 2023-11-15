<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
include 'connection.php';

$objDb = new DbConnect;
$conn = $objDb->connect();
var_dump($conn);
?>
