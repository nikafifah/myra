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

$ttoken = mysqli_real_escape_string($conn,$_GET['id']);
$sql = "SELECT * FROM term t JOIN subsection sb ON t.subsection_no = sb.subsection_no JOIN section s ON sb.section_no = s.section_no WHERE t.ttoken = '".$ttoken."'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if($row) {
  $section_no = $row['section_order'] . " - " . $row['section_malay'];
  $subsection_no = $row['subsection_order'] . " - " . $row['subsection_malay'];
  $term_order = $row['term_order'];
  $term_malay = $row['term_malay'];
  $term_english = $row['term_english'];
  $term_desc = $row['term_desc'];
} else {
  header("Location: terms.php?restrict");
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
              <li class="breadcrumb-item active">Terms</li>
              <li class="breadcrumb-item active">Edit Term</li>
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
                <h3 class="card-title">Edit Term</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="peditterm.php?id=<?php echo $ttoken; ?>" method="post">
                <div class="card-body">
                  <div class="form-group">
                  <label for="section_no">Section</label>
                    <input type="text" class="form-control" id="section_no" style="overflow:auto" name="section_no" value="<?php echo $section_no; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="subsection_no">Sub-Section</label>
                    <input type="text" class="form-control" id="subsection_no" style="overflow:auto" name="subsection_no" value="<?php echo $subsection_no; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="term_order">Term Order</label>
                    <select class="form-control select2" id="term_order" style="width:4em" name="term_order" required>
                      <option value="<?php echo $term_order;?>" selected><?php echo $term_order;?></option>
                      <?php
                      $letters = range('a','z');
                      $letters_used = array();
                      $index = 0;
                      $sql = "SELECT term_order FROM term WHERE date_deleted IS NULL AND subsection_no = '".$row['subsection_no']."' ORDER BY term_order ASC";
                      $terms = mysqli_query($conn,$sql);
                      while($d = mysqli_fetch_assoc($terms)) {
                        $letters_used[$index] = $d['term_order'];
                        $index++; 
                      }
                      $result = array_diff($letters,$letters_used);

                      foreach( $result as $t_order) {
                        echo "<option value='".$t_order."'>$t_order</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="term_malay">Term (Malay)</label>
                    <input type="text" class="form-control" id="term_malay" name="term_malay" value="<?php echo $term_malay; ?>" style="overflow:auto">
                  </div>
                  <div class="form-group">
                    <label for="term_english">Term (English)</label>
                    <input type="text" class="form-control" id="term_english" name="term_english" value="<?php echo $term_english; ?>" style="overflow:auto">
                  </div>
                  <div class="form-group">
                    <label for="term_desc">Term Description</label>
                    <textarea class="form-control" rows="10" id="summernote" name="term_desc" style="resize: none;"><?php echo $term_desc; ?></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
                <!-- <script type="text/javascript">document.getElementById("back").onclick = function(){location.href = "terms.php";};</script> -->

                <div class="card-footer">
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-primary">Submit</button>
                  <button id="cancel" class="btn btn-default" >Cancel</button>
                </div>

                <div class="modal fade" id="modal-primary">
                  <div class="modal-dialog">
                    <div class="modal-content bg-secondary">
                      <div class="modal-header">
                        <h4 class="modal-title">Edit Term</h4>
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

<div class="modal fade" id="nodata">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Something went wrong...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Term titles are empty.</p>
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
<?php if (isset($_GET['empty'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#nodata").modal("show");
    });
    </script>
<?php } ?>

</body>
</html>
