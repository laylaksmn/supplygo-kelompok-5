<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pick Up Order - SUPPLYGO</title>
  <link rel="stylesheet" href="pickup.css">
</head>
<body>

<?php include 'views/layout/header.php'; ?>

<main>
<div class="main">
    <h2 class="title-text">Order Details</h2>

    <div class="address">
        <div class="address-card">
            <div class="name">
                <img src="icon1.png" alt="">
                <strong><?= $user['name'] ?></strong>
            </div>
            <p><?= $user['address'] ?></p>
        </div>

        <div class="address-card">
            <div class="name">
                <img src="icon2.png" alt="">
                <strong>Toko <?= $market['name'] ?></strong>
            </div>
            <p><?= $market['address'] ?></p>
        </div>
    </div>

    <div class="product-list">
        <div class="product-header">
            <span>PRODUCT</span>
            <span>PRICE</span>
            <span>QUANTITY</span>
        </div>

        <div class="product-item">
            <div class="product-info">
                <img src="<?= $photo ?>" alt="<?= $productName ?>">
                <div>
                    <h2><?= $productName ?></h2>
                    <p><?= $weight ?> kg</p>
                </div>
            </div>

            <div class="price">Rp <?= number_format($price); ?></div>

            <div class="quantity">
                <button onclick="changeQty(-1)">−</button>
                <span id="qty"><?= $quantity ?></span>
                <button onclick="changeQty(1)">+</button>
            </div>
        </div>
    </div>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <p><span>Total Supply</span><span><?= number_format($total_supply) ?> kg</span></p>
        <p><span>Shipping Costs</span><span>Rp <?= number_format($shipping_cost) ?></span></p>
        <p><span>Service Fee</span><span>Rp <?= number_format($service_fee) ?></span></p>
        <hr>
        <p class="total"><span>Total Payment</span><span>Rp <?= number_format($total) ?></span></p>
        <button onclick="send()">Send</button>
    </div>
</div>
</main>

<script src="pickup.js"></script>
</body>
</html>