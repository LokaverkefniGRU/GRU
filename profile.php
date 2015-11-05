<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}
// setur user sem online i db
if (isset($_SESSION['id'])){
    $query = "UPDATE `user` SET  `online` =  '1' WHERE `id` = '" . $_SESSION['id'] . "';";
    $result = mysqli_query($db, $query);
}

if (!isset($_GET['id'])) {
    header('Location: profile.php?id=' . $_SESSION['id']);
}
		
$id = $_GET['id'];
// til að ná í fyrir profile
$profileid = $_GET['id'];
$query = "SELECT * FROM user WHERE id = '$profileid' LIMIT 1";
$result = mysqli_query($db, $query);
$profile = mysqli_fetch_array($result, MYSQLI_ASSOC);

// til að þap komi ekki error
if (!$profile && !isset($_SESSION['id'])) {
    header('Location: index.php');
}else if (!$profile) {
    header('Location: profile.php?id=' . $_SESSION['id']);
}

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Resend email
if (isset($_POST['send_email'])) {
    // Býr til nýjan kóða til að staðfesta emailið þegar hann ítir a takkan
    $confirm_code = md5(uniqid(rand()));
    $query = "UPDATE `user` SET  `confirm_code` =  '" . $confirm_code . "' WHERE  `user`.`id` = " . $user_id . ";";
    $result = mysqli_query($db, $query);

    // Send email
    $to      = $user['email'];
    $subject = 'Please confirm your email for lokaverkefni.cf';
    $message = 'Your confirmation link: http://lokaverkefni.cf/confirmation.php?passkey=' . $confirm_code .'';
    $headers = 'From: no-reply@lokaverkefni.cf' . "\r\n" .
        'Reply-To: no-reply@lokaverkefni.cf' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);    
}

if (isset($_POST['new_password'])) {
    $Salt = uniqid();
    $Algo = '6';
    $Rounds = '10000';
    $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;

    $new_password = strip_tags($_POST['new_password']);
    $new_password2 = strip_tags($_POST['new_password2']);
    if ($new_password != $new_password2) {
        echo '<script type="text/javascript">alert("Your passwords does not match!")</script>';
    }else{
        $new_password = strip_tags($_POST['new_password']);
        $new_password = mysqli_real_escape_string($db, $new_password);
        $new_password = crypt($new_password, $CryptSalt);
        $query = "UPDATE `user` SET  `password` =  '" . $new_password . "', `salt` =  '" . $CryptSalt . "', `change_password` =  '0' WHERE  `user`.`id` =" . $user_id . ";";
        $result = mysqli_query($db, $query);
    if (!$result) {
        echo '<script type="text/javascript">alert("Some Tech Issues!, Try again later!")</script>';
    }else{
            header('Location: profile.php');
         }
    }
}

	?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title><?php echo $profile['fullname']; ?></title>
    <?php styles(); ?>
 <style type="text/css">
 body{
    height: auto;
 }

.container-low{
    padding-top: 5em;
}
.container-profile-cover{
    z-index: -2;
    height: 400px;
    width: 100%;
    background: url(http://www.wallpapereast.com/static/images/pier_1080.jpg);
    background-repeat: no-repeat;
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
.profile{
   
}
    .profile .profile-h2{
        margin-right: 2em;
    }
    .name{
        float: right;
    }
    .profile-pic{
        width: 250px;
        float: left;
    }
    .profile-pic:hover{
        opacity:.7;
        -webkit-transition: all 0.8s ease-out;
        -moz-transition: all 0.8s ease-out;
        transition: all 0.8s ease-out;}
    }

.text-red{
    color: red;
}
.text-green{
    color: green;
}
</style>	

 </head>
 <body>
 

<div class="main-nav">
                        <!-- main nav -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-home" role="navigation">
        
            <div class="container">

                <div class="navbar-header">
                    
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> <!--.navbar-header -->

                <!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="profile.php" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"><?php echo($user['fullname'])?></a>

                                    <ul class="dropdown-menu">
                                            <li><a href="editprofile.php">Edit Profile</a></li>
                                            <li><a href="logout.php"><i class="fa fa-sign-in"></i> Logout</a></li>
                                        </ul>
                                    </li>
                            </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/">Home</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->

            </div> <!--/.container-fluid -->
        </nav>
    </div> <!-- /.main-nav -->
    <div class="container-profile-cover">
<div class="container container-low">

<?php
if ($user['confirmed'] == 0) {
    echo'   
    <div class="bg-danger bs-callout bs-callout-danger">
        <h4>You need to confirm your email!</h4>
        <form action="profile.php" method="POST"><button type="submit" name="send_email" class="btn btn-danger">(re-send confirmation)</button></form>
    </div>';
}
if ($user['change_password'] == 1) {
    echo'   
    <div class="bg-danger bs-callout bs-callout-danger">
        <h4>Please Change Your Password!</h4>
        <button type="button" href="#" class="btn btn-danger launch-modal" data-modal-id="modal-password">Change Password!</button>
    </div>';
}
?>
    <div class="profile clearfix">
        <img class='img-thumbnail profile-pic' src='<?php echo($profile['image'])?>'/>
        <div class="name">
            <span><h3 class='header-text text-left profile-h2'><?php echo($profile['fullname'])?></h3><?php if($profile['online'] == 1){echo('<i class="fa fa-circle text-green"></i>');}elseif($profile['online'] == 0){echo('<i class="fa fa-circle text-red"></i>');} ?></span>
        </div>

        <?php
        $query = "SELECT * FROM post WHERE user_id = $profileid";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo ($row['content'] . "<br>");
        }
        ?>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD -->
        <div class="modal fade" id="modal-password" onkeypress="return runScript(p)" tabindex="-1" role="dialog" aria-labelledby="modal-password-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-password-label">Change Password!</h3>
                        <p>Enter your new password</p>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="" method="post" class="registration-form">
                        <div id="html_element"></div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="new_password" placeholder="New Password..." class="form-username form-control" id="reg_password">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="new_password2" placeholder="New Password Again..." class="form-username form-control" id="reg_password2">
                            </div>       
                            <button type="submit" value="getResponse" class="btn">Change!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


 </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script src="scripts/main.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script type="text/javascript">
    setInterval("update()", 1000); // Update every 1 seconds 
    function update() 
    { 
        $.post("update.php"); // Sends request to update.php 
    } 
    </script>
 </html>