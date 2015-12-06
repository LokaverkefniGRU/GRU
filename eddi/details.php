<?php 
include 'database.php';
$output = "";
foreach($_GET as $key=>$val) 
{ 
  $output .= $key . " = ".$val . "<br/>";
} 
?>
<html>
 <head>
  <title>PHP Details</title>
 </head>
 <body>
 <?php 
 echo '<p>Details for movie with ID: '.$_GET["id"].'</p>'.$output ;



 ?>
 </body>
</html>