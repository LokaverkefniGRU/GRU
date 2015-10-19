<?php 
include 'include/config.php';

if(isset($_POST['reg_username'])) {
    $reg_username = strip_tags($_POST['reg_username']);
    $reg_password = strip_tags($_POST['reg_password']);
    $reg_name = strip_tags($_POST['reg_name']);
    $reg_email = strip_tags($_POST['reg_email']);
    $reg_simanumer = strip_tags($_POST['reg_simanumer']);

    $reg_username = mysqli_real_escape_string($db, $reg_username);
    $reg_password = mysqli_real_escape_string($db, $reg_password);
    $reg_password = md5($reg_password);
    $reg_name = mysqli_real_escape_string($db, $reg_name);
    $reg_email = mysqli_real_escape_string($db, $reg_email);
    $reg_simanumer = mysqli_real_escape_string($db, $reg_simanumer);
    $query = "INSERT INTO `0712982139_verkefni4`.`notandi` (`id`, `username`, `password`, `nafn`, `simanumer`, `netfang`) VALUES (NULL, '$reg_username', '$reg_password', '$reg_name', '$reg_simanumer', '$reg_email');";
    $result = mysqli_query($db, $query);
    if (!$result) {
        echo '<script type="text/javascript">alert("Það tókst ekki að skrá þig í gagnagrunnin okkar")</script>';
    }else{
        echo '<script type="text/javascript">alert("Skráning tókst. Vinsamlegast skráðu þig inn.")</script>';
    }
}


$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'img/'; 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'index.php'; 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'seccess.php'; 
$fieldname = 'file'; 

$errors = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 

isset($_POST['submit']) 
    or error('the upload form is neaded', $uploadForm); 

($_FILES[$fieldname]['error'] == 0) 
    or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 
     
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
    or error('not an HTTP upload', $uploadForm); 
     
@getimagesize($_FILES[$fieldname]['tmp_name']) 
    or error('only image uploads are allowed', $uploadForm); 

// $sida = time();
// while(file_exists($uploadFilename = $uploadsDirectory.$sida.'_'.$_FILES[$fieldname]['name'])) 
// { 
//     $sida++; 
// } 

$sida = time();
while(file_exists($uploadFilename = $uploadsDirectory.$_FILES['api']['name'])) 
{ 
    $sida++; 
} 

@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
    or error('receiving directory insuffiecient permission', $uploadForm); 
     
header('Location: ' . $uploadSuccess); 

function error($error, $location, $seconds = 5) 
{ 
    header("Refresh: $seconds; URL=".$location.""); 
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
    <html lang="en">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
            <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <title>Upload error</title>
        </head>
        <body>
        <div id="Upload">
            <h1>Upload failure</h1>
            <p>An error has occurred: 
            <span class="red">' . $error . '...</span>
             The upload form is reloading</p>
         </div>
    </html>'; 
    exit; 
} // end error handler 

?> 