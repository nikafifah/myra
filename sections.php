<?php
session_start();
if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) {
  header("Location: login.php");
}
if($_SESSION['USER_ROLE'] != 2) {
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
              <li class="breadcrumb-item active">Sections</li>
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
                <h3 class="card-title">Active Sections</h3>
                <a href="addsection.php"><button type="submit" class="card-title btn btn-warning float-right">Add Section</button></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Section Order</th>
                    <th>Section (Malay)</th>
                    <th>Section (English)</th>
                    <th>Date Created</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $conn = mysqli_connect("localhost", "root", "", "myra");
                    if($conn-> connect_error){
                        die("Connection failed::". $conn-> connect_error);
                    }

                    $sql = "SELECT * FROM section WHERE date_deleted IS NULL ORDER BY section_order";
                    $result = $conn->query($sql);
                    $counter = 1;
                    if($result-> num_rows > 0){
                        while ($row = $result-> fetch_assoc()){
                          $token = $row['stoken'];
                          ?>
                          <tr>
                            
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $row['section_order']; ?></td>
                            <td><?php echo $row['section_malay']; ?></td>
                            <td><?php echo $row['section_english']; ?></td>
                            <td><?php echo $row['date_created']; ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" onclick="window.location.href='viewsection.php?id=<?php echo $token; ?>'" class="btn btn-primary btnn-block btn-sm fas fa-eye"></button>
                                <?php if($row['USER_ID'] == $_SESSION['USER_ID']) { ?>
                                <button type="button" onclick="window.location.href='editsection.php?id=<?php echo $token; ?>'" class="btn btn-secondary btnn-block btn-sm fas fa-edit"></button>
                                <!-- <button type="button" onclick="window.location.href='deletesection.php?id=<?php echo $token; ?>'" class="btn btn-danger btnn-block btn-sm fas fa-trash-can"></button> -->
                                <button type="button" data-href="pdeletesection.php?id=<?php echo $token; ?>" class="btn btn-danger btnn-block btn-sm fas fa-trash-can" data-toggle="modal" data-target="#delete"></button>
                                <?php } ?>
                              </div>
                              <div class="modal fade" id="delete">
                                <div class="modal-dialog">
                                  <div class="modal-content bg-danger">
                                      <div class="modal-header">
                                        <h4 class="modal-title">Delete Section</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>Do you want to delete this section?</p>
                                      </div>
                                      <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                        <a class="btn btn-outline-light btn-delete">Delete</a>
                                      </div>
                                  </div>
                                </div>
                            </div>
                            </td>
                          </tr>
                          <?php
                        }
                    }
                    ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Section Order</th>
                    <th>Section (Malay)</th>
                    <th>Section (English)</th>
                    <th>Date Created</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
              
            </div>
            <div class="card card-primary">
              <div class="card-header">
                  <h3 class="card-title">Deleted Sections</h3>
              </div>
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Section Order</th>
                    <th>Section (Malay)</th>
                    <th>Section (English)</th>
                    <th>Date Deleted</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $conn = mysqli_connect("localhost", "root", "", "myra");
                    if($conn-> connect_error){
                        die("Connection failed::". $conn-> connect_error);
                    }

                    $sql = "SELECT * FROM section WHERE date_deleted IS NOT NULL ORDER BY section_order";
                    $result = $conn->query($sql);
                    $counter = 1;
                    if($result-> num_rows > 0){
                        while ($row = $result-> fetch_assoc()){
                          $token = $row['stoken'];
                          ?>
                          <tr>
                            
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $row['section_order']; ?></td>
                            <td><?php echo $row['section_malay']; ?></td>
                            <td><?php echo $row['section_english']; ?></td>
                            <td><?php echo $row['date_deleted']; ?></td>
                            <td>
                              <div class="btn-group">
                                <button type="button" onclick="window.location.href='viewsection.php?id=<?php echo $token; ?>'" class="btn btn-primary btnn-block btn-sm fas fa-eye"></button>
                                <?php if($row['USER_ID'] == $_SESSION['USER_ID']) { ?>
                                <button type="button" data-href="prestoresection.php?id=<?php echo $token; ?>" class="btn btn-secondary btnn-block btn-sm fas fa-plus-square" data-toggle="modal" data-target="#restore"></button>
                                <?php } ?>
                              </div>
                              <div class="modal fade" id="restore">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-secondary">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Restore</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to restore this section?</p>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                            <a class="btn btn-outline-light btn-restore">Restore</a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <?php
                        }
                    }
                    ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Section Order</th>
                    <th>Section (Malay)</th>
                    <th>Section (English)</th>
                    <th>Date Deleted</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
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
<!-- modal -->
<div class="modal fade" id="sectionadded">
    <div class="modal-dialog">
        <div class="modal-content bg-success">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>New section has been successfully added!</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="sectionedited">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Section has been successfully updated!</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="sectionrestored">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Section has been successfully restored!</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>                

  <div class="modal fade" id="sectiondeleted">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Section has been successfully deleted!</p>
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
                <p>No sections were added.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="restricted">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Something went wrong...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Wrong section.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

<div class="modal fade" id="deleteerror">
  <div class="modal-dialog">
      <div class="modal-content bg-danger">
          <div class="modal-header">
              <h4 class="modal-title">Error</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p>Delete was unsuccessful.</p>
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="restoreerror">
  <div class="modal-dialog">
      <div class="modal-content bg-danger">
          <div class="modal-header">
              <h4 class="modal-title">Error</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p>Restore was unsuccessful.</p>
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
  <!-- /modal  -->
  <?php include('scripts.php');?>

  <script>
    $('#restore').on('show.bs.modal', function(e) {
        $(this).find('.btn-restore').attr('href', $(e.relatedTarget).data('href'));
    });
    $('#delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-delete').attr('href', $(e.relatedTarget).data('href'));
    });
  </script>

<!-- page script -->
<!-- modal -->
<?php if (isset($_GET['empty'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#nodata").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['deletefail'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#deleteerror").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['restorefail'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#restoreerror").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['exists'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sectionexists").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['restored'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sectionrestored").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['added'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sectionadded").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['edited'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sectionedited").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['deleted'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#sectiondeleted").modal("show");
    });
    </script>
<?php } ?>
<?php if (isset($_GET['restrict'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#restricted").modal("show");
    });
    </script>
<?php } ?>
<!-- /modal  -->

<!-- Page specific script -->
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $('table.table#example2').DataTable({
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
