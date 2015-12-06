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

//Fyrir registeratin
if (isset($_POST['reg_f_name'])) {
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
       $subject = 'Please confirm your email for lokaverkefni.com';
       $message = 'Your confirmation link: https://lokaverkefni.com/confirmation.php?passkey=' . $confirm_code .'';
       $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
           'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

       mail($to, $subject, $message, $headers);
       }
}

// if (isset($_POST['lost_email'])) {
//     $lost_email = strip_tags($_POST['lost_email']);
//     $lost_email = mysqli_real_escape_string($db, $lost_email);

//     $query = "SELECT email FROM user WHERE email = '" . $lost_email . "'";
//     $result = mysqli_query($db, $query);
//     if (mysqli_num_rows($result) > 0){
//         $Salt = uniqid();
//         $Algo = '6';
//         $Rounds = '10000';
//         $CryptSalt = '$' . $Algo . '$rounds=' . $Rounds . '$' . $Salt;

//         $rand_password = uniqid(rand());
//         $rand_password_crypt = crypt($rand_password, $CryptSalt);
        
//         $to      = $lost_email;
//         $subject = 'Password Recover';
//         $message = 'Your password is: ' . $rand_password .'';
//         $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
//             'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
//             'X-Mailer: PHP/' . phpversion();

//         mail($to, $subject, $message, $headers);
//         $query = "UPDATE `user` SET  `password` =  '" . $rand_password_crypt . "', `salt` =  '" . $CryptSalt . "', `change_password` =  '1' WHERE  `user`.`email` = '" . $lost_email . "';";
//         $result = mysqli_query($db, $query);
//     }else{
//         echo("<script>alert('This email is not in our records!')</script>");
//     }
//  }

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
            <button class="btn register" id="btnregister" type="button" data-parent="#accordion"data-toggle="collapse" href="#login" data-target="#register">Sign up</button>
            <button class="btn login" id="btnlogin" type="button" data-parent="#accordion" data-toggle="collapse" href="#login" onclick"FocusLogin()" data-target="#login">Log in</button>
        </div>

        <!-- LOGIN -->
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div id="login" data-parent="#accordion" class="collapse login col-lg-2">
        
            <form action="" method="POST" class="form-horizontal">
                <legend>Log in</legend>
                <span id="login_reg"></span>
                <!-- Text input-->
                <div class="form-group">
                    <div class="col-lg-12">
                    <input id="username" name="" type="text" placeholder="Username" class="form-control input-md">
                    </div>
                </div>

                <!-- Password input-->
                <div class="form-group">
                    <div class="col-lg-12">
                        <input id="password" name="" type="password" placeholder="Password" class="form-control input-md">
                    </div>
                </div>
                <button></button>
                <div class="form-group">
                    <div class="">
                        <button type="button" id="loginButton" class="btn btn-primary">Log in</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- REGISTER -->
    <div id="register" data-parent"#accordion" class="collapse register col-lg-2">
        <form class="form-horizontal" action="" method="POST">
            <fieldset>

            <!-- Lengrið á forminu -->
            <legend>Sign up</legend>

            <!-- First Name -->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_f_name" name="reg_f_name" type="text" placeholder="First name" class="form-control input-md" autocomplete="off">
              </div>
            </div>

            <!-- Last Name -->
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_l_name" name="reg_l_name" type="text" placeholder="Last name" class="form-control input-md" autocomplete="off">
              </div>
            </div>

            <!-- Username -->
            <span id="usernameout"></span>
            <div id="div_reg_user" class="form-group">
              <div class="col-lg-12">
              <input id="reg_username" name="reg_username" type="text" placeholder="Username" class="form-control input-md" autocomplete="off">
              </div>
            </div>

            <!-- Email -->
            <span id="emailout"></span>
            <div class="form-group">
              <div class="col-lg-12">
              <input id="reg_email" name="reg_email" type="email" placeholder="E-mail" class="form-control input-md" autocomplete="off">
              </div>
            </div>

            <!-- Password -->
            <span id="passout"></span>
            <div class="form-group">
              <div class="col-lg-12">
                <input id="reg_password" name="reg_password" type="password" placeholder="Password" class="form-control input-md" autocomplete="off">
              </div>
            </div>

            <!-- Password 2 -->
            <div class="form-group">
              <div class="col-lg-12">
                <input id="reg_password2" name="reg_password2" type="password" placeholder="Password again" class="form-control input-md" autocomplete="off">
              </div>
            </div>
          
            <!-- Button -->
            <div class="form-group">
              <div class="col-lg-12">
                <input type="submit" id="" class="btn btn-primary" value="Sign Up">
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
<script src="../scripts/jquery.js"></script>
<script src="../scripts/bootstrap.js"></script>

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

        <?php
            $email_array = json_encode($array_reg_email);
            $username_array = json_encode($array_reg_username);
            echo "var email_array = " . $email_array . ";";
            echo "var username_array = " . $username_array . ";";
        ?>
            
        var username = document.getElementById("usernameout");
        var email = document.getElementById("emailout");
        var password = document.getElementById("passout");
        var taken = false;
        

        $("#reg_username").keyup(function(){
            var input = document.getElementById("reg_username").value.toLowerCase();

            if (jQuery.inArray(input, username_array) != -1) {
                taken = true;
            }
            else{taken=false;}

            if (taken==true) {
                $( "div_reg_user" ).addClass( "form-group has-warning" );
                username.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Username is already in use </div>';              
            }
            else{
                username.innerHTML = "";
            }

        });

        $("#reg_email").keyup(function(){
            var taken_email = false;
            var input = document.getElementById("reg_email").value.toLowerCase();

            if (jQuery.inArray(input, email_array) != -1) {
                taken_email = true;
            }
            else{
                taken_email=false;
            }

            if (taken_email==true) {
                email.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Email is already in use </div>';              
            }
            else{
                email.innerHTML = "";
            }

        });
        $("#reg_password2").keyup(function(){
            var pass1 = document.getElementById("reg_password").value;
            var pass2 = document.getElementById("reg_password2").value;
            if (pass1.length > 3 && pass2.length > 3) {
                if (pass1 == pass2) {
                    password.innerHTML = "";
                }
                else{
                password.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Passwords do not match</div>';              
                }   
            }
            else if(pass1.length < 3 && pass2.length < 3){
                password.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Password has to be more than 3 letters</div>';              
            };
        });
            
            // $("#login").click(function(){

            //     var username = document.getElementById("username").value.toLowerCase();
            //     var password = document.getElementById("password").value;

            //     <?php
            //     $array_login = array();
            //     $query = "SELECT username, password, salt FROM `user` WHERE username =  "?>username;<?php

            //     $result = mysqli_query($db, $query);

            //     while($row = $result->fetch_assoc()) {
            //         array_push($array_login, $row['username']);
            //     }
            //     ?>
            //     login.innerHTML = <?print_r($array_login);?>

            //});

// login
var login_reg = document.getElementById("login_reg");
var login = function(){
      // get the values from the input fields
    var username = $('#username')[0].value; // Nær í value ur textbox
    var password = $('#password')[0].value; // Nær í value ur textbox
  
    $.ajax({
        url: "assets/php/login.php", //Reffar á login.php
        type: "POST", // Þetta er POST skipun
        data: {
            username: username, // uername == username
            password: password  // password == password
        },
        success: function(data){ // ef success þá keyrir hann þessa function
            if (data == false) { // ef data (username & password) == false (Sé vitlaust) Þá keyrir hann þessa error skipun
                login_reg.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b>Wrong username or password!</div>'; 
            }
            else{ // ef data er ekki false (username & password) þá relodar hann síðunni til að userinn hendist á home.php því hann er kominn með session
                window.location.reload(); // Reload
            }
        }
    })
}
$('#password').on('keydown',function(event){
    if (event.keyCode == 13) {
        login();
    };
});
$('#loginButton').on('click',login);

function LoginFocus(){
	document.getElementById('username').focus();
};

// lost email
// var lost_email = document.getElementById("lost_email");
// $('#lost_emailButton').on('click',function(){

//     // get the values from the input fields
//     var lost_email = $('#lost_email')[0].value;
  
//     $.ajax({
//         url: "assets/php/emailrecover.php",
//         type: "POST",
//         lost_mail: {
//             lost_email: lost_email
//         },
//         success: function(lost_mail){
//             if (lost_mail == true) {
//                 login_reg.innerHTML = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Success! </b>Email has been sent to this email</div>'; 
//             }
//             else{
//                 login_reg.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b>This email is not in our records!</div>';           
//             }
//         }
//     })
//  })

// register
// var reg_reg = document.getElementById("reglogin_reg");
// $('#regButton').on('click',function(){

//     // get the values from the input fields
//     var username = $('#username')[0].value;
//     var password = $('#password')[0].value;
  
//     $.ajax({
//         url: "assets/php/register.php",
//         type: "POST",
//         data: {
//             username: username,
//             password: password
//         },
//         success: function(data){
//             if (data == false) {
//                 reg_reg.innerHTML = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Wrong username or password!</div>'; 
//             }
//             else{
//                 window.location.reload();
//             }
//         }
//     })
//  })

    </script>



<!--                 $array_login = array();
$query = "SELECT id, username, password, salt FROM `user` WHERE username =  '$username'";
$result = mysqli_query($db, $query);

while($row = $result->fetch_assoc()) {
    array_push($array_login, $row['username']);
} -->