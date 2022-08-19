<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_POST['submit'])) {
  $section_no = $_POST['section_no'];
  $subsection_order = $_POST['subsection_order'];
  $subsection_m = $_POST['subsection_malay'];
  $subsection_e = $_POST['subsection_english'];
  $subsection_desc = $_POST['subsection_desc'];
  $Cdate = getTimestamp();
  $Update = getTimestamp();
  $user_id = $_SESSION['USER_ID'];
  $token = generateToken(100);

  function checkSubs($conn,$subsection_order,$section_no) {
    $found = false;
    $sql = "SELECT sb.subsection_order, s.section_no FROM subsection sb JOIN section s ON sb.section_no = sb.section_no WHERE sb.date_deleted IS NULL AND sb.subsection_order = '".$subsection_order."' AND sb.section_no = '".$section_no."'";
    $qry = mysqli_query($conn,$sql);
    $row = mysqli_num_rows($qry);

    if($row > 0) {
      $found = true;
    }
    return $found;
  }

  try {
    if(($subsection_m != "" || $subsection_m != NULL || $subsection_e != "" || $subsection_e != NULL ) && $section_no != NULL) {
      if(checkSubs($conn,$subsection_order,$section_no) == TRUE) {
        // echo "<script type= 'text/javascript'>alert('Record already exists.');window.location='addsubsection.php';</script>";
        header("Location: addsubsection.php?exists");
      } else {
        $sql = "INSERT INTO subsection(subsection_order,subsection_malay, subsection_english, subsection_desc, date_created, date_updated, section_no, USER_ID, sbtoken) VALUES('".$subsection_order."','".$subsection_m."','".$subsection_e."','".$subsection_desc."','".$Cdate."','".$Update."','".$section_no."','".$user_id."','".$token."')";
        $result = mysqli_query($conn,$sql);
    
        $sql2 = "SELECT * FROM subsection WHERE sbtoken = '$token'";
        $result2 = mysqli_query($conn,$sql2);
        $row = mysqli_fetch_assoc($result2);
      
        $sql3 = "INSERT INTO subsection_history (subsection_no, USER_ID, subs_process) VALUES ('".$row['subsection_no']."', '".$user_id."', 'ADD')";
        $result3 = mysqli_query($conn,$sql3);
        
        if($result == TRUE) {
          // echo "<script type= 'text/javascript'>alert('New record successfully saved');window.location='subsections.php';</script> ";
          header("Location: subsections.php?added");
        } else {
          // echo "<script type= 'text/javascript'>alert('Record unsuccessfully saved);</script> ";
          header("Location: addsubsection.php?addfail");
        }
      }
    } else {
      header("Location: addsubsection.php?empty");
    }
    
  } catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}
} else {
  header("Location: subsections.php");
}
?>
