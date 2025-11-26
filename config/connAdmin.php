<?php
$dbhost = "localhost";
$dbuser = "admin";
$dbpass = "admin123";
$dbname = "supplygo";
$dbport = "3306";
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
if ($mysqli->connect_errno) {
  echo "ERROR: ", $mysqli->connect_error;
}