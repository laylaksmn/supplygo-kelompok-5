<?php
include 'auth.php';
  $user = $_SESSION['user'];
  if ($user['role'] === 'customer') {
    require_once 'connCustomer.php';
  } else {
    require_once 'conn.php';
  }

$user = $_SESSION['user'];
// TOTAL PENJUALAN = SUM(price * sold)
$sqlTotal = "SELECT SUM(price * sold) AS total_penjualan FROM products";
$resTotal = $mysqli->query($sqlTotal);
$rowTotal = $resTotal ? $resTotal->fetch_object() : null;
$total_penjualan = $rowTotal && $rowTotal->total_penjualan ? (int)$rowTotal->total_penjualan : 0;

// BARANG TERJUAL = SUM(sold)
$sqlSold = "SELECT SUM(sold) AS barang_terjual FROM products";
$resSold = $mysqli->query($sqlSold);
$rowSold = $resSold ? $resSold->fetch_object() : null;
$barang_terjual = $rowSold && $rowSold->barang_terjual ? (int)$rowSold->barang_terjual : 0;

// PRODUK AKTIF = COUNT(*)
$sqlActive = "SELECT COUNT(*) AS produk_aktif FROM products WHERE user_id = '{$user['id']}'";
$resActive = $mysqli->query($sqlActive);
$rowActive = $resActive ? $resActive->fetch_object() : null;
$produk_aktif = $rowActive && $rowActive->produk_aktif ? (int)$rowActive->produk_aktif : 0;

// AMBIL DATA PRODUK
$sqlProducts = "SELECT p.*, m.name, m.category
FROM products AS p
JOIN markets as m ON p.market_id = m.market_id
WHERE p.user_id = '{$user['id']}'
ORDER BY p.product_id DESC";
$resultProducts = $mysqli->query($sqlProducts);
$products = [];
if ($resultProducts && $resultProducts->num_rows > 0) {
  while ($p = $resultProducts->fetch_assoc()) {
    $stock = isset($p['stock']) ? (int)$p['stock'] : 0;
    if ($stock == 0) {
      $status = 'Habis';
      $badgeClass = 'is-danger';
    } elseif ($stock < 20) {
      $status = 'Menipis';
      $badgeClass = 'is-warn';
    } else {
      $status = 'Aman';
      $badgeClass = 'is-ok';
    }
    $level = $stock;
    if ($level < 0) $level = 0;
    if ($level > 100) $level = 100;

    $products[] = [
      'id' => $p['product_id'],
      'name' => $p['product_name'],
      'market' => $p['name'],
      'category' => $p['category'],
      'price' => (int)$p['price'],
      'stock' => (int)$p['stock'],
      'sold' => (int)$p['sold'],
      'status' => $status,
      'badgeClass' => $badgeClass,
      'level' => $level,
    ];
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tracking - SUPPLYGO</title>
  <link rel="stylesheet" href="tracking.css" />
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
        <a href="tracking.php" class="active">Tracking</a>
        <a href="history.php">History</a>
        <a href="logout.php" class="logout"><button>Log out</button></a>
      </nav>
    </div>
  </header>

  <main>
    <section class="section summary">
      <div class="section-header">
        <h2>Inventory &amp; Sales Tracker</h2>
        <div class="controls controls--chips">
          <button class="chip" aria-pressed="true">Hari ini</button>
          <button class="chip">Minggu ini</button>
          <button class="chip">Bulan ini</button>
          <button class="chip">90 Hari</button>
        </div>
      </div>

      <div class="metrics">
        <div class="card metric-card">
          <h3>Total Penjualan</h3>
          <p class="mono">Rp <?= number_format($total_penjualan, 0, ',', '.') ?></p>
          <div class="metric-note is-up">▲ +15% vs kemarin</div>
        </div>
        <div class="card metric-card">
          <h3>Barang Terjual</h3>
          <p class="mono"><?= $barang_terjual ?> pcs</p>
          <div class="metric-note is-up">▲ +9% vs periode lalu</div>
        </div>
        <div class="card metric-card">
          <h3>Produk Aktif</h3>
          <p class="mono"><?= $produk_aktif ?></p>
          <div class="metric-note is-neutral">Dalam Katalog</div>
        </div>
      </div>
    </section>

    <section class="section products">
      <div class="section-header">
        <h2>Daftar Produk</h2>
        <div class="controls">
          <div class="control-search">
            <input type="search" placeholder="Cari Produk / SKU">
          </div>
          <a href="product_create.php" class="btn">+ Tambah Produk</a>
          <div class="controls-group">
            <button class="chip" aria-pressed="true">Semua</button>
            <button class="chip">Stok Rendah</button>
            <button class="chip">Terlaris</button>
          </div>
        </div>
      </div>

      <div class="table">
        <table>
          <thead>
            <tr>
              <th>PRODUK</th>
              <th>MARKET</th>
              <th>KATEGORI</th>
              <th>HARGA</th>
              <th>STOK</th>
              <th>TERJUAL</th>
              <th>STATUS</th>
              <th>LEVEL</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($products)): ?>
              <tr>
                <td colspan="8">Belum ada produk di database.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($products as $p): ?>
                <tr>
                  <td><?= $p['name'] ?></td>
                  <td><?= $p['market'] ?></td>
                  <td><?= $p['category'] ?></td>
                  <td class="mono"><?= number_format($p['price'], 0, ',', '.') ?></td>
                  <td class="mono"><?= $p['stock'] ?></td>
                  <td class="mono"><?= $p['sold'] ?></td>
                  <td>
                    <span class="badge <?= $p['badgeClass'] ?>"><?= $p['status'] ?></span>
                  </td>
                  <td>
                    <div class="progress" aria-label="Level Stok" aria-valuenow="<?= $p['level'] ?>" aria-valuemin="0" aria-valuemax="100">
                      <i class="progress-fill" style="width: <?= $p['level'] ?>%"></i>
                    </div>
                  </td>
                  <td class="actions">
                    <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
                    <form action="product_delete.php" method="post" class="inline" onsubmit="return confirm('Yakin hapus produk ini?');">
                      <input type="hidden" name="id" value="<?= $p['id'] ?>">
                      <button type="submit" class="btn btn-delete">Hapus</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="section charts">
      <h2>Grafik &amp; Ringkasan</h2>
      <div class="chart-grid">
        <div class="card chart-card">
          <div class="chart-title">Top 5 Penjualan (periode)</div>
        </div>
        <div class="card chart-card">
          <div class="chart-title">Komposisi Penjualan per Kategori</div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>