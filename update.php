<?php 
include 'include/config.php';
session_start(); 
if ($_SESSION["id"]) 
{
	mysqli_query("UPDATE user SET online = 1 WHERE id = '" . $_SESSION['id'] . "'") or die(mysql_error());
}elseif (!$_SESSION['id']) {
	mysqli_query("UPDATE user SET online = 0 WHERE id = '" . $_SESSION['id'] . "'") or die(mysql_error());
}
?>