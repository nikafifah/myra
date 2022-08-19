
<li class="nav-item">
<a href="home.php" class="nav-link">
  <i class="fa-solid fa-home fas nav-icon"></i>
  <p>
    Home
  </p>
</a>
</li>
<?php if($_SESSION['USER_ROLE'] == 1) { ?>
<li class="nav-item">
  <a href="audit.php" class="nav-link">
    <i class="fas fa-solid fa-user-check nav-icon"></i>
    <p>Audit</p>
  </a>
</li>

<li class="nav-item">
  <a href="users.php" class="nav-link">
    <i class="nav-icon fas fa-solid fa-users"></i>
    <p>Users</p>
  </a>
</li>
<?php } ?>
<?php if($_SESSION['USER_ROLE'] == 2) { ?>
<li class="nav-item">
    <a href="sections.php" class="nav-link">
      <i class="fa-solid fa-folder nav-icon fas"></i>
      <p>Sections</p>
    </a>
</li>

<li class="nav-item">
  <a href="subsections.php" class="nav-link">
    <i class="fa-solid fa-folder-open nav-icon fas"></i>
    <p>Sub-Sections</p>
  </a>  
</li>

<li class="nav-item">
  <a href="terms.php" class="nav-link">
    <i class="fa-solid fa-book nav-icon fas"></i>
    <p>Terms</p>
  </a>
</li>
<?php } ?>

<li class="nav-item">
  <a href="contact.php" class="nav-link">
    <i class="nav-icon fas fa-solid fa-phone"></i>
    <p>Contact</p>
  </a>
</li>

<li class="nav-item">
  <a href="plogout.php" class="nav-link">
    <i class="nav-icon fas fa-solid fa-power-off"></i>
    <p>Log Out</p>
  </a>
</li>