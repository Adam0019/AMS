<?php
include('config/dbcon.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Signup Form </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <form action="signup_check.php" method="POST">
      <h2>Signup</h2>
        <div class="input-field">
        <input type="text" id="u_name" name="u_name" required>
        <label for="u_name">Enter your name</label>
    </div>
    <div class="input-field">
        <input type="email" id="u_email" name="u_email" required>
        <label for="u_email">Enter your email</label>
    </div>
    <!-- <div class="input-field">
        <input type="phone" id="u_phone" name="u_" required>
        <label for="u_phone">Enter your phone</label>
    </div> -->
      <div class="input-field">
        <input type="password" id="password" data-type="password" name="password" required>
        <label for="password">Enter your password</label>
      </div>
      <input type="submit" class="button" value="Sign Up" name="submit">
      <div class="register">
        <p>Already have an account? <a href="index.php">Login</a></p>
      </div>
    </form>
  </div>
</body>
</html>