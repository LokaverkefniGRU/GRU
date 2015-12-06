<?php
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

$user_id = $_GET['user_id'];
$thing_id = $_GET['thing_id'];
$type = $_GET['type'];

if ($type==1) {
	$sql = "DELETE FROM hopar_join WHERE hopar_ID = $thing_id AND user_id = $user_id";
	$result = mysqli_query($db, $sql);
	if (!$result) {
		echo $sql;
	}
	else{
		header("Location: Group.php?id=$thing_id");		
	}
}
else{
	$sql = "DELETE FROM event_invite WHERE event_ID = $thing_id AND receiver_ID = $user_id";
	$result = mysqli_query($db, $sql);

	if (!$result) {
		echo "<pre>"; echo var_dump($result); echo "</pre>";
		echo $sql;
	}
	else{
		header("Location: Event.php?id=$thing_id");
	}
}


?>