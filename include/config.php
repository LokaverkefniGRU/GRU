<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

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
	
function styles()
{
	echo'
    <meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
	<link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="style/input.css">
    <link rel="stylesheet" type="text/css" href="style/global.css">
	';
}
?>

    
