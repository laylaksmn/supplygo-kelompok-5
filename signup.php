<?php 
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'conn.php';
    $username = addslashes(trim($_POST['username']));
    $email = addslashes(trim($_POST['email']));
    $password  = addslashes(trim($_POST['password']));
    $confirmPassword = addslashes(trim($_POST['confirmPassword']));

    $uploadDir = './uploadsPP/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }
    $name = $username;
    $profilepicture = $uploadDir . 'defaultprofile.jpg';

   if ($password !== $confirmPassword) {
    header('Location: signup.php?password=1');
    die;
  }
  try {
    $stmt = $mysqli->prepare("INSERT INTO user (username, email, password, name, imagepath) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $password, $name, $profilepicture);
    $stmt->execute();
    $stmt->close();
  }catch(mysqli_sql_exception $exc){
    if ($mysqli->errno === 1062){
      header('Location: signup.php?username=1');
      die;
    }
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up - SUPPLYGO</title>
  <link rel="stylesheet" href="gabung.css">
</head>
<body>
  <div class="signup-container">
    <h2><span class="black">SIGN</span> <span class="orange">UP</span></h2>
    <form id="signupForm" method="POST" action="">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Create a username" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter password" required>

      <label for="confirmPassword">Confirm Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>

      <div class="checkbox">
        <input type="checkbox" id="agree" required>
        <label for="agree">I agree with Terms & Policy</label>
      </div>

      <button type="submit" class="btn-signup">SIGN UP</button>
      <?php if (isset($_GET['username'])): ?>
      <p class="error">Username is unavailable</p>
      <?php endif; ?>
      <?php if (isset($_GET['password'])): ?>
      <p class="error">Password didn't match</p>
      <?php endif; ?>

      <p class="login-text"> 
        Already have an account? <a href="login.php">Login</a>
      </p>
    </form>
</body>
</html>

