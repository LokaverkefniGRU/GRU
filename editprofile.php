<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}

$pass_errors[] = "";

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);


if (isset($_POST['f_name'])) {
    
    $username = $user['username'];
    $password = strip_tags($_POST['password']);

    $result = mysqli_query($db, "SELECT id, username, password, salt FROM  `user` WHERE username =  '" . $user['username'] . "'");
    $array = mysqli_fetch_array($result);

    $id = $array[0];
    $dbusername = $array[1];
    $dbpassword = $array[2];
    $CryptSalt = $array[3];

    $hashed_password = crypt($password, $CryptSalt);
    if($dbusername == $username && $dbpassword == $hashed_password){
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
      $about = mysqli_real_escape_string($db, $about);
      $query = "UPDATE `user` SET  `username` =  '" . $username . "', `firstname` =  '" . $f_name . "', `lastname` =  '" . $l_name . "', `fullname` =  '" . $full_name . "', `email` =  '" . $email . "',`about` = '" . $about . "' WHERE `id` = '" . $id . "';";
      $result = mysqli_query($db,$query);
      header('Location: editprofile.php');
        if (!$result) {
          echo "<script>alert('Some huge ass error, just chill.. we will handle this :)')</script>";
        }else{
          $confirm_code = md5(uniqid(rand()));
          $query = "UPDATE `user` SET  `confirm_code` =  '" . $confirm_code . "', `confirmed` =  '0' WHERE  `user`.`id` =" . $user_id . ";";
          $result = mysqli_query($db, $query);

          // Send email
          $to      = $user['email'];
          $subject = 'Please confirm your updates for lokaverkefni.com';
          $message = 'Your confirmation link: https://lokaverkefni.com/confirmation.php?passkey=' . $confirm_code .' 
          If it was not you please contact support@lokaverkefni.com
          IP: ' . $user['ip'];
          $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
              'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
              'X-Mailer: PHP/' . phpversion();

          mail($to, $subject, $message, $headers);
          header('Location: editprofile.php');
        }
      }else {
        echo "<script>alert('Wrong Password')</script>";
    }
}



  if(isset($_FILES['image'])){
      $errors = array();
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
      
      if($file_size > 5000000+6){
         $errors[]='File size must be less than 5mb!';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/profile/" . $file_name);
         $link = ("images/profile/" . $file_name);
         $query = "UPDATE `user` SET `image` = '" . $link . "' WHERE `user`.`id` = '" . $user_id . "';";
         $result = mysqli_query($db, $query);
         if (!$result) {
           echo "Error!";
         }
         header('Location: editprofile.php');
      }
   }
if (isset($_POST['new_password'])) {
  $username = $user['username'];
  $current_password = strip_tags($_POST['current_password']);

    $result = mysqli_query($db, "SELECT id, username, password, salt FROM  `user` WHERE username =  '" . $user['username'] . "'");
    $array = mysqli_fetch_array($result);

    $id = $array[0];
    $dbusername = $array[1];
    $dbpassword = $array[2];
    $CryptSalt = $array[3];

    $hashed_password = crypt($current_password, $CryptSalt);
    if($dbusername == $username && $dbpassword == $hashed_password){
        $new_password = strip_tags($_POST['new_password']);
        $new_password2 = strip_tags($_POST['new_password2']);
      if ($new_password != $new_password2) {
          $pass_errors = "Your passwords does not match!";
      }else{
          $Salt = md5(uniqid(time()));
        $Algo = '6';
        $Rounds = '10000';
        $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;
          
          $new_password = strip_tags($_POST['new_password']);
          $new_password = mysqli_real_escape_string($db, $new_password);
          $new_password = crypt($new_password, $CryptSalt);
          $query = "UPDATE `user` SET  `password` =  '" . $new_password . "', `salt` =  '" . $CryptSalt . "', `change_password` =  '0' WHERE  `user`.`id` =" . $user_id . ";";
          $result = mysqli_query($db, $query);
        if (!$result) {
          $pass_errors = "Some Tech Issues!, Try again later!";
        }else{
                $to      = $user['email'];
                $subject = 'Your password has been changed!';
                $message = 'Your password has been changed on https://lokaverkefni.com. 
                If it was not you please contact support@lokaverkefni.com
                IP: ' . $user['ip'];
                $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
                    'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);  
                header('Location: profile.php');
             }
        }
      }else {
        $pass_errors = "Wrong Password";
    }
}

?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title><?php echo $user['firstname']; ?> - Edit User</title>
    <title>Home - Coffee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <script src="scripts/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
    <style type="text/css">
    .container-low{
      margin-top: 6em;
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
          
            <ul id="results"></ul>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="home.php" class="page-scroll">Home</a></li>
            <li><a href="messages.php" class="page-scroll">Messages</a></li>
            <li><a href="profile.php" class="page-scroll"><?php echo $user['fullname']  ?></a></li>
            <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <div class="container-profile-cover">
<div class="container container-low">
<div class="col-md-4">
    <img class='img-thumbnail profile-pic' src='<?php echo($user['image'])?>'/>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
        <label for="image">Update Profile Picture</label>
        <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/jpg">
        <p class="help-block">Max size: 5mb</p>
        <p class="help-block color-red"><?php 
        if(isset($_FILES['image']))
        {
          if (!empty($errors)) {
           echo '<div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <b>Error! </b>'; 
                 print_r($errors[0]);
                echo '</div>';
           
          }
        }
          ?>  </p>
      </div>
      <button id="changeprofilepicture" type="submit" class="btn waves-effect waves-light left col-xs-12">Update Profile Picture</button>
    </form>
</div>

<div class="col-md-8">
<form class="form-horizontal" action="" method="POST">
<div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s12 m6">
          <input id="f_name" name="f_name" type="text" class="validate" value="<?php echo($user['firstname']) ?>" required>
          <label for="first_name">First Name</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="l_name" name="l_name" type="text" class="validate" value="<?php echo($user['lastname']) ?>" required>
          <label for="l_name">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="fullname" type="text" class="validate" value="<?php echo($user['fullname']) ?>" required>
          <label for="fullname">Full Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="username" name="username" type="text" class="validate" value="<?php echo($user['username']) ?>" required>
          <label for="username">Username</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="email" name="email" type="email" class="validate" value="<?php echo($user['email'])?>" required>
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" class="validate" required>
          <label for="password">Confirm Password</label>
        </div>
      </div>
       <button id="changeinfo" class="btn waves-effect waves-light right col s12 m4 l3" type="submit"><i class="fa fa-check"></i> Submit</button> 
    </form>
    <button id="changepass" class="btn waves-effect waves-light left red col s12 m5 l4" type="button" data-toggle="collapse" data-target="#pass"><i class="fa fa-lock"></i>  Change password</button>
     <?php 
    if(isset($_POST['new_password']))
        {
          if (!empty($pass_errors)) {
           echo '<div class="alert alert-danger clearfix ">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <b>Error! </b>'; 
                 echo($pass_errors);
                echo '</div>';
           
          }
        }
     ?>
          <div id="pass" class="collapse col xs12 s12">

              <form role="form" action="" method="post" class="registration-form">
              <div class="row">
                      <div class="input-field col s12">
                          <input type="password" name="current_password" class="form-username form-control col s12" id="reg_password" required>
                          <label for="new_password">Current Password</label>
                      </div>
                  </div>
                  <div class="row">
                      <div class="input-field col s12">
                          <input type="password" name="new_password" class="form-username form-control col s12" id="reg_password" required>
                          <label for="new_password">New Password</label>
                      </div>
                  </div>
                  <div class="row">
                      <div class="input-field col s12">
                          <input type="password" name="new_password2" class="form-username form-control col s12" id="reg_password2" required>
                          <label for="new_password2">New Password Again</label>
                      </div>
                  </div>
                  <button type="submit" value="getResponse" class="btn red"><i class="fa fa-check"></i> Change</button>
              </form>
          </div>
  </div>
</form>

  
  
</div>
 </body>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
 <script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
  
 </html>