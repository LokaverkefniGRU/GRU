<?php 
session_start();
include 'include/config.php';

if (isset($_POST['email'])) {
    $error_email = "";
    $lost_email = strip_tags($_POST['email']);
    $lost_email = mysqli_real_escape_string($db, $lost_email);

    $query = "SELECT email FROM user WHERE email = '" . $lost_email . "'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0){
        $Salt = md5(uniqid(time()));
        $Algo = '2';
        $Rounds = '07';
        $CryptSalt = '$' . $Algo . '$' . $Rounds . '$' . $Salt;

        $rand_password = uniqid(rand());
        $rand_password_crypt = crypt($rand_password, $CryptSalt);
        
        $to      = $lost_email;
        $subject = 'Password Recover';
        $message = 'Your password is: ' . $rand_password .'';
        $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
            'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
        $query = "UPDATE `user` SET  `password` =  '" . $rand_password_crypt . "', `salt` =  '" . $CryptSalt . "', `change_password` =  '1' WHERE  `user`.`email` = '" . $lost_email . "';";
        $result = mysqli_query($db, $query);
        $error_email = '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Success! </b> Please check your spam folder!</div>';
        //echo("<script>alert('Success! Pleas check your spam folder!')</script>");
    }else{
        $error_email = '<div class="alert alert-danger "> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b> This email is not in our records!</div>'; 
        header('Locaton: recover.php');
    }
 }
 ?>
<html>
<head>
    <title>Coffee</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/slideshowindex.css">
    <style type="text/css">
    a, a:hover{
        color: black;
        text-decoration: none;
    }
    .center-inputt{
        position: absolute;
        top: 30%;
    }
    </style>
</head>
<body>

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
            <button class="btn register" type="button" data-parent="#accordion"data-toggle="collapse" href="#login" data-target="#register"><a href="index.php">Sign up</a></button>
            <button class="btn login" type="button" data-parent="#accordion" data-toggle="collapse" href="#login" data-target="#login"><a href="index.php">Log in</a></button>
        </div>
        
        <div class="container center-inputt">

            <form action="" method="POST" class="form-inline">
                <legend class="">Recover Password</legend>
                
                <div class="form-group">
                    <div class="">
                        <input id="email" name="email" type="email" placeholder="Email" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <button type="submit" id="" class="btn btn-primary">Recover</button>
                    </div>
                </div><?php 
                 if(isset($_POST['email']))
                    {
                      if (!empty($error_email)) {
                             echo($error_email);
                      }
                    }
                 ?>
            </form>
        </div>

        <div class="container stayconn">
            <h1 class="fyrri">Stay</h1><h1 class="seinni">Connected</h1>
        </div>
    </div>
</div>
<footer>
    <button id="stopss" onclick="noss()" id="close_popup" class="btn stopss">Toggle slideshow</button>
</footer>
</body>
<script src="scripts/jquery.js"></script>
<script src="scripts/bootstrap.js"></script>

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
</html>
