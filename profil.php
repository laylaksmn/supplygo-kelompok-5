<?php
  require_once 'auth.php';
  require_once 'conn.php';

  $uploadDir = './uploadsPP/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $user = $_SESSION['user'];
  $result = $mysqli->query("SELECT * FROM user WHERE email = '$user'");
  $userData = $result->fetch_assoc();
  $name = $userData['name'];
  $profilepicture = $userData['imagepath'];
  $aboutme = $userData['aboutme'] ?? '';
  $address = $userData['address'] ?? '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteaccount'])) {
      if ($profilepicture !== $uploadDir . 'defaultprofile.jpg' && file_exists($profilepicture)) {
          unlink($profilepicture);
      }

      $deleteProducts = $mysqli->prepare("DELETE FROM products WHERE user_id = ?");
      $deleteProducts->bind_param("i", $userData['user_id']);
      $deleteProducts->execute();
      $deleteProducts->close();
          
      $delete = $mysqli->prepare("DELETE FROM user WHERE email=?");
      $delete->bind_param("s", $user);
      $delete->execute();
      $delete->close();
        
      session_destroy();
      header("Location: index.php");
      exit();
    }
      
    $name = addslashes(trim($_POST['name']));
    $aboutme = addslashes(trim($_POST['aboutme']));
    $address = addslashes(trim($_POST['address']));
    $save = $profilepicture;
      
    if (isset($_POST['deletephoto'])) {
      $save = $uploadDir . 'defaultprofile.jpg';
    } else if (isset($_FILES['profilepicture']) && $_FILES['profilepicture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilepicture']['tmp_name'];
        $fileName = time() . '_' . $_FILES['profilepicture']['name'];
        $save = $uploadDir . $fileName;
        move_uploaded_file($fileTmpPath, $save);
    }
    
    $stmt = $mysqli->prepare("UPDATE user SET name=?, aboutme=?, address=?, imagepath=? WHERE email=?");
    $stmt->bind_param("sssss", $name, $aboutme, $address, $save, $user);
    $stmt->execute();
    $stmt->close();
    
    header("Location: profil.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>SUPPLGO - <?= $name ?></title>
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
        <a href="logout.php" class="logout"><button>Log out</button></a>
      </nav>
    </div>
  </header>

  <main>
    <div class="profile-container">
      <form action="profil.php" method="post" enctype="multipart/form-data">
      <img src="<?= $profilepicture ?>" id="profilepicturepreview" />
      <input type="file" name="profilepicture" id="profilepicture" style="display:none;" accept="image/*" /><br>

      <label for="name">Name</label><br>
      <input type="text" id="name" name="name" value="<?= $name ?>"><br>

      <label for="aboutme">About Me</label><br>
      <textarea id="aboutme" name="aboutme" placeholder="Write something about your account"><?= htmlspecialchars($aboutme) ?></textarea><br>

      <label for="address">Address</label><br>
      <textarea id="address" name="address"placeholder="Add your pick up address"><?= htmlspecialchars($address) ?></textarea><br>

      <div class="photo-actions" style="display:none;">
        <button type="button" id="addphoto" class="add-btn">Change Photo Profile</button>
        <button type="submit" name="deletephoto" id="deletephoto" class="delete-btn">Delete Photo Profile</button>
      </div>
      <div class="profile-actions">
        <button type="submit" class="edit">Save Changes</button>
        <button type="submit" name="deleteaccount" id="deleteaccount" class="delete-account">Delete Account</button>
      </div>
    </form>

    </div>
  </main>

  <script>
    const file = document.getElementById('profilepicture');
    const preview = document.getElementById('profilepicturepreview');
    const actions = document.querySelector('.photo-actions');
    const addphoto = document.getElementById('addphoto');
    const deleteAccountBtn = document.getElementById('deleteaccount');

    preview.addEventListener('click', () => {
      actions.style.display = 'block';
    });
    addphoto.addEventListener('click', () => {
      file.click();
    });

    file.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });

    deleteAccountBtn.addEventListener('click', function(e) {
        if (!confirm("Are you sure you want to delete your account?")) {
            e.preventDefault();
        }
    });
  </script>
</body>
</html>