<?php
include '../../include/config.php';
session_start();

$buttonidd = $_POST['buttonidd'];

$query = "SELECT * FROM post_like WHERE post_ID = '" . $buttonidd . "' AND user_id = '" . $_SESSION['id'] . "';";
$result = mysqli_query($db, $query);
if (is_object($result) && $result->num_rows <= 0) {
	$query = "INSERT INTO post_like (post_id, user_id) VALUES('" . $buttonidd . "','" . $_SESSION['id'] . "');";
	$result = mysqli_query($db, $query);

	#select fyrir eiganda postsins
	$query = "SELECT user_id FROM post WHERE id = '" . $buttonidd . "'";
	$result = mysqli_query($db, $query);
	$row = $result->fetch_assoc();

	#býr til nofification
	// $query = "INSERT INTO noticifation(seen, sender_id, post_id, receiver_id, type, special) VALUES(0, '" . $_SESSION['id'] ."', '" . $buttonidd . "', '" . $row['user_id'] . "',1, 0);";
	// $result=mysqli_query($db, $query);

	$query = "SELECT COUNT(user_id) AS likes FROM post_like WHERE post_id = '" . $buttonidd . "'";
	$result = mysqli_query($db, $query);

	$row = $result -> fetch_assoc();
	$count_result = $row['likes'];

	if (!$result) {
	    echo("<script>alert('Some hard ass error please contact the support')</script>");
	}else{
		//print_r($row);
		echo(json_encode($row));
	}
}else if (is_object($result) && $result->num_rows > 0) {
	$query = "DELETE FROM `post_like` WHERE `post_like`.`post_ID` = " . $buttonidd . " AND `post_like`.`user_ID` = '" . $_SESSION['id'] . "'";
	$result = mysqli_query($db, $query);

	#select fyrir eiganda postsins
	$query = "SELECT user_id FROM post WHERE id = '" . $buttonidd . "'";
	$result = mysqli_query($db, $query);
	$row = $result->fetch_assoc();

	#býr til nofification
	// $query = "INSERT INTO noticifation(seen, sender_id, post_id, receiver_id, type, special) VALUES(0, '" . $_SESSION['id'] ."', '" . $buttonidd . "', '" . $row['user_id'] . "',1, 0);";
	// $result=mysqli_query($db, $query);

	$query = "SELECT COUNT(user_id) AS likes FROM post_like WHERE post_id = '" . $buttonidd . "'";
	$result = mysqli_query($db, $query);

	$row = $result -> fetch_assoc();
	$count_result = $row['likes'];

	if (!$result) {
	    echo("<script>alert('Some hard ass error please contact the support')</script>");
	}else{
		//print_r($row);
		echo(json_encode($row));
	}
}else{
	echo "No bjets";
}



?>