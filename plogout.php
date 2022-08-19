<?php
//save as plogout.php
include('connection.php');
include('functions.php');
session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");
$logout = getTimestamp();
$token = $_SESSION['logintoken'];
$sql = "UPDATE auditlog SET audit_logout = '".$logout."' WHERE atoken = '".$token."'";
$result = mysqli_query($conn,$sql);
session_unset();
session_destroy();
header("Location: index.php?logout");
?>
