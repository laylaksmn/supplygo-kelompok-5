<?php
require "./controllers/ProfileController.php";

$controller = new ProfileController($mysqli);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['deleteaccount'])) {
        $controller->deleteAccount();
    }

    $controller->update();
}

$controller->index();