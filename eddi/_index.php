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
        echo "<script>Alert.render('Username or Password was incorrect!')</script>";

    }
}

// Fyrir registeratin
if (isset($_POST['reg_password'])) {
    $reg_username = strip_tags($_POST['reg_username']);
    $reg_username = strtolower($reg_username);
    $reg_email = strip_tags($_POST['reg_email']);

    $reg_username = mysqli_real_escape_string($db, $reg_username);
    $reg_email = mysqli_real_escape_string($db, $reg_email);

    $reg_password = strip_tags($_POST['reg_password']);
    $reg_password2 = strip_tags($_POST['reg_password2']);

    $query = "SELECT username FROM user WHERE username = '" . $reg_username . "'";
    $check_username = mysqli_query($db, $query);

    $query = "SELECT email FROM user WHERE email = '" . $reg_email . "'";
    $check_email = mysqli_query($db, $query);

    if ($reg_password != $reg_password2) {
        $errors[]='Your passwords do not match!';
        echo "<script>alert('Your passwords do not match!')</script>";
        header('Location: index.php');
    }elseif($check_username){
        echo "<script>alert('This username is in use, pick another')</script>";
        header('Location: index.php');
    }elseif($check_email){
        echo "<script>alert('This email is in use, pick another')</script>";
        header('Location: index.php');
    }else{
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
        $reg_email = mysqli_real_escape_string($db, $reg_email);
        
        $reg_password = mysqli_real_escape_string($db, $reg_password);
        $hashed_password = crypt($reg_password, $CryptSalt);
        
        // Random confirmation code 
        $confirm_code = md5(uniqid(rand())); 
        $query = "INSERT INTO `user` (`id`, `firstname`, `lastname`, `fullname`, `username`, `password`, `salt`, `email`, `confirm_code`, `confirmed`, `ip`) VALUES ('$id', '$reg_f_name', '$reg_l_name', '$reg_full_name', '$reg_username', '$hashed_password', '$CryptSalt', '$reg_email', '$confirm_code' , 0, '$ip');";
        $result = mysqli_query($db, $query);
        header('Location: index.php');

    if (!$result) {
        echo "<script>alert('We Could not create your user, please try again..')</script>";
        header('Location: index.php');
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
    $lost_email = strip_tags($_POST['lost_email']);
    $lost_email = mysqli_real_escape_string($db, $lost_email);

    $query = "SELECT email FROM user WHERE email = '" . $lost_email . "'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0){
        $Salt = uniqid();
        $Algo = '6';
        $Rounds = '10000';
        $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;

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
        $result = mysqli_query($db, $query);
    }else{
        echo("<script>alert('This email is not in our records!')</script>");
    }
 }

// Check if username is already in db
$array_reg_username = array();
$query = "SELECT username FROM user";
$result = mysqli_query($db, $query);

while($row = $result->fetch_assoc()) {
    array_push($array_reg_username, $row['username']);
}

// Check if email is already in db
$array_reg_email = array();
$query = "SELECT email FROM user";
$result = mysqli_query($db, $query);

while($row = $result->fetch_assoc()) {
    array_push($array_reg_email, $row['email']);
}

$array_reg_username = array();
$query = "SELECT username, password FROM user";
$result = mysqli_query($db, $query);

while($row = $result->fetch_assoc()) {
    array_push($array_reg_username, $row['username'], $row['password']);
}

?>

<html>
<head>
    <title>Coffee</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style/slideshowindex.css">
    
</head>
<body>
<!-- PRELOADER -->
<div id="preloader" style="display: none;">
<div id="status" style="display: none;"></div>
</div>
<!-- BEGINNING OF HTML -->
<div class="page">
    

        <ul id="ss" class="cb-slideshow">
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
            <li>
                <span></span>
            </li>
        </ul>

    <div class="container">
        <div class="logreg-buttons">
            <button class="btn register" type="button" data-parent="#accordion"data-toggle="collapse" href="#login" data-target="#register">Sign up</button>
            <button class="btn login" type="button" data-parent="#accordion" data-toggle="collapse" href="#login" data-target="#login">Log in</button>
        </div>

        <!-- LOGIN -->
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div id="login" data-parent="#accordion" class="collapse login col-lg-2">
            <form action="" method="POST" class="form-horizontal">
                <legend>Log in</legend>
                <!-- Text input-->
                <div class="form-group">
                    <div class="col-lg-12">
                    <input id="username" name="username" type="text" placeholder="Username" class="form-control input-md">
                    </div>
                </div>

                <!-- Password input-->
                <div class="form-group">
                    <div class="col-lg-12">
                        <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md">
                    </div>
                </div>

                <div class="form-group">
                    <div class="">
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- REGISTER -->
    <div id="register" data-parent"#accordion" class="collapse register col-lg-2">
        <form class="form-horizontal" action="" method="POST">
            <fieldset>

            <!-- Form Name -->
            <legend>Sign up</legend>

            <!-- Text input-->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_f_name" name="reg_f_name" type="text" placeholder="First name" class="form-control input-md">
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_l_name" name="reg_l_name" type="text" placeholder="Last name" class="form-control input-md">
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_username" name="reg_username" type="text" placeholder="Username" class="form-control input-md">
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_email" name="reg_email" type="email" placeholder="E-mail" class="form-control input-md">
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <div class="col-lg-12">
                <input id="reg_password" name="reg_password" type="password" placeholder="Password" class="form-control input-md">
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <div class="col-lg-12">
                <input id="reg_password2" name="reg_password2" type="password" placeholder="Password again" class="form-control input-md">
              </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="form-group">
              <div class="col-lg-12"> 
                <label class="radio-inline" for="radios-0">
                  <input type="radio" name="radios" id="radios-0" value="1" checked="checked">
                  Male
                </label> 
                <label class="radio-inline" for="radios-1">
                  <input type="radio" name="radios" id="radios-1" value="2">
                  Female
                </label> 
                <label class="radio-inline" for="radios-2">
                  <input type="radio" name="radios" id="radios-2" value="3">
                  Other
                </label> 
              </div>
            </div>

            <!-- File Button --> 
            <div class="form-group">
              <label class=" control-label" for="filebutton">File Button</label>
              <div class="col-lg-12">
                <input id="filebutton" name="filebutton" class="input-file" type="file">
              </div>
            </div>

            <!-- Button -->
            <div class="form-group">
              <div class="col-lg-12">
                <button id="submit" name="submit" class="btn btn-primary">Register</button>
              </div>
            </div>

            </fieldset>
            </form>
        </div>
    </div>
</div>


        <div class="container">
            <h1 class="fyrri">Stay</h1><h1 class="seinni">Connected</h1>
        </div>
    </div>
</div>
<footer>
    <button id="stopss" onclick="noss()" id="close_popup" class="btn stopss">Toggle slideshow</button>
</footer>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">

function noss(){
    if (document.getElementById('ss').style.display == 'none') {
        document.getElementById('ss').style.display='block';
    }
    else{
        document.getElementById('ss').style.display='none';
        document.getElementById('ss').value = 'Banani';
    }
 
}

</script>