<?php
$host = "localhost"; //ganti alamat IP
$type = "admin";
$pass = "admin123";
$database = "supplygo";
$port = "3306";
$mysqli = new mysqli($host, $type, $pass, $database, $port);
if ($mysqli->connect_errno) {
  echo "ERROR: ", $mysqli->connect_error;
}
