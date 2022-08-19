<?php
session_start();
include("connection.php");
include("functions.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if(isset($_POST['submit'])) {
    $section_no = $_POST['section_no'];
    $term_order = $_POST['term_order'];
    $term_m = $_POST['term_malay'];
    $term_e = $_POST['term_english'];
    $term_desc = $_POST['term_desc'];
    $Cdate = getTimestamp();
    $Update = getTimestamp();
    $subsection_no = $_POST['subsection_no'];
    $user_id = $_SESSION['USER_ID'];
    $token = generateToken(100);

    function checkTerm($conn,$term_order,$subsection_no,$section_no) {
        $found = false;
        $sql = "SELECT t.term_order, sb.subsection_no, s.section_no FROM term t JOIN subsection sb ON sb.subsection_no = t.subsection_no JOIN section s ON s.section_no = sb.section_no WHERE t.date_deleted IS NULL AND t.term_order='".$term_order."' AND sb.subsection_no = '".$subsection_no."' AND s.section_no = '".$section_no."'";
        $qry = mysqli_query($conn,$sql);
        $row = mysqli_num_rows($qry);
    
        if($row > 0) {
          $found = true;
        }
        return $found;
    }

    try {
        if(($term_m != "" || $term_m != NULL || $term_e != "" || $term_e != NULL ) && $subsection_no != NULL) {
            if(checkTerm($conn,$term_order,$subsection_no,$section_no) == TRUE) {
                // echo "<script type= 'text/javascript'>alert('Record already exists.');window.location='addsubsection.php';</script> ";
                header("Location: addterm.php?exists");
            } else {
                $sql = "INSERT INTO term(term_order, term_malay, term_english, term_desc, date_created, date_updated, subsection_no, USER_ID, ttoken) VALUES('".$term_order."','".$term_m."','".$term_e."','".$term_desc."','".$Cdate."','".$Update."','".$subsection_no."','".$user_id."','".$token."')";
                $result = mysqli_query($conn,$sql);
    
                $sql2 = "SELECT * FROM term WHERE ttoken = '$token'";
                $result2 = mysqli_query($conn,$sql2);
                $row = mysqli_fetch_assoc($result2);
                
                $sql3 = "INSERT INTO term_history (term_no, USER_ID, term_process) VALUES ('".$row['term_no']."', '".$user_id."', 'ADD')";
                $result3 = mysqli_query($conn,$sql3);
    
                if ($result == TRUE) {
                    // echo "<script type= 'text/javascript'>alert('New record successfully saved');window.location='terms.php';</script> ";
                    header("Location: terms.php?added");
                } else {
                    // echo "<script type= 'text/javascript'>alert('Record unsuccessfully saved);</script> ";
                    header("Location: addterm.php?addfail");
                }
            }
        } else {
            header("Location: addterm.php?empty");
        }
        
        
    } catch (Exception $e) { echo "Error!: ". $e->getMessage(). "<br>"; die();}
    
} else {
    header("Location: terms.php");
}
?>