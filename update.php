<?php 
include 'include/config.php';
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>i
	<title>Title</title>
</head>
<body>
<?php 
if ($_SESSION["id"]) 
{
	mysqli_query($db, "UPDATE user SET online = 1 WHERE id = '" . $_SESSION['id'] . "'");
}else{
	$sql = "SELECT * FROM user";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
}else {
    echo "0 results";
}
}
?>



</body>
</html>