<?php
include("connection.php");
include("functions.php");
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

if($json['status'] == "true")
{
    $sql3 = "SELECT * FROM myra.user_access WHERE USER_ID = '".$USER_ID."' and access_no = 1";
    $qry3 = mysqli_query($conn,$sql3);
    $row3 = mysqli_num_rows($qry3);
    if($row3 > 0)
    {
        $re2 = mysqli_fetch_assoc($qry3);
        session_start();
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_NAME'] = $re2['USER_NAME'];
        $_SESSION['USER_EMAIL'] = $re2['USER_EMAIL'];
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = date('Y/m/d H:i:s');
        function get_ip()
        {
            if(isset($_SERVER['HTTP_CLIENT_IP']))
            {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            else if(isset($_SERVER['HTTP_X-FORWARD_FOR']))
            {
                return $_SERVER['HTTP_X-FORWARD_FOR'];
            }   
            else
            {
                return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            }
        }
        $ip=get_ip();
        $token = generateToken(10);

        @ $hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if($_SESSION['USER_ID']=$USER_ID)
        {
           $sqlNewLogin = "INSERT into auditlog(audit_no, audit_login, audit_logout, audit_ip, USER_ID, token) VALUES (NULL,'".$date."','".$date."',$ip,'".$re2['USER_ID']."',$token)";
           $queryNewLogin=mysqli_query($conn
           ,$sqlNewLogin) or die ("Error: ". mysqli_error($conn));
           $_SESSION['logintime']=$date;
        }  
        echo "<script language='javascript'>alert('User exist.');window.location='Dashboard_StaffHEA.php';</script>";
    }
    else
    {
        echo "<script language='javascript'>alert('User does not have access to the system.');window.location='1.php';</script>";
    }
}
else if($json['status'] == "false")
{
    echo "<script language='javascript'>alert('USER does not exist.');window.location='index.php';</script>";
}

/*$sql2 = "select * from classbook_backup_jengka.vw_staff_phg where USER_ID = '".$USER_ID."' and USER_PASSWORD = '".$USER_PASSWORD."'";
$qry2 = mysqli_query($conn2,$sql2);
$row2 = mysqli_num_rows($qry2);
if($row2 > 0)
{
    $sql3 = "select * from group_aizat.user_access where USER_ID = '".$USER_ID."' and appaccessid = 1";
    $qry3 = mysqli_query($conn3,$sql3);
    $row3 = mysqli_num_rows($qry3);
    if($row3 > 0)
    {
        $re2 = mysqli_fetch_assoc($qry3);
        session_start();
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_NAME'] = $re2['USER_NAME'];
        $_SESSION['USER_EMAIL'] = $re2['USER_EMAIL'];
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = date('Y/m/d H:i:s');
        function get_ip()
        {
            if(isset($_SERVER['HTTP_CLIENT_IP']))
            {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            else if(isset($_SERVER['HTTP_X-FORWARD_FOR']))
            {
                return $_SERVER['HTTP_X-FORWARD_FOR'];
            }   
            else
            {
                return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            }
        }
        $ip=get_ip();
        @ $hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if($_SESSION['USER_ID']=$USER_ID)
        {
           $sqlNewLogin = "INSERT into group_aizat.auditloginstaf (USER_ID,logintime,browser) VALUES ('".$re2['USER_ID']."','".$date."','".$_SERVER['HTTP_USER_AGENT']."')";
           $queryNewLogin=mysqli_query($conn3,$sqlNewLogin) or die ("Error: ". mysqli_error($conn3));
           $_SESSION['logintime']=$date;
        }  
        echo "<script language='javascript'>alert('User exist.');window.location='Dashboard_StaffHEA.php';</script>";   
    }
    else
    {
        echo "<script language='javascript'>alert('User does not have access to the system.');window.location='1.php';</script>";
    }
}
else
{
    echo "<script language='javascript'>alert('USER does not exist.');window.location='index.php';</script>";
}
*/
?>

