<?php
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include 'inc/header.php';
?>
<?php
include 'inc/footer.php';
?>