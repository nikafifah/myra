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
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <?php
          $sql1 = "SELECT * FROM user_assigned WHERE role_no = 1 AND assigned_deleted IS NULL";
          $result1 = mysqli_query($conn,$sql1);
          $row1 = mysqli_num_rows($result1);
          $sql2 = "SELECT * FROM user_assigned WHERE role_no = 2 AND assigned_deleted IS NULL";
          $result2 = mysqli_query($conn,$sql2);
          $row2 = mysqli_num_rows($result2);
          $sql3 = "SELECT * FROM section WHERE date_deleted IS NULL";
          $result3 = mysqli_query($conn,$sql3);
          $row3 = mysqli_num_rows($result3);
          $sql4 = "SELECT * FROM subsection sb JOIN section s ON s.section_no = sb.section_no WHERE sb.date_deleted IS NULL AND s.date_deleted IS NULL";
          $result4 = mysqli_query($conn,$sql4);
          $row4 = mysqli_num_rows($result4);
          $sql5 = "SELECT * FROM term t JOIN subsection sb ON sb.subsection_no = t.subsection_no JOIN section s ON s.section_no = sb.section_no WHERE t.date_deleted IS NULL AND sb.date_deleted IS NULL AND s.date_deleted IS NULL";
          $result5 = mysqli_query($conn,$sql5);
          $row5 = mysqli_num_rows($result5);
          ?>
          <?php if($_SESSION['USER_ROLE'] == 1) { ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $row1; ?></h3>
                <p style="font-size: 20px;"><strong>Total Administrators</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tie"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $row2; ?></h3>
                <p style="font-size: 20px;"><strong>Total Moderators</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-gear"></i>
              </div>
            </div>
          </div>
          <?php } ?>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $row3; ?></h3>
                <p style="font-size: 20px;"><strong>Total Sections</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-folder"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $row4; ?></h3>
                <p style="font-size: 20px;"><strong>Total Sub-Sections</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-folder-open"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo $row5; ?></h3>
                <p style="font-size: 20px;"><strong>Total Terms</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-book"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->

      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <!-- Line chart -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Monthly Searches
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>

                </div>
              </div>
              <div class="card-body">
                <!-- <div id="line-chart" style="height: 300px;"></div> -->
                <form action="home.php" class="form-horizontal" method="post">
                  <div class="form-group row">
                  <select class="form-control" style="width:10em" name="year" id="year">
                    <?php
                    if(isset($_POST['year'])) {
                      echo getSelectedListYears($_POST['year']);
                    } else {
                      echo getSelectedListYears(date("Y"));
                    }
                    ?>
                  </select>
                  <input type="submit" value="Search" class="btn btn-primary" style="margin-left:10px">
                  </div>
                  
                </form>
                    
                <canvas id="myChart" style="margin-top:20px"></canvas>

              </div>
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

  <div class="modal fade" id="loginsuccess">
    <div class="modal-dialog">
        <div class="modal-content bg-success">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have successfully logged in!</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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
<?php
if(isset($_POST['year'])) {
  $year = $_POST['year'];
} else {
  $year = date("Y");
}

// January
$sqljan = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 1 AND YEAR(search_date) = '".$year."'";
$resjan = mysqli_query($conn,$sqljan);
$rowjan = mysqli_fetch_assoc($resjan);
$jan = $rowjan['total'];
// February
$sqlfeb = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 2 AND YEAR(search_date) = '".$year."'";
$resfeb = mysqli_query($conn,$sqlfeb);
$rowfeb = mysqli_fetch_assoc($resfeb);
$feb = $rowfeb['total'];
// March
$sqlmac = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 3 AND YEAR(search_date) = '".$year."'";
$resmac = mysqli_query($conn,$sqlmac);
$rowmac = mysqli_fetch_assoc($resmac);
$mac = $rowmac['total'];
// April
$sqlapr = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 4 AND YEAR(search_date) = '".$year."'";
$resapr = mysqli_query($conn,$sqlapr);
$rowapr = mysqli_fetch_assoc($resapr);
$apr = $rowapr['total'];
// May
$sqlmay = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 5 AND YEAR(search_date) = '".$year."'";
$resmay = mysqli_query($conn,$sqlmay);
$rowmay = mysqli_fetch_assoc($resmay);
$may = $rowmay['total'];
// June
$sqljun = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 6 AND YEAR(search_date) = '".$year."'";
$resjun = mysqli_query($conn,$sqljun);
$rowjun = mysqli_fetch_assoc($resjun);
$jun = $rowjun['total'];
// July
$sqljul = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 7 AND YEAR(search_date) = '".$year."'";
$resjul = mysqli_query($conn,$sqljul);
$rowjul = mysqli_fetch_assoc($resjul);
$jul = $rowjul['total'];
// August
$sqlaug = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 8 AND YEAR(search_date) = '".$year."'";
$resaug = mysqli_query($conn,$sqlaug);
$rowaug = mysqli_fetch_assoc($resaug);
$aug = $rowaug['total'];
// September
$sqlsep = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 9 AND YEAR(search_date) = '".$year."'";
$ressep = mysqli_query($conn,$sqlsep);
$rowsep = mysqli_fetch_assoc($ressep);
$sep = $rowsep['total'];
// October
$sqloct = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 10 AND YEAR(search_date) = '".$year."'";
$resoct = mysqli_query($conn,$sqloct);
$rowoct = mysqli_fetch_assoc($resoct);
$oct = $rowoct['total'];
// November
$sqlnov = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 11 AND YEAR(search_date) = '".$year."'";
$resnov = mysqli_query($conn,$sqlnov);
$rownov = mysqli_fetch_assoc($resnov);
$nov = $rownov['total'];
// December
$sqldec = "SELECT count(*) AS total FROM auditsearch WHERE MONTH(search_date) = 12 AND YEAR(search_date) = '".$year."'";
$resdec = mysqli_query($conn,$sqldec);
$rowdec = mysqli_fetch_assoc($resdec);
$dec = $rowdec['total'];

/* $sqltotal = "SELECT count(*) AS total FROM auditsearch";
$restotal = mysqli_query($conn,$sqltotal);
$rowtotal = mysqli_fetch_assoc($restotal);
$total = $rowtotal['total'];
if($total < 100) {
  $total = 100;
} else {
  $total = $total;
} */
?>
<!-- Page specific script -->
<script>
var xValues = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var yValues = [<?php echo $jan;?>,<?php echo $feb;?>,<?php echo $mac;?>,<?php echo $apr;?>,<?php echo $may;?>,<?php echo $jun;?>,<?php echo $jul;?>,<?php echo $aug;?>,<?php echo $sep;?>,<?php echo $oct;?>,<?php echo $nov;?>,<?php echo $dec;?>];

new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    scales: {
    yAxes: [{
        ticks: {min: 0, max: yValues.max},
        scaleLabel: {
            display: true,
            labelString: 'NUMBER OF SEARCHES'
        }}],
    xAxes: [{
        scaleLabel: {
            display: true,
            labelString: 'MONTH'
        }}]
    }
  }
});
</script>
</body>
</html>
