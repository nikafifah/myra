<?php
include("connection.php");
session_start();
if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
if($_SESSION['USER_ROLE'] != 1) {
  header("Location: home.php?noaccess");
}
$_SESSION['name'] = "";
$_SESSION['id'] = "";

if(isset($_POST['search'])) {
  $id = $_POST['id'];
  $sql = "SELECT * FROM user WHERE USER_ID = '".$id."'" ;
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result);
  if($row > 0) {
    $_SESSION['name'] = $row['USER_NAME'];
    $_SESSION['id'] = $row['USER_ID'];
  } else {
    // echo "<script type= 'text/javascript'>alert('Incorrect Staff ID.');</script> "; 
    header("Location: adduser.php?wrong");
    $_SESSION['id'] = "";
    $_SESSION['name'] = "";
  }
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
              <li class="breadcrumb-item active">Add User</li>
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
                <h3 class="card-title">Search ID</h3>
              </div>
              <form method="post" class="form-horizontal" action="adduser.php">
                <div class="card-body">
                  <label for="id">Search Staff ID</label>
                    <div class="form-group row">
                      <div class="col-sm-2">
                        <input type="text" name="id" id="id" class="form-control" placeholder="Search Staff ID" style="width:10em" required>
                      </div>
                      <button class="btn btn-primary" type="submit" id="search" name="search">Search ID</button>
                    </div>
                </div>
              </form>
            </div>
            <script>
              if ( window.history.replaceState ) {
                  window.history.replaceState( null, null, window.location.href );
                }
            </script>
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->

              <form class="form-horizontal" action="padduser.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="USER_ID">Staff ID</label>
                    <input type="text" class="form-control" name="USER_ID" id="USER_ID" placeholder="Staff ID" value="<?php echo $_SESSION['id'] ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="USER_NAME">Staff Name</label>
                    <input type="text" class="form-control" name="USER_NAME" id="USER_NAME" placeholder="Staff Name" value="<?php echo $_SESSION['name'] ?>" disabled > 
                  </div>
                  <div class="form-group">
                    <label for="role_no">Role</label>
                    <?php
                    $query2 = "SELECT * FROM user_role";
                    $result2 = mysqli_query($conn,$query2);
                    ?>
                    <select class="form-control select2" style="width: 15em;" id="role_no" name="role_no">
                      <!-- <option value="" disabled selected>Select a Role</option> -->
                      <?php
                      while($role = mysqli_fetch_array($result2,MYSQLI_ASSOC)):;
                      ?>
                      <option value="<?php echo $option = $role["role_no"];?>"><?php echo $role["role_name"]; ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="access_no">Access</label>
                    <?php
                    $query3 = "SELECT * FROM user_access";
                    $result3 = mysqli_query($conn,$query3);
                    ?>
                    <select class="form-control select2" style="width: 15em;" id="access_no" name="access_no">
                      <!-- <option value="" disabled selected>Access Type</option> -->
                      
                      <?php
                      while($access = mysqli_fetch_array($result3,MYSQLI_ASSOC)):;
                      ?>
                      <option value="<?php echo $option = $access["access_no"];?>"><?php echo $access["access_status"]; ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addconfirm">Submit</button>
                  <button id="cancel" class="btn btn-default" >Cancel</button>
                </div>

                <div class="modal fade" id="addconfirm">
                  <div class="modal-dialog">
                    <div class="modal-content bg-primary">
                      <div class="modal-header">
                        <h4 class="modal-title">Add User</h4>
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
<div class="modal fade" id="userwrong">
    <div class="modal-dialog">
        <div class="modal-content bg-warning">
            <div class="modal-header">
                <h4 class="modal-title">Warning</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Incorrect Staff ID</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="userexists">
    <div class="modal-dialog">
        <div class="modal-content bg-warning">
            <div class="modal-header">
                <h4 class="modal-title">Warning</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>User already exists.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

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
                <p>Role assign was unsuccessful.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

<?php include('scripts.php');?>

<!-- /modal -->
<!-- page script -->
<?php if (isset($_GET['wrong'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#userwrong").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['exists'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#userexists").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['addfail'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#error").modal("show");
    });
    </script>
<?php } ?>


</body>
</html>
