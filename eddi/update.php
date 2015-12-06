<?php 
include 'include/config.php';
session_start(); 
date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Title</title>
</head>
<body>
<?php 
if ($_SESSION["id"]) 
{
	mysqli_query($db, "UPDATE user SET last_online = '" . $date() . "' WHERE id = '" . $_SESSION['id'] . "'");
}else{
	echo($date);
}
?>



</body>
</html>