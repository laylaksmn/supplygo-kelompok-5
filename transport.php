<?php
include 'conn.php';

if (isset($_GET['konfirmasi_id'])) {
    $id = $_GET['konfirmasi_id'];
    $mysqli->query("UPDATE kendaraan SET status='Dalam Perjalanan' WHERE kendaraan_id='$id'");
    header("Location: dashboard.php?kendaraan_id=$id");
    exit();
}

if (isset($_GET['hapus_id'])) {
    $hapusId = $_GET['hapus_id'];
    $mysqli->query("DELETE FROM kendaraan WHERE kendaraan_id='$hapusId'");
    header("Location: transport.php");
    exit();
}

$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filterType = isset($_GET['type']) ? $_GET['type'] : '';

$query = "SELECT * FROM kendaraan WHERE 1";
if ($filterStatus != '') {
    $query .= " AND status='$filterStatus'";
}
if ($filterType != '') {
    $query .= " AND type='$filterType'";
}
$query .= " ORDER BY kendaraan_id DESC";

$kendaraanResult = $mysqli->query($query);

// Statistik
$totalKendaraan = $mysqli->query("SELECT COUNT(*) AS total FROM kendaraan")->fetch_assoc()['total'];
$tersedia = $mysqli->query("SELECT COUNT(*) AS total FROM kendaraan WHERE status='Tersedia'")->fetch_assoc()['total'];
$dalamPerjalanan = $mysqli->query("SELECT COUNT(*) AS total FROM kendaraan WHERE status='Dalam Perjalanan'")->fetch_assoc()['total'];

// Ambil tipe kendaraan unik
$tipeResult = $mysqli->query("SELECT DISTINCT type FROM kendaraan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transport Management - SUPPLYGO</title>
  <link rel="stylesheet" href="transport.css">
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">
        <img src="logo Web.png" alt="Logo" class="logo-img">
        <h2>SUPPLYGO</h2>
      </div>
      <nav>
        <a href="dashboard.php">Home</a>
        <a href="market.php">Market</a>
        <a href="transport.php" class="active">Transport</a>
        <a href="tracking.php">Tracking</a>
        <a href="history.php">History</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="content-wrapper">
      <div class="page-header">
        <h1>Transport Management</h1>
        <p>Kelola dan pilih jenis armada Anda</p>
      </div>

      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Kendaraan</h3>
          <p class="stat-number orange"><?php echo $totalKendaraan; ?></p>
        </div>
        <div class="stat-card">
          <h3>Tersedia</h3>
          <p class="stat-number green"><?php echo $tersedia; ?></p>
        </div>
        <div class="stat-card">
          <h3>Dalam Perjalanan</h3>
          <p class="stat-number blue"><?php echo $dalamPerjalanan; ?></p>
        </div>
      </div>

      <form method="GET" class="filter-section">
        <div class="filters">
          <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="Tersedia" <?php if($filterStatus=='Tersedia') echo 'selected'; ?>>Tersedia</option>
            <option value="Dalam Perjalanan" <?php if($filterStatus=='Dalam Perjalanan') echo 'selected'; ?>>Dalam Perjalanan</option>
          </select>

          <select name="type" class="filter-select">
            <option value="">Semua Jenis Kendaraan</option>
            <?php while($row = $tipeResult->fetch_assoc()): ?>
              <option value="<?php echo $row['type']; ?>" <?php if($filterType==$row['type']) echo 'selected'; ?>>
                <?php echo $row['type']; ?>
              </option>
            <?php endwhile; ?>
          </select>

          <button type="submit" class="btn-filter">Cari</button>
        </div>
        <button type="button" class="btn-add" onclick="window.location.href='tambah_kendaraan.php'">+ Tambah Kendaraan</button>
      </form>

      <div class="vehicle-grid">
        <?php if ($kendaraanResult->num_rows > 0): ?>
          <?php while($kendaraan = $kendaraanResult->fetch_assoc()): ?>
            <div class="vehicle-card">
              <div class="vehicle-image">
                <img src="<?php echo $kendaraan['kendaraan_image_path'] ?: 'truck-placeholder.png'; ?>" alt="<?php echo $kendaraan['name']; ?>">
              </div>
              <div class="vehicle-info">
                <div class="vehicle-header">
                  <h3><?php echo $kendaraan['name']; ?></h3>
                  <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $kendaraan['status'])); ?>">
                    <?php echo $kendaraan['status']; ?>
                  </span>
                </div>
                <p class="vehicle-type"><?php echo $kendaraan['type']; ?></p>
                <div class="vehicle-details">
                  <div class="detail-row"><span>Kapasitas:</span><span><?php echo $kendaraan['capacity']; ?></span></div>
                  <div class="detail-row"><span>Pengemudi:</span><span><?php echo $kendaraan['driver'] ?: 'Belum ditentukan'; ?></span></div>
                  <div class="detail-row"><span></span><span><?php echo $kendaraan['estimation'] ?: '-'; ?></span></div>
                </div>

                <?php if ($kendaraan['status'] == 'Tersedia'): ?>
                  <form method="GET" action="transport.php" class="confirm-form" onsubmit="return confirm('Konfirmasi kendaraan ini untuk berangkat?');">
                    <input type="hidden" name="pilih_id" value="<?php echo $kendaraan['kendaraan_id']; ?>">
                    <button type="submit" class="btn-pilih">Pilih</button>
                  </form>
                <?php endif; ?>

                <form method="GET" action="transport.php" class="delete-form" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?');">
                  <input type="hidden" name="hapus_id" value="<?php echo $kendaraan['kendaraan_id']; ?>">
                  <button type="submit" class="btn-hapus">🗑 Hapus</button>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="no-data">Tidak ada kendaraan ditemukan.</p>
        <?php endif; ?>
      </div>
    </div>
  </main>
</body>
</html>
