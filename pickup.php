<?php
  require_once 'auth.php';
  require_once 'conn.php';

  if (isset($_GET['qty'])) {
      $quantity = (int) $_GET['qty'];
  } else {
      $quantity = 1;
  }

  if ($quantity < 1) {
      $quantity = 1;
  }

  include_once 'conn.php';
  $market_id = urldecode($_COOKIE['market_id']);
  $productName = urldecode($_COOKIE['product_name']);
  $price = urldecode($_COOKIE['product_price']);
  $weight = urldecode($_COOKIE['product_weight']);
  $photo = urldecode($_COOKIE['product_photo']);

  $shipping_cost = 7000;
  $service_fee = 2000;
  $total_supply = $quantity * $weight;
  $total = $shipping_cost + $service_fee;

  $result = $mysqli->query("SELECT * FROM markets WHERE market_id = '$market_id'");
  $marketData = $result->fetch_assoc();
  $market_name = 'Toko ' . $marketData['name'];
  $market_address = $marketData['address'];

  $user = $_SESSION['user'];
  $result = $mysqli->query("SELECT * FROM user WHERE email = '$user'");
  $userData = $result->fetch_assoc();
  $user_id = $userData['user_id'];
  $name = $userData['name'];
  $address = $userData['address'];

  if (isset($_GET['send']) && $_GET['send'] == 'true') {
      $stmt = $mysqli->prepare("INSERT INTO products (market_id, product_name, price, weight, stock, product_image_path, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isdiisi", $market_id, $productName, $price, $weight, $quantity, $photo, $user_id);
      $stmt->execute();
      $stmt->close();

      header("Location: history.php");
      exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pick Up Order - SUPPLYGO</title>
  <link rel="stylesheet" href="pickup.css">
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
        <a href="market.php" class="active">Market</a>
        <a href="transport.php">Transport</a>
        <a href="tracking.php">Tracking</a>
        <a href="history.php">History</a>
        <a href="logout.php" class="logout"><button>Log out</button></a>
      </nav>
    </div>
  </header>

  <main>
  <div class="main">
    <h2 class="title-text">Order Details</h2>

    <div class="address">
      <div class="address-card">
      <div class="name">
        <img src="https://cdn-icons-png.freepik.com/512/3388/3388845.png?uid=R52619887&ga=GA1.1.1643784234.1688526712" alt="Pickup Icon"> 
        <strong><?php echo $name ?></strong>
      </div>
      <p><?php echo $address ?></p>
    </div>
    <div class="address-card">
      <div class="name">
        <img src="https://cdn-icons-png.freepik.com/512/2948/2948253.png?uid=R52619887&ga=GA1.1.1643784234.1688526712" alt="Location Icon"> 
        <strong><?php echo $market_name ?></strong>
      </div>
      <p><?php echo $market_address ?></p>
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
          <img src="<?php echo $photo ?>" alt="<?php echo $productName ?>">
          <div>
            <h2><?php echo $productName ?></h2>
            <p><?php echo $weight ?> kg</p>
          </div>
        </div>

        <div class="price">Rp <?php echo number_format($price, 0, ',', '.'); ?></div>

        <div class="quantity">
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
          <span id="qty"><?php echo $quantity; ?></span>
          <button class="qty-btn" onclick="changeQty(1)">+</button>
        </div>
      </div>
    </div>

      <div class="order-summary">
        <h3>Order Summary</h3>
        <p><span>Total Supply</span><span><?php echo number_format($total_supply, 0, ',', '.'); ?> kg</span></p>
        <p><span>Shipping Costs</span><span>Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></span></p>
        <p><span>Service Fee</span><span>Rp <?php echo number_format($service_fee, 0, ',', '.'); ?></span></p>
        <hr>
        <p class="total"><span>Total Payment</span><span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span></p>
        <button class="send-btn" id="sendBtn" onclick="send()">Send</button>
      </div>
    </div>
  </div>
  </main>
  <script src="pickup.js"></script>
</body>
</html>