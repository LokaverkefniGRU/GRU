<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}

$pass_errors[] = "";

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

$session = $user['id'];
$group_id = $_GET['id'];
$title = $_POST['title'];
$content = $_POST['content'];

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php 
$id = time();
$time = "'";
$time .= (new \DateTime())->format('Y-m-d H:i:s');
$time .= "'";
(new \DateTime())->format('Y-m-d H:i:s');
$sql = "INSERT INTO `post`(`id`, `post`, `user_ID`, `title`, `content`, `likes`, `in_group`, `group_ID`, `in_event`,`to_friend`,`type`, `date_time`) 
VALUES ($id, $id,$session,'$title','$content',0,1,$group_id,0,0,1,$time)";
$result = mysqli_query($db, $sql);

if (!$result) {
	echo $sql;
}
else{
	header("Location:Group.php?id=$group_id");
}
?>
</body>
</html>