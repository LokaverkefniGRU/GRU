<?php 
include 'include/config.php';
session_start();

// Til að ná í uppls um user
$id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Tekur Messages count af þegar hann fer i messages.php
$query = "UPDATE `message` SET  `unread` =  'no' WHERE  `message`.`to_user` = " . $id . ";";
$select_result = mysqli_query($db, $query);
if (!$result) {
	$msg_error[] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Sorry, we could not update our message status :/</div>';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Coffee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
    <link href="style/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="eddi/style/snippets.css">
    <script src="scripts/jquery.js"></script>
    <style type="text/css">
    .cahat{
    	overflow-y:scroll;
    	height: 500px
    }
    </style>
</head>
<body>
    <nav id="tf-menu" class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php">Coffee</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="home.php" class="page-scroll">Home</a></li>
            <li><a href="messages.php" class="page-scroll">Messages<?php /*if($pmcount[0] > 0) {echo(' <span class="badge">'.$pmcount[0].'</span>');}else{}*/ ?></a></li>
            <li><a href="profile.php" class="page-scroll"><?php echo $user['fullname']  ?></a></li>
            <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  <div class="container container-low">
<div class="row" style="padding-top:40px;">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                RECENT CHAT HISTORY
            </div>
            <div class="panel-body cahat">
            		<?php 	
            			$query = "SELECT * FROM message JOIN user ON message.from_user = user.id WHERE message.to_user_id = " . $id . " ORDER BY time DESC";
            			$chat_result = mysqli_query($db, $query);
						if (is_object($chat_result) && $chat_result->num_rows > 0) {
							while(is_object($chat_result) && $row = $chat_result->fetch_assoc()) {
								echo'
								<ul class="media-list">
	                               	    <li class="media">
	                               	        <div class="media-body">
	                               	            <div class="media">
	                               	                <a class="pull-left" href="#">
	                               	                    <img class="media-object img-circle " width="50px" height="50px" src="' . $row['image'] . '" />
	                               	                </a>
	                               	                <div class="media-body" >
	                               	                    ' . $row['content'] . '
	                               	                    <br />
	                               	                   <small class="text-muted">' . $row['fullname'] . ' | ' . $row['time'] . '</small>
	                               	                    <hr />
	                               	                </div>
	                               	            </div>
	                               	        </div>
	                               	    </li>
	                               	</ul>';
							}
						}else{
							echo "Messages, start conversion with some followers!";
						}
            		 ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <?php
              	
              	if (isset($_POST['to_user'])) {
              		$from_user = $id;
              		$to_user = strip_tags($_POST['to_user']);
              		$to_user = mysqli_real_escape_string($db, $to_user);
              		$content = strip_tags($_POST['content']);
              		$content = mysqli_real_escape_string($db, $content);

					$msg_error[] = "";
			
						$sql = "SELECT id, username, email, fullname FROM user WHERE username = '$to_user'";
						$res_id = mysqli_query($db, $sql);
						$res_id = mysqli_fetch_array($res_id, MYSQLI_ASSOC);
						$check_id_id = $res_id['id'];
						$check_id = $res_id['username'];
						$check_email = $res_id['email']; //Ná i email hja user til að hann fái email að hann hafi fengið skiló!
						$check_fullname = $res_id['fullname']; //Ná i email hja fullname til að hann fái email að hann hafi fengið skiló!
						if ($check_id != $to_user) {
							$msg_error[] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> This user does not exist in our records! :/</div>';
						}else{
							$query = "INSERT INTO `message` (`to_user`, `to_user_id`, `from_user`, `content`, `time`, `unread`) VALUES ('" . $to_user . "', '" . $check_id_id . "' ,'" . $from_user . "', '" . $content . "', CURRENT_TIMESTAMP, 'yes');";
	              			$result = mysqli_query($db, $query);
	              			if (!$result) {
	              				$msg_error[] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> This user id does not exist in our records! :/</div>';
	              			}else{
	              				// EF ALLT ER RÉTT ÞA SENDIST ÞETTA
	              				$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM message WHERE to_user = " . $check_id_id . " AND unread = 'yes' "));
	              				$msg_error[] = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Success! </b><br> The message has been sent to ' . $check_fullname . '</div>';
	              			}
						}
              	}

               ?> 
               SEND MESSAGE
            </div>

            	<div class="panel-body">
			        <ul class="media-list">
			            <li class="media">
			                <div class="media-body">
			                    <div class="media">
			                        <div class="media-body" >
			                            <form action="" method="POST">
			                            <?php
			                            if ($user['confirmed'] == 0) {
											echo('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> You need to confirm your email before sending an message!</div>');
										} 	
										if (!empty($msg_error)) {
											print_r($msg_error[1]);
										}
			                             ?>
											  	<div class="form-group">
											    	<label for="to_user">To: </label>
											    	<input type="text" name="to_user" class="form-control" id="to_user" placeholder="To">
											  	</div>
											  	<div class="form-group">
											  			<label for="content">Message: </label>
											  			<textarea class="form-control" name="content" id="content" rows="3" placeholder="Content"></textarea>
											  	</div>
											  	<?php 
											  	if ($user['confirmed'] == 0) {
											  		echo('<button type="submit" class="btn btn-default" disabled>Send</button>');
											  	}else{
											  		echo('<button type="submit" class="btn btn-default">Send</button>');
											  	} 
											  	?>
											  
											</form>
			                        </div>
			                    </div>
			                </div>
			            </li>
			        </ul>
			    </div>
        </div>
        
    </div>
</div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
</html>