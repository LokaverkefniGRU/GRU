<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}
// if (isset($_SESSION['id'])){
//     $query = "UPDATE `user` SET  `online` =  '1' WHERE `id` = '" . $_SESSION['id'] . "';";
//     $result = mysqli_query($db, $query);
// }


// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Resend email
if (isset($_POST['send_email'])) {
    $to      = $user['email'];
    $subject = 'Please confirm your email for lokaverkefni.cf';
    $message = 'Your confirmation link: http://lokaverkefni.cf/confirmation.php?passkey=' . $user['confirm_code'] .'';
    $headers = 'From: lokaverkefni.cf@gmail.com' . "\r\n" .
        'Reply-To: lokaverkefni.cf@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);    
}

// UPDATE  `0712982139_gru`.`user` SET  `username` =  'aronhr9',
// `firstname` =  'Aron1',
// `lastname` =  'Hrafnsson1',
// `email` =  'aronhr981@gmail.com' WHERE  `user`.`id` =1446550437;

if (isset($_POST['f_name'])) {
    $id = $_SESSION['id'];
    $f_name = strip_tags($_POST['f_name']);
    $l_name = strip_tags($_POST['l_name']);
    $full_name = strip_tags($f_name . " " . $l_name);
    $username = strip_tags($_POST['username']);
    $username = strtolower($username);
    $email = strip_tags($_POST['email']);
    $about = strip_tags($_POST['about']);
       
    $f_name = mysqli_real_escape_string($db, $f_name);
    $l_name = mysqli_real_escape_string($db, $l_name);
    $full_name = mysqli_real_escape_string($db, $full_name);
    $username = mysqli_real_escape_string($db, $username);
    $email = mysqli_real_escape_string($db, $email);
    $about = mysqli_escape_string($db, $about);

    $query = "UPDATE `user` SET  `username` =  '" . $username . "', `firstname` =  '" . $f_name . "', `lastname` =  '" . $l_name . "', `fullname` =  '" . $full_name . "', `email` =  '" . $email . "',`about` = '" . $about . "' WHERE `id` = '" . $id . "';";
    $result = mysqli_query($db,$query);
    header('Location: editprofile.php');
    if (!$result) {
        echo "Nigga";
    }
}


// Breyta pass
if (isset($_POST['new_password'])) {
    $new_password = strip_tags($_POST['new_password']);
    $new_password2 = strip_tags($_POST['new_password2']);
    if ($new_password != $new_password2) {
        echo '<script type="text/javascript">alert("Your passwords does not match!")</script>';
    }else{
        $new_password = strip_tags($_POST['new_password']);
        $new_password = mysqli_real_escape_string($db, $new_password);
        $new_password = md5($new_password);
        $query = "UPDATE  `0712982139_gru`.`user` SET  `password` =  '" . $new_password . "', `change_password` =  '0' WHERE  `user`.`id` =" . $user_id . ";";
        $result = mysqli_query($db, $query);
    if (!$result) {
        echo '<script type="text/javascript">alert("Some Tech Issues!, Try again later!")</script>';
    }else{
            header('Location: profile.php');
         }
    }
}

  if(isset($_FILES['image'])){
      $errors= array();
      $salt = md5(uniqid(time()));
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $var = explode('.', $file_name);
      $file_ext = strtolower(array_pop($var));
      $file_name = $salt . $file_name;

      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions) === false){
         $errors[]="Extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10000000){
         $errors[]='File size must be less than 10mb!';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/".$file_name);
         $link = ("images/" . $file_name);
         $query = "UPDATE `user` SET `image` = '". $link ."' WHERE `user`.`id` = '" . $user_id . "';";
         $result = mysqli_query($db, $query);
         if (!$result) {
           echo "Error!";
         }
         header('Location: editprofile.php');
      }
   }




?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title><?php echo $user['firstname']; ?> - Edit User</title>
    <?php styles(); ?>
 <style type="text/css">
 body{
    height: auto;
 }
textarea.form-control {
  height: 100%;
}

.container-low{
    padding-top: 5em;
}
.container-profile-cover{
    z-index: -2;
    height: 400px;
    width: 100%;
    /*background: url(http://www.wallpapereast.com/static/images/pier_1080.jpg);*/
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
.color-red{
  color:red;
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
                        <li><a href="/">Home</a></li>
                        <li><a href="#">Blog</a></li>
                        <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo($user['fullname'])?></a>
                        <ul class="dropdown-menu">
                                <li><a href="profile.php">Edit Profile</a></li>
                                <li><a href="logout.php"><i class="fa fa-sign-in"></i> Logout</a></li>
                            </ul>
                        </li>
                </ul>
                    </ul>
                </div><!-- /.navbar-collapse -->

            </div> <!--/.container-fluid -->
        </nav>
    </div> <!-- /.main-nav -->
    <div class="container-profile-cover">
<div class="container container-low">
<div class="col-md-4">
    <img class='img-thumbnail profile-pic' src='<?php echo($user['image'])?>'/>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
        <label for="image">Update Profile Picture</label>
        <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/jpg">
        <p class="help-block">Max size: 10mb</p>
        <p class="help-block color-red"><?php 
        if(isset($_FILES['image']))
        {
          if (!empty($errors)) {
            print_r($errors[0]);
          }
        }
          ?>  </p>
      </div>
      <button type="submit" class="btn btn-default">Change</button>
    </form>
</div>

<div class="col-md-8">
    <form action="" method="POST" id="form">
      <div class="form-group">
        <label for="f_name">First name:</label>
        <input type="text" class="form-control" name="f_name" id="f_name" placeholder="First Name..." <?php echo 'value="' . $user['firstname'] . '"'; ?>>
      </div>
       <div class="form-group">
        <label for="l_name">Last name:</label>
        <input type="text" class="form-control" name="l_name" id="l_name" placeholder="Last Name..." <?php echo 'value="' . $user['lastname'] . '"'; ?>>
      </div>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Username..." <?php echo 'value="' . $user['username'] . '"'; ?>>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email..." <?php echo 'value="' . $user['email'] . '"'; ?>>
      </div>
      <div class="form-group">
        <label for="about">About:</label>
        <textarea name="about" placeholder="About yourself..." class="form-about-yourself form-control" id="form-about-yourself" rows="3"><?php echo ($user['about']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
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
                                <input type="password" name="new_password" placeholder="New Password..." class="form-username form-control" id="password">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="new_password2" placeholder="New Password Again..." class="form-username form-control" id="password2">
                            </div>
                            <button id="submit" value="getResponse" class="btn">Change!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


 </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    
 </html>