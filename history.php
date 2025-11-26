<?php
require_once 'auth.php';
require_once 'conn.php';
$products = [];
$user = $_SESSION['user'];
$result = $mysqli->query("SELECT * FROM user WHERE email = '$user'");
$userData = $result->fetch_assoc();
$user_id = $userData['user_id'];
$result = $mysqli->query("SELECT * FROM products WHERE user_id = '$user_id'");
while ($productData = $result->fetch_assoc()) {
    $market_id = $productData['market_id'];
    $market_result = $mysqli->query("SELECT * FROM markets WHERE market_id = '$market_id'");
    $marketData = $market_result->fetch_assoc();
    $market = $marketData['name'];
    $products[] = [
        'name' => $productData['product_name'],
        'market' => $market,
        'weight' => $productData['weight'],
        'price' => $productData['price'],
        'stock' => $productData['stock'],
        'image' => $productData['product_image_path']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - SUPPLYGO</title>
    <link rel="stylesheet" href="history.css">
</head>
<body>

<header>
    <div class="header-container">
       <div class="logo">
        <img src="logo web.png" alt="Logo" class="logo-img">
        <h2>SUPPLYGO</h2>
      </div>
      <nav>
        <a href="dashboard.php">Home</a>
        <a href="market.php">Market</a>
        <a href="transport.php">Transport</a>
        <a href="tracking.php">Tracking</a>
        <a href="history.php" class="active">History</a>
        <a href="logout.php" class="logout"><button>Log out</button></a>
      </nav>
    </div>
</header>

<main class="cards-container">
  <?php foreach ($products as $product): ?>
    <div class="card">
      <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="card-img">
      <div class="card-content">
        <h3><?= $product['name'] ?></h3>
        <p><b>Toko:</b> <?= $product['market'] ?></p>
        <p><b>Harga:</b> Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
        <p><b>Berat:</b> <?= $product['weight'] ?> kg</p>
        <p><b>Stok:</b> <?= $product['stock'] ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</main>

</body>
</html>
