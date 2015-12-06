<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
header(date_default_timezone_set('UTC'));
$title['global'] = "Coffee";

$config['dbuser'] = "GRU_H14"; //database user
$config['dbpass'] = "bananabomba98"; //database password
$config['dbname'] = "gru_h14_gru"; //database we're connecting to
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../style/style.css"/>
    <link rel="stylesheet" type="text/css" href="../style/main.css">
    <link rel="stylesheet" type="text/css" href="../style/input.css">
    <link rel="stylesheet" type="text/css" href="../css/global.css">
	';
}



?>

    
