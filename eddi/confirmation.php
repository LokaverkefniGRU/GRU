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
$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $confirm['id'] . " AND unread = 'yes' "));

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Confirm Email</title>
 	<?php styles(); ?>
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="stylesheet" type="text/css" href="css/nav.css">
    <link rel="stylesheet" type="text/css" href="style/input.css">

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
<nav class="navbar navbar-custom navbar-fixed-top one-page" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                    <input type="text" id="search" class="search-query" placeholder="Search for..." autocomplete="off">
                    <ul id="results"></ul>
                <a class="navbar-brand" href="home.php">Title</a>  
            </div>
            <div class="collapse navbar-collapse" id="custom-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation" class="active"><a href="home.php">Home <span class="badge">42</span></a></li>
                    <li role="presentation"><a href="#">Messages <span class="badge">'; print_r($pmcount[0]);  echo'</span></a></li>
                    <li role="presentation"><a href="profile.php">' . $confirm['fullname'] . '</a></li>
                </ul>
            </div>
        </div>
    </nav>

	            <div class="bg-danger bs-callout bs-callout-danger">
	              <h4>Error with confirm code!</h4>
	             	Please contact administrator!
	            </div>
	    ';
		}elseif ($result) {
			echo'
			<nav class="navbar navbar-custom navbar-fixed-top one-page" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                    <input type="text" id="search" class="search-query" placeholder="Search for..." autocomplete="off">
                    <ul id="results"></ul>
                <a class="navbar-brand" href="home.php">Title</a>  
            </div>
            <div class="collapse navbar-collapse" id="custom-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation" class="active"><a href="home.php">Home <span class="badge">42</span></a></li>
                    <li role="presentation"><a href="#">Messages <span class="badge">'; print_r($pmcount[0]);  echo'</span></a></li>
                    <li role="presentation"><a href="profile.php">' . $confirm['fullname'] . '</a></li>
                </ul>
            </div>
        </div>
    </nav>
	            <div class="bg-success bs-callout bs-callout-success">
	              <h4>Thanks for confirming your email</h4>
	            </div>
	    ';
		}
	}else{
			echo'
						<nav class="navbar navbar-custom navbar-fixed-top one-page" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                    <input type="text" id="search" class="search-query" placeholder="Search for..." autocomplete="off">
                    <ul id="results"></ul>
                <a class="navbar-brand" href="home.php">Title</a>  
            </div>
            <div class="collapse navbar-collapse" id="custom-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation" class="active"><a href="home.php">Home <span class="badge">42</span></a></li>
                    <li role="presentation"><a href="#">Messages <span class="badge">'; print_r($pmcount[0]);  echo'</span></a></li>
                    <li role="presentation"><a href="profile.php">' . $confirm['fullname'] . '</a></li>
                </ul>
            </div>
        </div>
    </nav>
			
	            <div class="bg-danger bs-callout bs-callout-danger">
	              <h4>Error with confirm code!</h4>
	             	Please contact administrator!
	            </div>
	    ';
		}

	  ?>
</div>

 </body>
     <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>
 </html>
