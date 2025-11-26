<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'conn.php';
    $email = addslashes(trim($_POST['email']));
    $password = addslashes(trim($_POST['password']));

    $stmt = $mysqli->prepare("SELECT password FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $getPassword = $result->fetch_object();
    if ($password !== $getPassword->password){
      header('Location: login.php?invalid=1');
      die;
    }
    $_SESSION['user'] = $email;
    header("Location: dashboard.php");
    die;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SUPPLYGO</title>
  <link rel="stylesheet" href="gabung.css">
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
      <p class="error">Email or password wrong</p>
      <?php endif; ?>

      <p class="signup-text"> 
        Don't have an account? <a href="signup.php">Create an account</a>
      </p>
    </form>
  </div>
</body>
</html>