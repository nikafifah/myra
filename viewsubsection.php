<?php
session_start();
if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
if($_SESSION['USER_ROLE'] != 2) {
  header("Location: home.php?noaccess");
}
$conn = mysqli_connect("localhost", "root", "", "myra");
if($conn-> connect_error){
    die("Connection failed::". $conn-> connect_error);
}

$sbtoken = mysqli_real_escape_string($conn,$_GET['id']);
$sql = "SELECT * FROM subsection sb JOIN section s ON sb.section_no = s.section_no WHERE sb.sbtoken = '".$sbtoken."'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if($row) {
  $section_no = $row['section_order'] . " - " . $row['section_malay'];
  $subsection_order = $row['subsection_order'];
  $subsection_malay = $row['subsection_malay'];
  $subsection_english = $row['subsection_english'];
  $subsection_desc = $row['subsection_desc'];  
} else {
  header("Location: subsections.php?restrict");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyRA Quick Search</title>
  <?php include('stylelinks.php')?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->

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
              <li class="breadcrumb-item active">Subsections</li>
              <li class="breadcrumb-item active">View Subsection</li>
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
          <div class="col-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Sub-Section Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="paddsubsection.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="section_no">Section</label>
                    <output class="form-control" id="section_no" name="section_no"><?php echo $section_no; ?></output>
                  </div>
                  <div class="form-group">
                    <label for="subsection_order">Sub-Section Order</label>
                    <output class="form-control" id="subsection_order" name="subsection_order" style="width:4em"><?php echo $subsection_order; ?></output>
                  </div>
                  <div class="form-group">
                    <label for="subsection_malay">Sub-Section (Malay)</label>
                    <output class="form-control" id="subsection_malay" name="subsection_malay"><?php echo $subsection_malay; ?></output>
                  </div>
                  <div class="form-group">
                    <label for="subsection_english">Sub-Section (English)</label>
                    <output class="form-control" id="subsection_english" name="subsection_english"><?php echo $subsection_english; ?></output>
                  </div>
                  <div class="form-group">
                    <label for="subsection_desc">Sub-Section Description</label>
                    <textarea class="form-control" rows="5" id="summernote" name="subsection_desc" style="resize:none" disabled><?php echo $subsection_desc; ?></textarea>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button  id="back" class="btn btn-default">Back</button>
                  <script type="text/javascript">document.getElementById("back").onclick = function(){location.href = "subsections.php";};</script>
                </div>
              </form>
            </div>
            <!-- /.card -->
            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



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
<script>
  $(function () {
    // Summernote
    $('#summernote').summernote('disable');  })
</script>

</body>
</html>