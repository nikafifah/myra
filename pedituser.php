<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_POST['submit'])) {
    $token = mysqli_real_escape_string($conn,$_GET['id']);
    $sql = "SELECT role_no, access_no FROM user_assigned WHERE utoken = '".$token."'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    $oldrole = $row['role_no'];
    $oldaccess = $row['access_no'];
    $role_no = $_POST['role_no'];
    $access_no = $_POST['access_no'];
    if($role_no == NULL || $role_no  == "") {
        $role_no = $oldrole;
    }
    
    if($access_no == NULL || $access_no  == "") {
        $access_no = $oldaccess;
    } 

    $update = getTimestamp();
    try {
        $sql = "UPDATE user_assigned SET role_no = '".$role_no."', access_no = '".$access_no."', assigned_updated = '".$update."' WHERE utoken = '".$token."'";
        $result = mysqli_query($conn,$sql);

        if ($result == TRUE) {
            // echo "<script type= 'text/javascript'>alert('Record successfully updated');window.location='users.php';</script> ";
            header("Location: users.php?edited");
        } else {
            // echo "<script type= 'text/javascript'>alert('Update unsuccessful);</script> ";
            header("Location: edituser.php?editfail");
        }
    } catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}  
} else {
    header("Location: users.php");
}
?>