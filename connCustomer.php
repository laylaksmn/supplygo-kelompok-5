<?php
$host = "localhost"; //ganti alamat IP
$type = "Customer";
$pass = "customer123";
$database = "supplygo";
$port = "3306";
$mysqli = new mysqli($host, $type, $pass, $database, $port);
if ($mysqli->connect_errno) {
  echo "ERROR: ", $mysqli->connect_error;
}