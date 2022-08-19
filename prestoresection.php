<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$token = mysqli_real_escape_string($conn,$_GET['id']);
$Update = getTimestamp();
$user_id = $_SESSION['USER_ID'];
try {
    $sql = "UPDATE section SET date_deleted = NULL, date_updated = '".$Update."' WHERE stoken = '".$token."'";
    $result = mysqli_query($conn,$sql);

    $sql2 = "SELECT * FROM section WHERE stoken = '$token'";
    $result2 = mysqli_query($conn,$sql2);
    $row = mysqli_fetch_assoc($result2);
    
    $sql3 = "INSERT INTO section_history (section_no, USER_ID, sec_process) VALUES ('".$row['section_no']."', '".$user_id."', 'RESTORE')";
    $result3 = mysqli_query($conn,$sql3);

    if ($result == TRUE) {
        header("Location: sections.php?restored");
    } else {
        header("Location: sections.php?restorefail");
    }        
} catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die(); } 
?>