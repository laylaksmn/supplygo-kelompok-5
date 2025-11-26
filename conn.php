<?php
$host = "localhost";
$user = "root";
$pass = "CRozz570";
$db = "transport_db";
$port = 3306;

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_errno) {
    die("Koneksi database gagal: " . $mysqli->connect_error);
}
?>
