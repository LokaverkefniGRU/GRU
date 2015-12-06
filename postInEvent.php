<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}
$session = $_SESSION['id'];
$event_id = $_GET['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$id = time();
$time = "'";
$time .= (new \DateTime())->format('Y-m-d H:i:s');
$time .= "'";
(new \DateTime())->format('Y-m-d H:i:s');
	   #INSERT INTO `post`(`id`, `post`, `user_ID`, `title`, `content`, `likes`, `in_group`, `in_event`, `event_ID`, `to_friend`,`type`, `date_time`)  #VALUES ($id, $id,$session,'$title', '$content',[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])
$sql = "INSERT INTO `post`(`id`, `post`, `user_ID`, `title`, `content`, `likes`, `in_group`,`in_event`,`event_ID`,`to_friend`,`type`, `date_time`) 

 VALUES ($id, $id,$session,'$title','$content',0,0,1,$event_id,0,1,$time)";
$result = mysqli_query($db, $sql);



if (!$result) {
	echo $sql;
}
else{
	header("Location:Event.php?id=$event_id");
}
?>