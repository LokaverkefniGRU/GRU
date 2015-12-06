<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}

$pass_errors[] = "";

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM user WHERE id = $user_id";
$result = mysqli_query($db, $sql);
$user = $result -> fetch_assoc();

$group_id = $_GET['id'];
$sql = "SELECT * FROM hopar WHERE id = $group_id";
$result = mysqli_query($db, $sql);
$row = $result -> fetch_assoc();
$name = $row['name'];
$host_ID = $row['host_ID'];

$sql = "SELECT user_ID, stada FROM hopar_join WHERE user_ID = $user_id";
$result = mysqli_query($db, $sql);
$row = $result -> fetch_assoc();
$user_status = $row['stada'];


?>
 <!DOCTYPE html>
 <html>
 <head>
 	  <title><?php echo $name; ?></title>
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
<?php echo "<a href='group_members.php?id=" .$group_id . "'>View members</a>"; ?>

    <h2><?php echo $name; ?></h2>
    <form class="well col-lg-12" action="postInGroup.php?id=<?php echo $group_id; ?>" method="POST">
        <input name="title" id="title" placeholder="Add Title..."><br>
        <textarea name="content" class="col-lg-12" placeholder="Share something with your friends!" required></textarea><br>
        <button type="submit" id="postbutton">Post <i class="fa fa-share"></i></button><br>
        <button type="button" id="postbutton" class="addphoto"><span class="file-input btn-default btn-file addphoto"><i class="fa fa-camera-retro"></i><input type="file" onchange="readURL(this);" name="files[]" accept="image/*"  multiple></span></button><br>
        <button type="button" id="postbutton" class="addtitlee"><i class="fa fa-plus"></i> Add Title</button><br>
    </form>
    <?php

        $sql = "SELECT post.id, user.image, user.fullname, post.likes, post.title, post.user_ID, post.content, post.photo, post.date_time FROM user JOIN post ON user.id = post.user_id WHERE in_group=1 AND group_id=$group_id ORDER BY date_time DESC";
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

                                    $comment_query = "INSERT INTO `comment` (`id`, `post_id`, `user_id`, `time`, `content`) VALUES ('" . $id_comment . "', '" . $AJAXid . "', '" . $user_id . "', CURRENT_TIMESTAMP, '" . $comment . "');";
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
                                        echo '<script>window.location = "home.php";</script>';
                                      }else{
                                        $query_comment = "DELETE FROM `comment` WHERE `comment`.`post_id` = " . $AJAXid . "";
                                        $result_comment = mysqli_query($db, $query_comment);
                                        if (!$result_comment) {
                                          echo "<script> alert('Sorry, we could not delete this post.. (Delete from comment table) ')</script>";
                                          echo '<script>window.location = "home.php";</script>';
                                        }else{
                                          $query_post = "DELETE FROM `post` WHERE `post`.`id` = " . $AJAXid . "";
                                          $result_post = mysqli_query($db, $query_post);
                                          if (!$result_post) {
                                            echo "<script> alert('Sorry, we could not delete this post.. (Delete from post table) ')</script>";
                                            echo '<script>window.location = "home.php";</script>';
                                          }else{
                                            echo "<script> alert('Success!')";
                                            echo '<script>window.location = "home.php";</script>';
                                          }
                                        }
                                      }
                                    }else{
                                    echo "<script> alert('You can only delete your own posts!')</script>";
                                    echo '<script>window.location = "home.php";</script>';
                                  }
                                  }

                                    echo("<div class='well col-lg-12 col-md-12 col-sm-12' name='post" . $row['id'] . "'><hr>
                                              <form action='' method='POST'>
                                                <button type='submit' name='delete" . $row['id'] . "' class='deletepost' style='float:right; position: absolute; top: 1px; right: 1px;'><span><i class='fa fa-times'></i></span></button>
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
                                                var like = JSON.parse(data);
                                                document.getElementById('likecounter<?php echo $AJAXid ?>').innerHTML = like.likes;
                                              }
                                          })
                                       });   
                                 </script>
                                <?php
                                  }
                                } else {
                            echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>No Posts :( </b><br> No post to show, post something!</div>';
                        }
 ?>

</div>
 </body>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
 <script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
  
 </html>