<?php
session_start();
include('connection.php');
$ttoken = mysqli_real_escape_string($conn, $_GET['id']);
// $sql = "SELECT * FROM term t JOIN subsection sb ON t.subsection_no = sb.subsection_no JOIN section s ON sb.section_no = s.section_no WHERE t.ttoken = '".$ttoken."'";
$sql = "SELECT t.*, t.date_deleted AS 'term_deleted', sb.*, sb.date_deleted AS 'subsection_deleted', s.*, s.date_deleted AS 'section_deleted' FROM term t JOIN subsection sb ON t.subsection_no = sb.subsection_no JOIN section s ON sb.section_no = s.section_no WHERE t.date_deleted IS NULL AND sb.date_deleted IS NULL AND s.date_deleted IS NULL AND t.ttoken = '".$ttoken."'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if($row) {
    $section = $row['section_order'] . " - " . $row['section_malay'] . " / " . $row['section_english'];
    $section_desc = $row['section_desc'];
    $subsection = $row['subsection_order'] . " - " . $row['subsection_malay'] . " / " . $row['subsection_english'];
    $subsection_desc = $row['subsection_desc'];
    $term_malay = $row['term_malay'];
    $term_english = $row['term_english'];
    $term_desc = $row['term_desc'];
} else {
    header("Location: index.php");
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
    <section class="content" style="margin-top:5%">
        <div class="container-fluid">
            <h2 class="text-center display-4">MyRA QuickSearch</h2>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">View Search</h3>
                        </div>
                        <form action="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="section">Section</label>
                                    <output class="form-control" id="section" name="section"><?php echo $section; ?></output>
                                </div>
                                <div class="form-group">
                                    <label for="section_desc">Section Description</label>
                                    <textarea name="section_desc" id="section_desc" rows="5" class="form-control" disabled style="resize:none"><?php echo $section_desc;?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="subsection">Sub-Section</label>
                                    <!-- <textarea name="subsection" id="subsection" cols="30" rows="3" class="form-control" disabled style="resize:none"></textarea> -->
                                    <output class="form-control" id="subsection" name="subsection" style="overflow:auto"><?php echo $subsection; ?></output>
                                </div>
                                <div class="form-group">
                                    <label for="subsection_desc">Sub-Section Description</label>
                                    <textarea name="subsection_desc" id="subsection_desc" rows="5" class="form-control" disabled style="resize:none"><?php echo $subsection_desc;?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="term_malay">Term (Malay)</label>
                                    <output class="form-control" id="term_malay" name="term_malay" style="overflow:auto"><?php echo $term_malay;?></output>
                                </div>
                                <div class="form-group">
                                    <label for="term_english">Term (English)</label>
                                    <output class="form-control" id="term_english" name="term_english" style="overflow:auto"><?php echo $term_english;?></output>
                                </div>
                                <div class="form-group">
                                    <label for="term_desc">Term Description</label>
                                    <textarea name="term_desc" id="term_desc" rows="5" class="form-control" disabled style="resize:none"><?php echo $term_desc;?></textarea>
                                </div>
                        </div>
                        <div class="card-footer">
                            <button  id="back" class="btn btn-default">Back</button>
                            <script type="text/javascript">document.getElementById("back").onclick = function(){location.href = "index.php";};</script>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
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
<?php include('scripts.php');?>

<!-- page script -->
<?php if (isset($_GET['logout'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#logout").modal("show");
    });
    </script>
<?php } ?>
<script>
  $(function () {
    // Summernote
    $('textarea#section_desc').summernote('disable');
    $('textarea#subsection_desc').summernote('disable'); 
    $('textarea#term_desc').summernote('disable');

    
    })
</script>
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
