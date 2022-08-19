<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("connection.php");
include("functions.php");
if(isset($_POST['login'])) {
  $USER_ID = mysqli_real_escape_string($conn,$_POST['USER_ID']);
  $USER_PASSWORD = mysqli_real_escape_string($conn,$_POST['USER_PASSWORD']);
  
  // i-staff portal api
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt_array($curl, array(
    CURLOPT_PORT => "444",
    CURLOPT_URL => "https://integrasi.uitm.edu.my:444/stars/login/json/".$USER_ID,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n\t\"password\": \"".$USER_PASSWORD."\"\n}",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "postman-token: a5f640ca-aedf-6572-f4ef-b6ae06cad9eb",
      "token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiY2xhc3Nib29raW5nIn0._dTe9KRNSHSBMybfC4Gs6Brv6vO2HxQ8CWp9lOtI0hk"
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);
  
  $json = json_decode($response, TRUE);
  if(isset($json['status']) && $json['status'] != NULL) {
    if($json['status'] == "true") {
      $sql3 = "SELECT * FROM user_assigned ua JOIN user u ON ua.USER_ID = u.USER_ID JOIN user_role ur ON ua.role_no = ur.role_no WHERE u.USER_ID = '".$USER_ID."' AND ua.access_no = 1 AND ua.assigned_deleted IS NULL";
      echo $sql3;
      $qry3 = mysqli_query($conn,$sql3);
      $row3 = mysqli_num_rows($qry3);
      if($row3 > 0) {
        $re2 = mysqli_fetch_assoc($qry3);
        session_start();
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_NAME'] = $re2['USER_NAME'];
        $_SESSION['USER_ROLE'] = $re2['role_no'];
        $_SESSION['USER_ROLENAME'] = $re2['role_name'];
        $_SESSION['userlogged'] = 1;
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = getTimestamp();
        function get_ip() {
            if(isset($_SERVER['HTTP_CLIENT_IP'])) {
              return $_SERVER['HTTP_CLIENT_IP'];
            } else if(isset($_SERVER['HTTP_X-FORWARD_FOR'])) {
              return $_SERVER['HTTP_X-FORWARD_FOR'];
            } else {
              return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            }
        }
        $ip=get_ip();
        $token = generateToken(100);
        $_SESSION['logintoken'] = $token;
        if(isset($_SESSION['USER_ID'])) {
          $sql = "INSERT into auditlog(audit_login, audit_ip, USER_ID, atoken, role_no) VALUES ('".$date."','".$ip."','".$_SESSION['USER_ID']."','".$token."','".$_SESSION['USER_ROLE']."')";
          $result = mysqli_query($conn,$sql);
        }
        header("Location: home.php?success");
      } 
    } else if($json['status'] == "false") {
      header("Location: login.php?error");
      // echo "<script type= 'text/javascript'>window.location='login.php?error';</script> ";
      // echo "tak ok";
    }
  }
  // end i-staff portal api 
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyRA Quick Search</title>
  <?php include('stylelinks.php')?>

</head>
<body class="hold-transition login-page" style="margin-top:5%">

  <div class="login-logo">
    <a href="index.php"><b>MyRA</b> QuickSearch</a>
    </div>
  <div class="card" style="height: 65vh; width:60vh">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in using <b>UiTM i-Staff Portal</b> account to start your session.</p>
      <div style="text-align: center; margin-bottom:10px">
          <img src="myralogo.png" alt="Myra Logo" class="brand-image img-circle elevation-3;" style="opacity: .9; width: 150px;" >
      </div>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-group mb-3">
          <input name="USER_ID" id="USER_ID" type="text" class="form-control" placeholder="Staff ID">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="USER_PASSWORD" id="USER_PASSWORD" type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button name="login" type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
<div class="modal fade" id="loginerror">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Warning</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Unable to log into the system.<br>Please check your <strong>Staff ID</strong> and <strong>Password</strong>.</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('scripts.php');?>

<!-- page script -->
<?php if (isset($_GET['error'])){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#loginerror").modal("show");
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
</body>
</html>
