<?php 
$dbuser = "root";
$dbpass = "";
$dbhost = "localhost";
$dbname = "myra";
// $dbname2 = "classbook_backup_jengka";
$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
// $conn2 = mysqli_connect($dbhost,$dbuser,$dbpass, $dbname2);

/*try
{
  $user = "root"; 
  $pass = ""; 
  $dsn = "mysql:host=localhost;dbname=myra";
  $dbh = new PDO($dsn, $user, $pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   echo "ok";
}
catch (PDOException $e)
{
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}*/
?>