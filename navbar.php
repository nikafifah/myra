<?php

$icon = "";
if($_SESSION['USER_ROLE'] == 2) {
  $icon = '<i class="fas fa-user-gear"></i>';
} else {
  $icon = '<i class="fas fa-user-tie"></i>';
}
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<!-- Left navbar links -->
<ul class="navbar-nav">
  <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="home.php" class="nav-link">HOME</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="index.php" class="nav-link">SEARCH</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="contact.php" class="nav-link">CONTACT</a>
  </li>
</ul>
<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
  <li class="nav-item d-none d-sm-inline-block" style="padding-right: 10px;"><?php echo $icon;?></li>
  <li class="nav-item d-none d-sm-inline-block" style="padding-right: 15px;"><?php echo $_SESSION['USER_NAME'];?></li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="plogout.php" class="d-block">LOG OUT <i class="fas fa-power-off" style="padding-left:5px;padding-right:5px"></i></a>
  </li>
</ul>
</nav>
