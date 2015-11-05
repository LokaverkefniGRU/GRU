<?php 
session_start();
include 'include/config.php';
$errors = array();
$errors_login = array();
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    $query = "UPDATE `user` SET  `online` =  '1' WHERE `id` = '" . $_SESSION['id'] . "';";
    $result = mysqli_query($db, $query);
}

// ná i iptölu hja user
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// Sem login
if (isset($_POST['username'])) {
    $username = strip_tags($_POST['username']);
    $username = strtolower($username);
    $username = mysqli_real_escape_string($db, $username);
    $password = strip_tags($_POST['password']);
    $password = mysqli_real_escape_string($db, $password);

    $result = mysqli_query($db, "SELECT id, username, password, salt FROM  `user` WHERE username =  '$username'");
    $array = mysqli_fetch_array($result);

    $id = $array[0];
    $dbusername = $array[1];
    $dbpassword = $array[2];
    $CryptSalt = $array[3];

    $hashed_password = crypt($password, $CryptSalt);
    if($dbusername == $username && $dbpassword == $hashed_password){
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $id;
        // Fyrir ip á undan
        $query = "SELECT ip FROM user WHERE id = '" . $id . "';";
        $result = mysqli_query($db, $query);
        $iptala = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $query = "UPDATE `user` SET  `last_ip` =  '" . $iptala['ip'] . "' WHERE `id` = $id;";
        $result = mysqli_query($db, $query);
        
        $query = "UPDATE `user` SET  `ip` =  '" . $ip . "' WHERE `id` = $id;";
        $result = mysqli_query($db, $query);
        // Fyrir ip nuna
        $query = "UPDATE `user` SET  `ip` =  '" . $ip . "' WHERE `id` = $id;";
        $result = mysqli_query($db, $query);
        header("Location: profile.php");
    } else {
        $_SESSION["username"] = $username;
        $errors_login[]="Wrong Username or Password!";
        echo "<script>alert('Wrong Username or Password!')</script>";

    }
}
// Fyrir registeratin
if (isset($_POST['reg_password'])) {
    $reg_password = strip_tags($_POST['reg_password']);
    $reg_password2 = strip_tags($_POST['reg_password2']);
    if ($reg_password != $reg_password2) {
        $errors[]='Your passwords do not match!';
        echo "<script>alert('Your passwords do not match!')</script>";
        $_SESSION['reg_f_name'] = strip_tags($_POST['reg_f_name']);
        $_SESSION['reg_l_name'] = strip_tags($_POST['reg_l_name']);
        $_SESSION['reg_username'] = strip_tags($_POST['reg_username']);
        $_SESSION['reg_email'] = strip_tags($_POST['reg_email']);
    }else if(isset($_POST['reg_f_name'])) {
        $Salt = uniqid();
        $Algo = '6';
        $Rounds = '10000';
        $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;

        $id = time();
        $reg_f_name = strip_tags($_POST['reg_f_name']);
        $reg_l_name = strip_tags($_POST['reg_l_name']);
        $reg_full_name = strip_tags($reg_f_name . " " . $reg_l_name);
        $reg_username = strip_tags($_POST['reg_username']);
        $reg_username = strtolower($reg_username);
        $reg_password = strip_tags($_POST['reg_password']);
        $reg_email = strip_tags($_POST['reg_email']);
        
        $reg_f_name = mysqli_real_escape_string($db, $reg_f_name);
        $reg_l_name = mysqli_real_escape_string($db, $reg_l_name);
        $reg_full_name = mysqli_real_escape_string($db, $reg_full_name);
        $reg_username = mysqli_real_escape_string($db, $reg_username);
        $reg_password = mysqli_real_escape_string($db, $reg_password);
        $hashed_password = crypt($reg_password, $CryptSalt);
        $reg_email = mysqli_real_escape_string($db, $reg_email);
        // Random confirmation code 
        $confirm_code = md5(uniqid(rand())); 
        $query = "INSERT INTO `user` (`id`, `firstname`, `lastname`, `fullname`, `username`, `password`, `salt`, `email`, `confirm_code`, `confirmed`, `ip`) VALUES ('$id', '$reg_f_name', '$reg_l_name', '$reg_full_name', '$reg_username', '$hashed_password', '$CryptSalt', '$reg_email', '$confirm_code' , 0, '$ip');";
        $result = mysqli_query($db, $query);
    if (!$result) {
        $errors[]='We could not insert you into our database';
    }else{
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $id;
        header("Location: profile.php");
        // Set inn email php kóðan her
        $to      = $reg_email;
        $subject = 'Please confirm your email for lokaverkefni.cf';
        $message = 'Your confirmation link: http://lokaverkefni.cf/confirmation.php?passkey=' . $confirm_code .'';
        $headers = 'From: no-reply@lokaverkefni.cf' . "\r\n" .
            'Reply-To: no-reply@lokaverkefni.cf' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
        }
    }
}

if (isset($_POST['lost_email'])) {
    $Salt = uniqid();
    $Algo = '6';
    $Rounds = '10000';
    $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;

    $lost_email = strip_tags($_POST['lost_email']);
    $lost_email = mysqli_real_escape_string($db, $lost_email);

    $rand_password = uniqid(rand());
    $rand_password_crypt = crypt($rand_password, $CryptSalt);
    
    $to      = $lost_email;
    $subject = 'Password Recover';
    $message = 'Your password is: ' . $rand_password .'';
    $headers = 'From: no-reply@lokaverkefni.cf' . "\r\n" .
        'Reply-To: no-reply@lokaverkefni.cf' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    $query = "UPDATE `user` SET  `password` =  '" . $rand_password_crypt . "', `salt` =  '" . $CryptSalt . "', `change_password` =  '1' WHERE  `user`.`email` = '" . $lost_email . "';";
    // $query = "UPDATE `user` SET  `password` =  '" . $rand_password_crypt . "', `change_password` =  '1' WHERE  `user`.`email` = '" . $lost_email . "';";
    $result = mysqli_query($db, $query);
 }

maintenance();
?>

<html lang="en"> 
    <head> 
        <link href="http://tsuts.tskoli.is/2t/0712982139/GRU/img/favicon.ico" rel="icon" type="image/x-icon" />
    	<title><?php echo($title['global']); ?></title>
        <meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
		<link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
		<link rel="stylesheet" type="text/css" href="http://tsuts.tskoli.is/2t/0712982139/gru/css/style.css">
        <link rel="stylesheet" type="text/css" href="css/buttons.css">
        <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </script>
    </head> 
<style type="text/css">
h1{
     word-wrap: break-word;
     -webkit-hyphens: auto;
     -moz-hyphens: auto;
     -ms-hyphens: auto;
     -o-hyphens: auto;
     hyphens: auto;
}
.margin-test{
    margin: 0 auto;
    padding-left: 27%;
}
.margin{
    margin: 0 auto;
    padding-left: 45%;
}

.container-low{
    margin-top: 20%;
}
.bg{
    height: 100%;
    width: 100%;
    background: url(http://i.imgur.com/f57cIUI.jpg?1) no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
.col-md-3{
        width: 30%;
    }

@media only screen and (max-width: 700px) {
    .col-md-3{
        width: 50%;
    }
    .margin-test{
        padding-left: 0;
    }
}
@media only screen and (max-width: 991px) {
    .col-md-3{
        width: 50%;
    }
    .margin-test{
        padding-left: 0;
    }
}
.center-text{
    text-align: center;
}
.color-red{
  color:red;
}
</style>
<body>
<div class="bg">
    <div class="container">
        <div class="container-low">
            <h1 class="text-center">We love making people stay connected</h1>
            <div class="margin-test">
                <div class="col-md-3 col-sm-3 col-xs-6"><a href="#" class="btn btn-sm animated-button thar-four launch-modal" data-modal-id="modal-login">Login</a> </div>  
                <div class="col-md-3 col-sm-3 col-xs-6"><a href="#" class="btn btn-sm animated-button thar-three launch-modal" data-modal-id="modal-register">Register</a></div> 
            </div>

        </div>
    </div>
</div>
<!-- LOST PASSWORD -->
        <div class="modal fade" id="modal-lost" onkeypress="return runScript(p)" tabindex="-2" role="dialog" aria-labelledby="modal-lost-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-lost-label">Lost Password</h3>
                        <p>Enter your email..</p>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="" method="post" class="registration-form">
                        <div id="html_element"></div>
                            <div class="form-group">
                                <label class="sr-only" for="form-username">Username</label>
                                <input type="text" name="lost_email" placeholder="Email..." class="form-username form-control" id="lost_email">
                            </div>     
                            <button type="submit" value="getResponse" class="btn">Recover!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


<!-- LOGIN -->
        <div class="modal fade" id="modal-login" onkeypress="return runScript(p)" tabindex="-8" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-login-label">Login</h3>
                        <p>Login so you don't miss the fun!</p>
                        
                    </div>
                    
                    <div class="modal-body">
                    <p class="color-red"><?php 
                            if(isset($_POST['username'])){
                                if (!empty($errors_login)) {
                                    print_r($errors_login[0]);
                                }
                            }
                      ?>  </p>
                        <form role="form" action="" method="post" class="registration-form">
                        <div id="html_element"></div>
                            <div class="form-group">
                                <label class="sr-only" for="form-username">Username</label>
                                <?php 
                                if (isset($_SESSION['username'])){
                                    echo '<input type="text" value="' . $_SESSION['username'] . '" name="username" placeholder="Username..." class="form-username form-control" id="username">';
                                }else{
                                    echo '<input type="text" name="username" placeholder="Username..." class="form-username form-control" id="username">';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="password" placeholder="Password..." class="form-username form-control" id="password">
                            </div>       
                            <button type="submit" value="getResponse" class="btn">Login!</button>
                            <a href="#"  class="launch-modal margin text-center" data-dismiss="modal" data-modal-id="modal-lost">Lost Password?</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!-- REGISTER -->
        <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-register-label">Sign up now</h3>
                        <p>Join us!</p>
                        
                    </div>
                    <div class="modal-body">
                    <p class="color-red"><?php 
                            if(isset($_POST['reg_username'])){
                                if (!empty($errors)) {
                                    print_r($errors[0]);
                                }
                            }
                      ?>  </p>
                        <form role="form" action="" method="post" class="registration-form" accept-charset="UTF-8">
                            <div class="form-group">
                                <label class="sr-only" for="form-first-name">First name</label>
                                <input type="text" name="reg_f_name" <?php if (isset($_SESSION['reg_f_name'])){echo 'value="' . $_SESSION['reg_f_name'] . '"';} ?>placeholder="First name..." class="form-first-name form-control" id="reg_f_name">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-last-name">Last name</label>
                                <input type="text" name="reg_l_name" <?php if (isset($_SESSION['reg_l_name'])){echo 'value="' . $_SESSION['reg_l_name'] . '"';} ?> placeholder="Last name..." class="form-last-name form-control" id="reg_l_name">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-username">Username</label>
                                <input type="text" name="reg_username" <?php if (isset($_SESSION['reg_username'])){echo 'value="' . $_SESSION['reg_username'] . '"';} ?> placeholder="Username..." class="form-username form-control" id="reg_username">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-email">Email</label>
                                <input type="text" name="reg_email" <?php if (isset($_SESSION['reg_email'])){echo 'value="' . $_SESSION['reg_email'] . '"';} ?> placeholder="Email..." class="form-email form-control" id="reg_email">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="reg_password" placeholder="Password..." class="form-username form-control" id="reg_password">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="reg_password2" placeholder="Password Again..." class="form-username form-control" id="reg_password2">
                            </div>
                            <div class="form-group">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="sex" id="male" value="male" checked>
                                    <h5 class="modal-title" id="modal-register-label">Male</h5>
                                  </label>
                                </div>
                                <div class="radio" class="form-control">
                                  <label>
                                    <input type="radio" name="sex" id="female" value="female">
                                    <h5 class="modal-title" id="modal-register-label">Female</h5>
                                  </label>
                                </div>
                            </div>
                            <button type="submit" class="btn">Sign me up!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</body> 
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="scripts/main.js"></script>
    <script type="text/javascript">
 
         $(document).ready(function(){
            $("#reg_username").change(function(){
                 $("#reg_username").addClass("input-error");
             
 
            var username=$("#reg_username").val();
 
              $.ajax({
                    type:"POST",
                    url:"index.php",
                    data:"reg_username="+username,
                        success:function(data){
                        if(data==0){
                            $("#reg_username").addClass("input-success");
                        }
                        else{
                            $("#reg_username").addClass("input-error");
                        }
                    }
                 });
 
            });
 
         });
 
       </script>
</html> 