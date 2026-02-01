<?php
// Súbor db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaming_eshop"; // Uisti sa, že toto sedí s phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Spojenie zlyhalo: " . $conn->connect_error);
}
// SEM NEDÁVAJ session_start()!
?>