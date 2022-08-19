<?php
session_start();
include('connection.php');
include('functions.php');
date_default_timezone_set("Asia/Kuala_Lumpur");

if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyRA Quick Search</title>
  <?php include('stylelinks.php')?>
  <style>
    img.profile-user-img {
        width: 10em;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>-->

  <!-- Navbar -->
  <?php include('navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="myralogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">MyRA Quick Search</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="" class="d-block">Hi, <?php if(isset($_SESSION['USER_NAME'])) { echo $_SESSION['USER_NAME']; } ?></a>
          <a href="" class="d-block">ROLE: <?php if(isset($_SESSION['USER_ROLENAME'])) { echo $_SESSION['USER_ROLENAME']; } ?></a>
        </div>
      </div>
      <!-- SidebarSearch Form -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php include('menu.php'); ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">MyRA Quick Search</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  <!-- /.content-wrapper -->
   <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid"
                                    src="pics/nik.jpg"
                                    alt="User profile picture" style="border-radius: 15px;">
                            </div>
                            <h3 class="profile-username text-center">NIK NURUL AFIFAH BINTI YAHYA</h3>
                            <p class="text-muted text-center">Project Manager</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Matric No.</b> <a class="float-right">2020458314</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Group</b> <a class="float-right">CS1104F</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">nikafifah712@gmail.com</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">0176568644</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid"
                                    src="pics/hasbi.png"
                                    alt="User profile picture" style="border-radius: 15px;">
                            </div>
                            <h3 class="profile-username text-center">HASBI ARMAN BIN ADNAN</h3>
                            <p class="text-muted text-center">Programmer 1</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Matric No.</b> <a class="float-right">2020617408</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Group</b> <a class="float-right">CS1104F</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">hasbiarman3@gmail.com</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">0122301164</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid"
                                    src="pics/elle.jpg"
                                    alt="User profile picture" style="border-radius: 15px;">
                            </div>
                            <h3 class="profile-username text-center">WAN ELIZ EDLINA BT WAN ZAMANI</h3>
                            <p class="text-muted text-center">Database Designer</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Matric No.</b> <a class="float-right">2020616608</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Group</b> <a class="float-right">CS1104F</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">eliz_edlina@yahoo.com</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">0176039279</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid"
                                    src="pics/chepah.jpg"
                                    alt="User profile picture" style="border-radius: 15px;">
                            </div>
                            <h3 class="profile-username text-center">SHARIFAH ARIFAH BINTI SYED AZHAM SHAH</h3>
                            <p class="text-muted text-center">Programmer 2</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Matric No.</b> <a class="float-right">2020821306</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Group</b> <a class="float-right">CS1104F</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">arifah4azham@gmail.com</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">0139412067</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    

                    
                </div>
            </div>
        </div>
    </section>
  </div>

  <div class="modal fade" id="noaccess">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Something went wrong...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You tried to access a restricted page.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

   <!-- footer -->
   <?php include('version.php'); ?>
  <!-- / footer  -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include('scripts.php');?>

<!-- page script -->
<?php if (isset($_GET['noaccess'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#noaccess").modal("show");
    });
    </script>
<?php } ?>
</body>
</html>
