<?php
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include 'inc/header.php';
?>

<div class="container">
  <h1>Welcome to the Dashboard</h1>
  <p>Hello, <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'User'; ?>! You are logged in.</p>
</div>

<?php

include 'inc/footer.php';
?>