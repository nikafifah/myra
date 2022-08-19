<?php
session_start();
if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
if($_SESSION['USER_ROLE'] != 1) {
  header("Location: home.php?noaccess");
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
              <li class="breadcrumb-item active">Audit</li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Audit Login/Logout</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Staff No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>IP Address</th>
                    <th>Login</th>
                    <th>Logout</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $conn = mysqli_connect("localhost", "root", "", "myra");
                    if($conn-> connect_error){
                        die("Connection failed::". $conn-> connect_error);
                    }

                    $sql = "SELECT u.USER_ID,u.USER_NAME,ur.role_name,a.audit_ip,a.audit_login,a.audit_logout FROM auditlog a JOIN user u ON a.USER_ID = u.USER_ID JOIN user_assigned ua ON a.USER_ID = ua.USER_ID JOIN user_role ur ON a.role_no = ur.role_no ORDER BY audit_no DESC";
                    $result = $conn->query($sql);
                    $counter = 1;
                    if($result-> num_rows > 0){
                        while ($row = $result-> fetch_assoc()){
                          ?>
                          <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $row['USER_ID']; ?></td>
                            <td><?php echo $row['USER_NAME']; ?></td>
                            <td><?php echo $row['role_name']; ?></td>
                            <td><?php echo $row['audit_ip']; ?></td>
                            <td><?php echo $row['audit_login']; ?></td>
                            <td><?php echo $row['audit_logout']; ?></td>

                            <!-- <td>
                              <div class="btn-group">
                                  <button type="button" name="view" id="view" onclick="window.location.href='viewuser.php?id=<?php echo $row['utoken']; ?>'" class="btn btn-primary btnn-block btn-sm fas fa-eye"></button>
                                  <button type="button" name="edit" id="edit" onclick="window.location.href='edituser.php?id=<?php echo $row['utoken']; ?>'" class="btn btn-secondary btnn-block btn-sm fas fa-edit"></button>
                                  <button type="button" name="delete" id="delete" onclick="window.location.href='deleteuser.php?id=<?php echo $row['utoken']; ?>'" class="btn btn-danger btnn-block btn-sm fas fa-trash-can"></button>
                              </div>
                            </td> -->
                          </tr>
                          <?php
                        }
                    }
                    ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Staff No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>IP Address</th>
                    <th>Login</th>
                    <th>Logout</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            
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
<!-- Page specific script -->
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
</body>
</html>
