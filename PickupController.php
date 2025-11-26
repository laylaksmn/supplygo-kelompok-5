<?php
require_once 'models/UserModel.php';
require_once 'models/MarketModel.php';
require_once 'models/ProductModel.php';

class PickupController {

    private $userModel;
    private $marketModel;
    private $productModel;

    public function __construct($mysqli) {
        $this->userModel = new UserModel($mysqli);
        $this->marketModel = new MarketModel($mysqli);
        $this->productModel = new ProductModel($mysqli);
    }

    public function show() {
        $quantity = isset($_GET['qty']) ? (int) $_GET['qty'] : 1;
        if ($quantity < 1) $quantity = 1;

        $market_id   = urldecode($_COOKIE['market_id']);
        $productName = urldecode($_COOKIE['product_name']);
        $price       = urldecode($_COOKIE['product_price']);
        $weight      = urldecode($_COOKIE['product_weight']);
        $photo       = urldecode($_COOKIE['product_photo']);

        $shipping_cost = 7000;
        $service_fee = 2000;

        $total_supply = $quantity * $weight;
        $total = $shipping_cost + $service_fee;

        $user = $this->userModel->getUserByEmail($_SESSION['user']);
        $user_id = $user['user_id'];

        $market = $this->marketModel->getMarketById($market_id);

        if (isset($_GET['send']) && $_GET['send'] == 'true') {
            $this->productModel->insertProduct(
                $market_id,
                $productName,
                $price,
                $weight,
                $quantity,
                $photo,
                $user_id
            );

            header("Location: history.php");
            exit();
        }

        include 'views/pickup_view.php';
    }
}