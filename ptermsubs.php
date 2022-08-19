<?php 
// Include the database config file 
include_once 'connection.php'; 
 
if(!empty($_POST["section_no"])){ 
    // Fetch state data based on the specific country 
    $query = "SELECT * FROM subsection WHERE section_no = ".$_POST['section_no']." "; 
    $result = $conn->query($query); 
     
    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
        echo '<option value="">Select a Subsection</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['subsection_no'].'">'.$row['subsection_order'].' - '.$row['subsection_malay'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">Subsections not available</option>'; 
    } 
}
?>