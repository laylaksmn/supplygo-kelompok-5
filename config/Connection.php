<?php
require_once "../config/auth.php";

$user = $_SESSION['user'];

if ($user['role'] === 'customer') {
  require_once "../config/connCustomer.php";
} else {
  require_once "../config/connAdmin.php";
}
