<?php
include('config/dbcon.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Login Form </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <form action="login_check.php" method="POST">
      <h2>Login</h2>
        <div class="input-field">
        <input type="text" id="u_name" name="username" class="input" required>
        <label>Enter your username</label>
      </div>
      <div class="input-field">
        <input type="password" id="password" data-type="password" name="password" class="input"required>
        <label>Enter your password</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Remember me</p>
        </label>
        <a href="#">Forgot password?</a>
      </div>
      <input type="submit" class="button" value="Login" name="submit">
      <div class="register">
        <p>Don't have an account? <a href="signup.php">Register</a></p>
      </div>
    </form>
  </div>
</body>
</html>