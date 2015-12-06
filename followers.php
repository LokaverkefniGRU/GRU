<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}

$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - Coffee</title>
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
        .vanilla{
          display: block;
        }
        .vanilla img{
          margin-top: -.8em;
        }
        .vanilla h4{
          padding-left: 4em;
        }
        .btn-file {
          background: none;
          position: absolute;
          bottom: 20px;
          left: 20px;
          
          overflow: hidden;
        }
        .btn-file:hover {
          color: lightblue;
        }
        .btn-file input[type=file] {
          position: absolute;
          top: 0;
          right: 0;
          text-align: left;
          filter: alpha(opacity=0);
          opacity: 0;
          background: red;
          cursor: inherit;
          display: block;
        }
        input[readonly] {
          background-color: white !important;
          cursor: text !important;
        }
        span.form-control-feedback {
          position: absolute;
          top: 10px;
          right: -2px;
          z-index: 2;
          display: block;
          width: 34px;
          height: 34px;
          line-height: 34px;
          text-align: center;
          color: #3596e0;
          left: initial;
          font-size: 14px;
        }
        h2{
          font-weight: bold;
          margin-bottom: .5em;
        }
        a, a:hover{
            text-decoration: none;
        }
    </style>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="body">
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

    <div class="middle container">
    <?php 
			$query = "SELECT * FROM user JOIN friend_request ON user.id = friend_request.sender_ID WHERE receiver_ID = $user_id";
			$result = mysqli_query($db, $query);
			if (is_object($result) && $result->num_rows > 0) {
			    // output data of each row
			    while(is_object($result) && $row = $result->fetch_assoc()) {
			    	echo('<div class="col-md-12 follow"><a href="profile.php?id=' . $row['id']. '"><img class="img-circle pull-left" height="50" width="50" src="' . $row['image'] . '"><h1 class="">' . $row['fullname'] . '</h1>@' . $row['username'] . '</a></div>');
			    }
			}else{
				echo('<div class="col-md-12 follow">You dont have any followers.. Sorry homi..</div>');
			}
         ?>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
