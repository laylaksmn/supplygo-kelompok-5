<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>SUPPLYGO - <?= $user['name'] ?></title>
  <link rel="stylesheet" href="profil.css">
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
      <a href="history.php">History</a>
      <a href="logout.php"><button>Log out</button></a>
    </nav>
  </div>
</header>

<main>
  <div class="profile-container">

    <form action="profil.php" method="POST" enctype="multipart/form-data">

      <img src="<?= $user['imagepath'] ?>" id="profilepicturepreview">

      <input type="file" name="profilepicture" id="profilepicture" style="display:none;" accept="image/*" />

      <label>Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">

      <label>About Me</label>
      <textarea name="aboutme"><?= htmlspecialchars($user['aboutme']) ?></textarea>

      <label>Address</label>
      <textarea name="address"><?= htmlspecialchars($user['address']) ?></textarea>

      <div class="profile-actions">
        <button type="submit" class="edit">Save Changes</button>
        <button type="submit" name="deleteaccount" class="delete-account">Delete Account</button>
      </div>
    </form>
  </div>
</main>

</body>
</html>