<?php
if ($_SESSION['user']['role'] !== 'admin') {
  http_response_code(403);
  die("Access denied! You do not have permission to access this page.");
}