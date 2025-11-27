<?php
session_start();

// 1. Redirect Saat Sudah Login
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'conn.php';
    // Menggunakan Prepared Statement
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT password, username FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_object();

    if (!$userData || !password_verify($password_raw, $userData->password)){
      // 2. Cek password dengan password_verify()
      header('Location: login.php?invalid=1');
      die;
    }
    
    // Set sesi dengan username
    $_SESSION['user'] = [
      'id' => $userData->user_id,
      'username' => $userData->username,
      'name' => $userData->name,
      'role' => $userData->role
    ];
    header("Location: dashboard.php");
    die;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SUPPLYGO</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-container">
    <h2><span class="orange">LOG</span> IN</h2>
    <form method="post" action="">
      <label>Email</label>
      <input type="email" name="email" required placeholder="Enter your email">

      <label>Password</label>
      <input type="password" name="password" required placeholder="Enter password">

      <button type="submit" class="btn-login">LOG IN</button>
      <?php if (isset($_GET['invalid'])): ?>
      <p class="error">Email atau password salah</p>
      <?php endif; ?>

      <p class="signup-text"> 
        Don't have an account? <a href="signup.php">Create an account</a>
      </p>
    </form>
  </div>
</body>
</html>
