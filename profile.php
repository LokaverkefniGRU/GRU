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

// Follow counter
$query = "SELECT COUNT(*) AS followers FROM friend_request WHERE receiver_ID = '$profileid'";
$result = mysqli_query($db, $query);
$followcount = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Resend email
if (isset($_POST['send_email'])) {
    // Býr til nýjan kóða til að staðfesta emailið þegar hann ítir a takkan
    $confirm_code = md5(uniqid(rand()));
    $query = "UPDATE `user` SET  `confirm_code` =  '" . $confirm_code . "' WHERE  `user`.`id` = " . $user_id . ";";
    $result = mysqli_query($db, $query);

    // Send email
    $to      = $user['email'];
    $subject = 'Please confirm your email for lokaverkefni.com';
    $message = 'Your confirmation link: https://lokaverkefni.com/confirmation.php?passkey=' . $confirm_code .'';
    $headers = 'From: no-reply@lokaverkefni.com' . "\r\n" .
        'Reply-To: no-reply@lokaverkefni.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);    
}

if (isset($_POST['new_password'])) {
    $Salt = md5(uniqid(time()));
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
}

// Adda sem vin
$query = "SELECT * FROM friend_request WHERE sender_ID = '$user_id' AND receiver_ID = '$profileid'";
$result = mysqli_query($db, $query);
$request = mysqli_fetch_array($result, MYSQLI_ASSOC);

$friend_error = array();

// Til að adda vini
if(isset($_POST['add'])){
    $query  = "INSERT INTO `friend_request`(`sender_ID`, `receiver_ID`,`sent`,`received`) VALUES ($user_id, $profileid, 1, 1)";
    $result = mysqli_query($db, $query);
    if (!$result) {
        $friend_error[] = "Sorry you cant follow this person!";
        print_r($friend_error[0]);
    }
    header('Location: profile.php?id=' . $profileid);
}

// Hætta við friend request
if(isset($_POST['unadd'])){
    $query  = "DELETE FROM `friend_request` WHERE `friend_request`.`sender_ID` = '$user_id' AND `friend_request`.`receiver_ID` = $profileid";
    $result = mysqli_query($db, $query);
    if (!$result) {
        $friend_error[] = "Sorry you cant unfollow this person!";
        print_r($friend_error[0]);
    }
    header('Location: profile.php?id=' . $profileid);
}

$valid_formats = array("jpg", "png", "gif", "zip", "bmp");
$max_file_size = 10240*1000; //100 kb
$path = "images/public/"; // Upload directory
$count = 0;

if(isset($_POST['files']) and $_SERVER['REQUEST_METHOD'] == "POST"){
    // Loop $_FILES to execute all files
    foreach ($_FILES['files']['name'] as $f => $name) {     
        if ($_FILES['files']['error'][$f] == 4) {
            continue; // Skip file if any error found
        }          
        if ($_FILES['files']['error'][$f] == 0) {              
            if ($_FILES['files']['size'][$f] > $max_file_size) {
                $message[] = "$name is too large!.";
                continue; // Skip large files
            }
            elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
                $message[] = "$name is not a valid format";
                continue; // Skip invalid file formats
            }
            else{ // No error found! Move uploaded files 
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$name)) {
                    $count++; // Number of successfully uploaded files
                }
            }
        }
    }
}                  
# error messages
if (isset($message)) {
    foreach ($message as $msg) {
        printf($msg);
    }
}
#success message
if($count != 0){
    printf($count);                        
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
         move_uploaded_file($file_tmp,"images/cover/" . $file_name);
         $link = ("images/cover/" . $file_name);
         $query = "UPDATE `user` SET `image` = '" . $link . "' WHERE `user`.`id` = '" . $user_id . "';";
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
    <title><?php echo $profile['fullname']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style type="text/css">
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
        .vanilla{
          display: block;
        }
        .vanilla img{
          margin-top: -.8em;
        }
        .vanilla h4{
          padding-left: 2.5em;
        }
        .profilepicheader{ 
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
        }
        .profile-photo{
            height: 120px; 
            width: 120px; 
            position: absolute;
            top: -17%;
            right: 37%;
            max-width: 100%;
            max-height: 100%;
        }
        .profileheader{
    background-color: #F5F5F5;
    height: 17em;
    background-image: url('<?php echo($profile["coverimage"])?>') ;
    background-size: cover;
}
.content{
}
@media screen and (min-width: ){

}

h2{
    font-size: 16px;
    font-weight: bold;
}

    </style>
    </head> 
    <body>
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
<div class="container">
<div class="container-low col-sm-12 col-md-12">

<?php
if ($_GET['id'] == $user_id && $user['confirmed'] == 0) {
                    echo'   
                    <div class="bg-danger bs-callout bs-callout-danger col-sm-12 col-md-12 col-lg-12">
                        <h4>You need to confirm your email!</h4>
                        <form action="profile.php" method="POST"><button type="submit" name="send_email" class="btn btn-danger">Re-send confirmation</button></form>
                    </div>';
                }

                if ($_GET['id'] == $user_id && $user['change_password'] == 1) {
                        echo'   
                        <div class="bg-danger bs-callout bs-callout-danger col-sm-12 col-md-12 col-lg-12">
                            <h4>Please Change Your Password!</h4>
                            <button type="button" href="#" class="btn btn-danger" data-toggle="collapse" data-target="#pass">Change Password!</button>
                            <div id="pass" class="collapse">
                            <hr>
                            <form role="form" action="" method="post" class="registration-form">
                                <div id="html_element"></div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password">Password</label>
                                        <input type="password" name="new_password" placeholder="New Password..." class="form-username form-control" id="reg_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password">Password</label>
                                        <input type="password" name="new_password2" placeholder="New Password Again..." class="form-username form-control" id="reg_password2" required>
                                    </div>       
                                    <button type="submit" value="getResponse" class="btn btn-danger">Change!</button>
                                </form>
                            </div>
                        </div>';
                    }
?>

 </div>




    <div class="well profileheader col-sm-5 col-md-5 col-lg-5">
    <form action="" method="POST">
    <?php 
            if ($request['sent'] == 1 && $request['receiver_ID'] == $profileid && $request['sender_ID'] == $user_id) {
                echo('<button type="submit" class="btn btn-primary followbtn redbtn" name="unadd"><span class="glyphicon glyphicon-minus"></span> Unollow</button>');            
            }elseif($profileid == $user_id){
                echo('<button class="btn btn-primary followbtn" name="edit"><a href="editprofile.php" class="editprofile" <span class="glyphicon glyphicon-edit"></span> Edit Profile</a></button>');
            }else{
                echo('<button type="submit" class="btn btn-primary followbtn" name="add"><span class="glyphicon glyphicon-plus"></span> Follow</button>');              
            }
            
    ?>
    </form>
        <div class="cover-container">
            <?php
                echo("<img class='profile-photo img-thumbnail img-circle' style='' src='../" . $profile['image'] . "'>");
            ?>
        </div>
        <div class="followerdiv">
        <span class="followersSPAN hr"><a href="followers.php">FOLLOWERS</a></span>
        <span class="followercounter"><a href="followers.php"><p class="followcount"><?php print_r($followcount['followers'])?></p></a></span>
        </div>

        <span class="profilefullname"><hr><?php echo($profile['fullname'])?></span>
    </div>


        <div class="content col-lg-7 col-md-7 col-sm-7">

        

            <?php 
                
                  
                        $sql = "SELECT post.id, user.image, user.fullname, post.likes, post.title, post.user_ID, post.content, post.date_time, post.photo FROM user JOIN post ON user.id = post.user_id WHERE post.user_id = " . $profileid . " ORDER BY date_time DESC";
                        $result = mysqli_query($db, $sql); 
                        if (is_object($result) && $result->num_rows > 0) {
                            // output data of each row
                            while(is_object($result) && $row = $result->fetch_assoc()) {
                                  $AJAXid = $row['id'];

                                  $query = "SELECT COUNT(user_id) AS likes FROM post_like WHERE post_id = $AJAXid";//velur fjölda likes á þessum post sem likes nafnið er til að einfalda kóðann
                                  $outcome = mysqli_query($db, $query); #útkoman úr queryinu
                                  $likes = $outcome -> fetch_assoc();

                                  $comment_query = "SELECT * FROM comment WHERE post_id = '" . $AJAXid . "'";
                                  $comment_result = mysqli_query($db, $comment_query);
                                  $commet_row = $comment_result -> fetch_assoc();

                                  $query = "SELECT COUNT(post_id) AS post_id FROM comment WHERE post_id = $AJAXid";
                                  $commentcount = mysqli_query($db, $query);
                                  $commentcounter_row = $commentcount -> fetch_assoc();

                                  if (isset($_POST['comment' . $row['id']])) {
                                    $id_comment = time();
                                    $comment = strip_tags($_POST['comment' . $row['id']]);
                                    $comment = mysqli_real_escape_string($db, $comment);

                                    $comment_query = "INSERT INTO `comment` (`id`, `post_id`, `user_id`, `time`, `content`) VALUES ('" . $id_comment . "', '" . $AJAXid . "', '" . $id . "', CURRENT_TIMESTAMP, '" . $comment . "');";
                                    $result = mysqli_query($db, $comment_query);
                                    if (!$result) {
                                      $comment_error[] = '<div class="alert alert-alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Error! </b><br> Error posting comment, try again!</div>';
                                    }else{
                                      echo '<script>window.location = "post.php?post=' . $AJAXid . '";</script>';
                                    }
                                  }

                                if (isset($_POST['delete' . $row['id']])) {
                                    if ($id == $row['user_ID']) {
                                      $query = "DELETE FROM `post_like` WHERE `post_like`.`post_ID` = " . $AJAXid . "";
                                      $result = mysqli_query($db, $query);
                                      if (!$result) {
                                        echo "<script> alert('Sorry, we could not delete this post.. (Delete from like table) ')</script>";
                                        header('Location: profile.php');
                                      }else{
                                        $query_comment = "DELETE FROM `comment` WHERE `comment`.`post_id` = " . $AJAXid . "";
                                        $result_comment = mysqli_query($db, $query_comment);
                                        if (!$result_comment) {
                                          echo "<script> alert('Sorry, we could not delete this post.. (Delete from comment table) ')</script>";
                                          header('Location: profile.php');
                                        }else{
                                          $query_post = "DELETE FROM `post` WHERE `post`.`id` = " . $AJAXid . "";
                                          $result_post = mysqli_query($db, $query_post);
                                          if (!$result_post) {
                                            echo "<script> alert('Sorry, we could not delete this post.. (Delete from post table) ')</script>";
                                            header('Location: profile.php');
                                          }else{
                                            echo "<script> alert('Success!')";
                                            header('Location: profile.php');
                                          }
                                        }
                                      }
                                    }else{
                                    echo "<script> alert('You can only delete your own posts!')</script>";
                                    header('Location: profile.php');
                                  }
                                  }

                                    echo("<div class='well col-lg-12 col-md-12 col-sm-12' name='" . $row['id'] . "'><hr>
                                            <form action='' method='POST'>
                                                <button class='deletepost' name='delete" . $row['id'] . "' style='float:right; position: absolute; top: 1px; right: 1px;'><span><i class='fa fa-times'></i></span></button>
                                            </form>
                                                <div class='date'><small> <a class='smalltext' style='float: right' href='post.php?post=" . $row['id'] . "'>" . $row['date_time'] . " </a></small></div><div class='vanilla'>
                                                <img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $row['image'] . "'</img> <h4><a href='profile.php?id=" . $row['user_ID'] . "'>" . $row['fullname'] . "</a></h4></div>
                                                <div class='postcontent' style='margin-top: 2em;'><h2>" . $row['title'] . "</h2>" . $row['content'] . "</div><hr>");
                                                if ($row['photo'] != NULL) {
                                                  echo("<img class='postcontentphoto' style='width: 100%; height: auto; max-height: 1000px;' src='" . $row['photo'] . "'></img><hr>");
                                                }
                                    
                                    echo("<input type='hidden' value='" . $row['id'] . "' id='buttonidd" . $row['id'] . "' name='buttonidd" . $row['id'] . "'>
                                                  <button type='button' class='likebutton' id='" . $row['id'] . "' name='" . $row['id'] . "'><i class='fa fa-heart-o'></i></button><span class='likecounter' id='likecounter" . $row['id'] . "'>" . $likes['likes'] . "</span> 
                                                  <button type='button' class='opencomments' data-toggle='collapse' data-target='#com" . $row['id'] . "'><i class='fa fa-comments'></i></button><span class='commentcounter'>" . $commentcounter_row['post_id'] . "</span>
                                                    <div id='com" . $row['id'] . "' class='collapse'>");
                                                    $comment_query = "SELECT * FROM comment WHERE post_id = '" . $AJAXid . "' ORDER BY time ASC";
                                                    $comment_result = mysqli_query($db, $comment_query);
                                                    if (is_object($comment_result) && $comment_result->num_rows > 0) {
                                                      while(is_object($comment_result) && $commet_row = $comment_result->fetch_assoc()) {
                                                        echo("<div id='comment_result_post_" . $row['id'] . "'>" . $commet_row['content'] . "</div><br>");
                                                      }
                                                    }else{
                                                      echo("No comments..");
                                                    }
                                                    echo("
                                                      <form action='' method='POST'>
                                                        <input type='text' id='commentid_" . $row['id'] . "' name='comment" . $row['id'] . "' placeholder='Write a comment..'>
                                                        <button type='submit' id='comment_" . $row['id'] . "' class='btn btn-default'>Post comment</button>
                                                      </form>
                                                    </div>
                                                  </div>
                                                ");
                                ?>
        
                                <script>
                                      $("#<?php echo $AJAXid ?>").on('click',function(){

                                          // get the values from the input fields
                                          var buttonidd = $("#buttonidd<?php echo $AJAXid ?>")[0].value;
                                        
                                          $.ajax({
                                              url: "assets/php/like.php",
                                              type: "POST",
                                              cache : false,
                                              data: {
                                                buttonidd: buttonidd
                                              },
                                              success: function(data){
                                                var lala = JSON.parse(data);
                                                document.getElementById('likecounter<?php echo $AJAXid ?>').innerHTML = lala.likes;
                                              }
                                          })
                                       })                                      

                                </script>
                                <?php
                                  
                                }
                        } else {
                            echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>No Posts :( </b><br>' . $profile['fullname'] . ' does not have any posts.</div>';
                        }
                 ?>
        </div>
        <?php
    $sql = "SELECT fullname, about, image FROM user WHERE id = " . $profileid . "";
    $result = $db->query($sql);
?>

    
    </div>



</body>
</html>    

<script type="text/javascript">
    $("textarea").click(function() {
        $(this).height(100);
        $(".createpost").show();
        $(".addtitlee").show();
        $(".createpost").show();
        $(".addphoto").show();
});


    $(".addtitlee").click(function() {
            $("#title").toggle();
        
});


</script>