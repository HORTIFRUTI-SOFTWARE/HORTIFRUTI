<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "hortfrut";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
