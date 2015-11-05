<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$title['global'] = "Coffee";

$config['dbuser'] = "2410982069"; //database user
$config['dbpass'] = "svolu2"; //database password
$config['dbname'] = "2410982069_gru"; //database we're connecting to
$config['dbhost'] = "tsuts.tskoli.is";

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
$db->set_charset("utf8");
if($db->connect_errno > 0)
	{
		die("Database error: " . $db->connect_error);
	}
	
function maintenance(){
	$status = 1;
	if ($status == 0) {
		die('The Site Is Under Maintenance!');
	}
}
function styles()
{
	echo'
    <meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
	<link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">
        
	';
}



 ?>