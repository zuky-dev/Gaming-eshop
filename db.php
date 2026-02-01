<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaming_eshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Spojenie zlyhalo: " . $conn->connect_error);
}


?>
