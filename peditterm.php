<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_POST['submit'])) {
    $term_m = $_POST['term_malay'];
    $term_e = $_POST['term_english'];
    $term_desc = $_POST['term_desc'];
    $term_order = $_POST['term_order'];
    $Update = getTimestamp();
    $token = mysqli_real_escape_string($conn,$_GET['id']);
    $user_id = $_SESSION['USER_ID'];
    try {
        if($term_m != "" || $term_m != NULL || $term_e != "" || $term_e != NULL) {
            $sql = "UPDATE term SET term_order = '".$term_order."',term_malay = '".$term_m."', term_english = '".$term_e."', term_desc = '".$term_desc."', date_updated = '".$Update."' WHERE ttoken = '".$token."'";
            $result = mysqli_query($conn,$sql);

            $sql2 = "SELECT * FROM term WHERE ttoken = '$token'";
            $result2 = mysqli_query($conn,$sql2);
            $row = mysqli_fetch_assoc($result2);
            
            $sql3 = "INSERT INTO term_history (term_no, USER_ID, term_process) VALUES ('".$row['term_no']."', '".$user_id."', 'EDIT')";
            $result3 = mysqli_query($conn,$sql3);

            if ($result == TRUE) {
                // echo "<script type= 'text/javascript'>alert('Record successfully updated');window.location='terms.php';</script> ";
                header("Location: terms.php?edited");
            } else {
                // echo "<script type= 'text/javascript'>alert('Update unsuccessful);</script> ";
                header("Location: editterm.php?editfail");
            }
        } else {
            header("Location: editterm.php?empty");
        }
    } catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}
} else {
    header("Location: terms.php");
}
?>