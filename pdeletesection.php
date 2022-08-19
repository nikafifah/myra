<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$Delete = getTimestamp();
$token = mysqli_real_escape_string($conn,$_GET['id']);
$user_id = $_SESSION['USER_ID'];
try {
    $sql = "UPDATE section SET date_deleted = '".$Delete."', date_updated = '".$Delete."' WHERE stoken = '".$token."'";
    $result = mysqli_query($conn,$sql);

    $sql2 = "SELECT * FROM section WHERE stoken = '$token'";
    $result2 = mysqli_query($conn,$sql2);
    $row = mysqli_fetch_assoc($result2);
    
    $sql3 = "INSERT INTO section_history (section_no, USER_ID, sec_process) VALUES ('".$row['section_no']."', '".$user_id."', 'DELETE')";
    $result3 = mysqli_query($conn,$sql3);

    if ($result == TRUE) {
        // echo "<script type= 'text/javascript'>alert('Record successfully deleted');window.location='sections.php';</script> ";
        header("Location: sections.php?deleted");
    } else {
        // echo "<script type= 'text/javascript'>alert('Delete unsuccessful);</script> ";
        header("Location: sections.php?deletefail");
    }
} catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}

?>