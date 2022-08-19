<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$Delete = getTimestamp();
$token = mysqli_real_escape_string($conn,$_GET['id']);
try {
    $sql = "UPDATE user_assigned SET access_no = '0',assigned_deleted = '".$Delete."',assigned_updated = '".$Delete."' WHERE utoken = '".$token."'";
    // $sql = "DELETE FROM user_assigned WHERE utoken = '".$token."'";

    $result = mysqli_query($conn,$sql);

    if ($result == TRUE) {
        // echo "<script type= 'text/javascript'>alert('Record successfully deleted');window.location='users.php';</script> ";
        header("Location: users.php?deleted");
    } else {
        // echo "<script type= 'text/javascript'>alert('Delete unsuccessful);</script> ";
        header("Location: users.php?deletefail");

    }
} catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}

?>