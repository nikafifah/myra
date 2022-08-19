<?php
session_start();
if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
if($_SESSION['USER_ROLE'] != 1) {
  header("Location: home.php?noaccess");
}

$conn = mysqli_connect("localhost", "root", "", "myra");
if($conn-> connect_error){
    die("Connection failed::". $conn-> connect_error);
}

$utoken = mysqli_real_escape_string($conn,$_GET['id']);
$sql = "SELECT * FROM user u JOIN user_assigned ua ON u.USER_ID = ua.USER_ID JOIN user_role ur ON ua.role_no = ur.role_no JOIN user_access uc ON ua.access_no = uc.access_no WHERE ua.utoken = '".$utoken."'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if($row) {
  $USER_ID = $row['USER_ID'];
  $USER_NAME = $row['USER_NAME'];
  $role_no = $row['role_no'];
  $role_name = $row['role_name'];
  $access_no = $row['access_no'];
  $access_status = $row['access_status'];
} else {
  header("Location: users.php?restrict");
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
              <li class="breadcrumb-item active">Users</li>
              <li class="breadcrumb-item active">Edit User</li>
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
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Edit User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="pedituser.php?id=<?php echo $utoken; ?>" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="USER_ID">Staff ID</label>
                    <input type="text" class="form-control" id="USER_ID" name="USER_ID" style="width:10em" value="<?php echo $USER_ID; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="USER_NAME">Staff Name</label>
                    <input type="text" class="form-control" id="USER_NAME" name="USER_NAME" value="<?php echo $USER_NAME; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="role_no">Role</label>
                    <?php
                    $query1 = "SELECT * FROM user_role";
                    $result1 = mysqli_query($conn,$query1);
                    ?>
                    <select class="form-control select2" style="width: 15em;" id="role_no" name="role_no">
                      <option value="<?php echo $role_no; ?>" disabled selected hidden><?php echo $role_name; ?></option>
                      <?php
                      while($role = mysqli_fetch_array($result1,MYSQLI_ASSOC)):;
                      ?>
                      <option value="<?php echo $role['role_no']; ?>"><?php echo $role['role_name']; ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="access_no">Access</label>
                    <?php
                    $query2 = "SELECT * FROM user_access";
                    $result2 = mysqli_query($conn,$query2);
                    ?>
                    <select class="form-control select2" style="width: 15em;" id="access_no" name="access_no">
                      <option value="<?php echo $access_no; ?>" disabled selected hidden><?php echo $access_status; ?></option>
                      <?php
                      while($access = mysqli_fetch_array($result2,MYSQLI_ASSOC)):;
                      ?>
                      <option value="<?php echo $access["access_no"];?>"><?php echo $access["access_status"]; ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-primary">Submit</button>
                  <button id="cancel" class="btn btn-default" >Cancel</button>
                </div>

                <div class="modal fade" id="modal-primary">
                  <div class="modal-dialog">
                    <div class="modal-content bg-secondary">
                      <div class="modal-header">
                        <h4 class="modal-title">Edit User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>Do you want to proceed?</p>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit" name="submit" class="btn btn-outline-light">Submit</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
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

<!-- modal -->

<div class="modal fade" id="error">
  <div class="modal-dialog">
      <div class="modal-content bg-danger">
          <div class="modal-header">
              <h4 class="modal-title">Error</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p>Update was unsuccessful.</p>
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>

<!-- /modal -->


<?php include('scripts.php');?>

<!-- page script -->

<?php if (isset($_GET['editfail'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#error").modal("show");
    });
    </script>
<?php } ?>

</body>
</html>
