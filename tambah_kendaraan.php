<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $driver = $_POST['driver'];
    $status = $_POST['status'];
    $estimation = $_POST['estimation'] ?? '';

    // Upload gambar
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
    $targetFile = $targetDir . basename($_FILES["kendaraan_image"]["name"]);
    move_uploaded_file($_FILES["kendaraan_image"]["tmp_name"], $targetFile);

    $query = "INSERT INTO kendaraan (name, type, capacity, driver, status, estimation, kendaraan_image_path)
              VALUES ('$name', '$type', '$capacity', '$driver', '$status', '$estimation', '$targetFile')";
    if ($mysqli->query($query)) {
        header("Location: transport.php");
        exit();
    } else {
        echo "Gagal menyimpan data: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Kendaraan - SUPPLYGO</title>
  <style>
    button {
      background-color: white;
      color: black;
      border: 2px solid #ff8800;
      padding: 6px 14px;
      cursor: pointer;
      font-weight: bold;
      border-radius: 5px;
      transition: 0.2s;
    }

    button:hover,
    button:active {
      background-color: #ff8800;
      color: white;
    }
  </style>
</head>
<body>
  <h1>Tambah Kendaraan Baru</h1>

  <form action="" method="POST" enctype="multipart/form-data">
    <table border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td><label for="name">Nama Kendaraan</label></td>
        <td><input type="text" name="name" required></td>
      </tr>

      <tr>
        <td><label for="type">Tipe Kendaraan</label></td>
        <td>
          <select name="type" required>
            <option value="Truck">Truck</option>
            <option value="Van">Van</option>
            <option value="Pickup">Pickup</option>
          </select>
        </td>
      </tr>

      <tr>
        <td><label for="capacity">Kapasitas</label></td>
        <td><input type="text" name="capacity" required></td>
      </tr>

      <tr>
        <td><label for="driver">Pengemudi</label></td>
        <td><input type="text" name="driver"></td>
      </tr>

      <tr>
        <td><label for="status">Status</label></td>
        <td>
          <select name="status" required>
            <option value="Tersedia">Tersedia</option>
            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
          </select>
        </td>
      </tr>

      <tr>
        <td><label for="kendaraan_image">Foto Kendaraan</label></td>
        <td><input type="file" name="kendaraan_image" accept="image/*"></td>
      </tr>

      <tr>
        <td colspan="2" align="center">
          <button type="submit">Simpan</button>
          <button type="button" onclick="window.location.href='transport.php'">Batal</button>
        </td>
      </tr>
    </table>
  </form>
</body>
</html>
