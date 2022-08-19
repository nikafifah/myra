<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_POST['submit'])) {
    $section_order = $_POST['section_order'];
    $section_m = $_POST['section_malay'];
    $section_e = $_POST['section_english'];
    $section_desc = $_POST['section_desc'];
    $Update = getTimestamp();
    $token = mysqli_real_escape_string($conn,$_GET['id']);
    $user_id = $_SESSION['USER_ID'];
    try {
        if($section_m != "" || $section_m != NULL || $section_e != "" || $section_e != NULL) {
            $sql = "UPDATE section SET section_order = '".$section_order."',section_malay = '".$section_m."', section_english = '".$section_e."', section_desc = '".$section_desc."', date_updated = '".$Update."' WHERE stoken = '".$token."'";
            $result = mysqli_query($conn,$sql);

            $sql2 = "SELECT * FROM section WHERE stoken = '$token'";
            $result2 = mysqli_query($conn,$sql2);
            $row = mysqli_fetch_assoc($result2);
            
            $sql3 = "INSERT INTO section_history (section_no, USER_ID, sec_process) VALUES ('".$row['section_no']."', '".$user_id."', 'EDIT')";
            $result3 = mysqli_query($conn,$sql3);

            if ($result == TRUE) {
                // echo "<script type= 'text/javascript'>alert('Record successfully updated');window.location='sections.php';</script> ";
                header("Location: sections.php?edited");
            } else {
                // echo "<script type= 'text/javascript'>alert('Update unsuccessful);</script> ";
                header("Location: editsection.php?editfail");
            }
        } else {
            header("Location: editsection.php?empty");
        }
        
    } catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}
    
} else {
    header("Location: sections.php");
}
?>