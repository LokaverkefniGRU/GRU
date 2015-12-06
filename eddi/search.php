<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}
 ?>
<!DOCTYPE HTML>
<html>
<head>
	<!-- Meta -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Search</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<!-- Load CSS -->
	<link href="style/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="scripts/custom.js"></script>
</head>
<body>
	<div id="main">
		<div class="icon"></div>
		<h1 class="title">Search</h1>
		<h5 class="title">Search name or username</h5>

		<input type="text" id="search" autocomplete="off">
		<ul id="results"></ul>
	</div>
</body>
</html>