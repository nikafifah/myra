<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$Delete = getTimestamp();
$token = mysqli_real_escape_string($conn,$_GET['id']);
$user_id = $_SESSION['USER_ID'];
try {
    $sql = "UPDATE term SET date_deleted = '".$Delete."',date_updated = '".$Delete."' WHERE ttoken = '".$token."'";
    $result = mysqli_query($conn,$sql);

    $sql2 = "SELECT * FROM term WHERE ttoken = '$token'";
    $result2 = mysqli_query($conn,$sql2);
    $row = mysqli_fetch_assoc($result2);
    
    $sql3 = "INSERT INTO term_history (term_no, USER_ID, term_process) VALUES ('".$row['term_no']."', '".$user_id."', 'DELETE')";
    $result3 = mysqli_query($conn,$sql3);

    if ($result == TRUE) {
        // echo "<script type= 'text/javascript'>alert('Record successfully deleted');window.location='terms.php';</script> ";
        header("Location: terms.php?deleted");
    } else {
        // echo "<script type= 'text/javascript'>alert('Delete unsuccessful);</script> ";
        header("Location: terms.php?deletefail");
    }
} catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}
?>