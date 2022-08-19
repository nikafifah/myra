<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyRA Quick Search</title>
  <?php include('stylelinks.php')?>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
        <?php if(!isset($_SESSION['userlogged']) || $_SESSION['userlogged'] !=1) { ?>
        <a href="login.php" class="nav-link">LOG IN</a>
        <?php } else { 
          $icon = "";
          if($_SESSION['USER_ROLE'] == 2) {
            $icon = '<i class="fas fa-user-gear"></i>';
          } else {
            $icon = '<i class="fas fa-user-tie"></i>';
          } ?>
          <li class="nav-link d-none d-sm-inline-block"><?php echo $icon." ".$_SESSION['USER_NAME'];?></li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="home.php" class="nav-link" >HOME</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="plogout.php" class="nav-link">LOG OUT <i class="fas fa-power-off" style="padding-left:5px;padding-right:5px"></i></a>
          </li>
        <?php } ?>
</ul>
      </li>
    </ul>
</nav>
  <!-- /.navbar -->
<div>
  <div class="wrapper">
    <!-- Main content -->
    <section class="content" style="margin-top:10%">
        <div class="container-fluid">
            <h2 class="text-center display-4">MyRA QuickSearch</h2>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="index.php" method="POST">
                        <div class="input-group">
                            <input type="text" id="keyword" name="keyword" class="form-control form-control-lg" placeholder="Type your keywords here" value="<?php if(isset($_POST['keyword'])) { echo $_POST['keyword']; }?>">
                            <div class="input-group-append">
                                <button type="submit" id="search" name="search" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        include('connection.php');
        if(isset($_POST['search'])) {
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['keyword'] != NULL) {
              $keyword = $_POST['keyword'];
              $newkeyword = str_replace(" ","|",$keyword);
              $sql = "INSERT INTO auditsearch(search_keyword) VALUES ('".$keyword."')";
              $result = mysqli_query($conn,$sql);
        ?>
        <div class="card-body table1">
          <table id="example2" class="table table-bordered table-hover table2">
            <thead>
              <tr>
                <th>#</th>
                <th>Section Order</th>
                <th>Section Title</th>
                <th>Sub-Section Title</th>
                <th>Term Title</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sqlsearch = "SELECT s.section_no, s.section_order, s.section_malay, s.section_english, sb.subsection_malay, sb.subsection_english, t.term_malay, t.term_english, t.ttoken, s.date_deleted, sb.date_deleted, t.date_deleted FROM term t JOIN subsection sb ON t.subsection_no = sb.subsection_no JOIN section s ON sb.section_no = s.section_no WHERE (t.term_malay REGEXP '$newkeyword' OR t.term_english REGEXP '$newkeyword') AND (t.date_deleted IS NULL AND sb.date_deleted IS NULL AND s.date_deleted IS NULL)";
              $resultsearch = mysqli_query($conn,$sqlsearch);    
              $count = 1;          
              ?>
              <?php 
              while($d = mysqli_fetch_assoc($resultsearch)) { ?>
                <tr>
                  <td><?php echo $count++; ?></td>
                  <td><?php echo $d['section_order']?></td>
                  <td><?php echo $d['section_malay'] . " / " . $d['section_english']; ?></td>
                  <td><?php echo $d['subsection_malay'] . " / " . $d['subsection_english']; ?></td>
                  <td><?php echo $d['term_malay'] . " / " . $d['term_english'];?></td>
                  <td>
                    <div class="btn-group">
                      <button type="button" id="view" name="view" class="btn btn-primary btnn-block btn-sm fas fa-eye" onclick="window.location.href='viewsearch.php?id=<?php echo $d['ttoken']; ?>'"></button>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th>#</th>
                <th>Section Order</th>
                <th>Section Title</th>
                <th>Sub-Section Title</th>
                <th>Term Title</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
          <?php }
        } ?>
        </div>
        <?php } ?>
    </section>
  </div>
</div>
<div class="modal fade" id="logout">
  <div class="modal-dialog">
    <div class="modal-content bg-secondary">
      <div class="modal-header">
        <h4 class="modal-title">Logout</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You have successfully logged out.</p>
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
                <p>Wrong search result.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>
<?php include('scripts.php');?>

<!-- page script -->
<?php if (isset($_GET['logout'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#logout").modal("show");
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
<script>
$(document).ready(function() {
    $("#show_hide_user_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_user_password input').attr("type") == "text"){
            $('#show_hide_user_password input').attr('type', 'user_password');
            $('#show_hide_user_password i').addClass( "fa-eye" );
            $('#show_hide_user_password i').removeClass( "fa-eye-slash" );
        }else if($('#show_hide_user_password input').attr("type") == "user_password"){
            $('#show_hide_user_password input').attr('type', 'text');
            $('#show_hide_user_password i').removeClass( "fa-eye" );
            $('#show_hide_user_password i').addClass( "fa-eye-slash" );
        }
    });
});    
</script>
<script>
$(function () {
  $("#example1").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "bJQueryUI":true,
    "bSort":true,
    "bSortable": true,
    "bPaginate":true,
    "sPaginationType":"full_numbers",
      "iDisplayLength": 10
  });
});
</script>
<script>
$(document).ready(function(){
          $("#search").click(function(){
             $(".card-body table1").hide();
        });
 });
</script>

<?php
$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
if($pageWasRefreshed ) {
  unset($keyword);
  unset($newkeyword);
  unset($_POST["search"]);
} ?>
</body>
</html>
