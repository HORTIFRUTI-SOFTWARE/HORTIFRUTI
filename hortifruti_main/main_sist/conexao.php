<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "mercantil_lima";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
