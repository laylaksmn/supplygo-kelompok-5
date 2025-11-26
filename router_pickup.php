<?php
require 'auth.php';
require 'conn.php';
require 'controllers/PickupController.php';

$controller = new PickupController($mysqli);
$controller->show();