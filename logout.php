<?php
include 'include/config.php';
session_start();
$query = "UPDATE user SET online = 0 WHERE id = '" . $_SESSION['id'] . "';";
$result = mysqli_query($db, $query);
if (isset($_SESSION['id'])) {
	unset($_SESSION['id']);
	unset($_SESSION['username']);
	header("Location: index.php");
}elseif (isset($_SESSION['adminid'])) {
	unset($_SESSION['adminid']);
	header("Location: index.php");
}
?>