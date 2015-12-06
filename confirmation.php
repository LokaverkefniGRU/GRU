<?php 
session_start();
include('include/config.php');
$passkey = $_GET['passkey'];
$query = "SELECT * FROM user WHERE confirm_code = '$passkey' LIMIT 1";
$result = mysqli_query($db, $query);
$confirm = mysqli_fetch_array($result, MYSQLI_ASSOC);


$user_id = $_SESSION['id'];
$query = "SELECT fullname FROM user WHERE id = '$user_id ' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);


if (!isset($_GET['passkey'])) {
    header('Location: profile.php');
}
// $pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $confirm['id'] . " AND unread = 'yes' "));
header("refresh:2;url=profile.php");
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Confirm Email</title>
    <link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 </head>
<style type="text/css">
.container-low{
    margin-top: 5em;
}
.bs-callout {
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
}
.bs-callout h4 {
    margin-top: 0;
    margin-bottom: 5px;
}
.bs-callout p:last-child {
    margin-bottom: 0;
}
.bs-callout code {
    border-radius: 3px;
}
.bs-callout+.bs-callout {
    margin-top: -5px;
}

.bs-callout-danger {
    border-left-color: #d9534f;
}
.bs-callout-danger h4 {
    color: #d9534f;
}
.bs-callout-success {
    border-left-color: #5cb85c;
}
.bs-callout-success h4 {
    color: #5cb85c;
}
</style>

 <body>


<div class="container container-low">
	 <?php 
	 if ($confirm['confirm_code'] == $passkey) {
		$query = "UPDATE `user` SET  `confirmed` =  '1' WHERE  `user`.`id` = " . $confirm['id']. ";";
		$result = mysqli_query($db, $query);
		if (!$result) {
			echo '
	            <div class="bg-danger bs-callout bs-callout-danger">
	              <h4>Error with confirm code!</h4>
	             	Please contact administrator!
	            </div>
	    ';
		}elseif ($result) {
			echo'
	            <div class="bg-success bs-callout bs-callout-success">
	              <h4>Thanks for confirming your email</h4>
	            </div>
	    ';
		}
	}else{
			echo'			
	            <div class="bg-danger bs-callout bs-callout-danger">
	              <h4>Error with confirm code!</h4>
	             	Please contact administrator!
	            </div>
	    ';
		}

	  ?>
</div>

 </body>

 </html>
