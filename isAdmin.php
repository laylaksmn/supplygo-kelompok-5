<?php
if ($_SESSION['user']['role'] !== 'admin') {
  die("Access denied!");
}