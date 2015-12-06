<?php 
include 'include/config.php';
session_start();
$log[] = array();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    die();
}

$id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

function fyrsta($id, $db) {
        $sql = "SELECT sender_id, receiver_id FROM friend_request WHERE sender_id = " . $id;
        $result = $db->query($sql);
    if ($result->num_rows > 0) { // Þessi if loopa kemur í veg fyrir að $log verði alltaf og þá færðu error ef þú ert ekki að followa nein!
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $log[] = array('sender' => $row['sender_id'], 'receiver' => $row['receiver_id']);
        }
        return $log;
    }else{
        // Skilar tómu til að koma í veg fyrir að $log verði alltaf og þá færðu error ef þú ert ekki að followa nein!
      echo '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>Attention! </b><br> You are not following anyone.. Search for people!</div>';
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

      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions) === false){
         $errors[]="Extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 5000000+6){
         $errors[]='File size must be less than 5mb!';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/public/" . $file_name);
         $link = ("images/public/" . $file_name);
       }
   }

if (isset($_POST['content'])) {
    $user_id = $_SESSION['id'];
    $id_post = time();
    $content = strip_tags($_POST['content']);
    $title = strip_tags($_POST['title']);
    $content = mysqli_real_escape_string($db, $content);
    $title = mysqli_real_escape_string($db, $title);
    $query = "INSERT INTO post (id, post, user_id, title, content, photo) VALUES ('$id_post', '$id_post', '$user_id', '$title', '$content', '$link');";
    $result = mysqli_query($db, $query);
    if (!$result){
        echo('<script> alert("We could not submit your post at this time, please try again later!")</script>');
    }else{
        // Til að það komi ekki "Confirm Form Resubmission"
        header('Location: home.php');
        die();
    }
}

$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM message WHERE to_user = " . $id . " AND unread = 'yes' "));

?>
<!DOCTYPE html>
<html>
<head>
    <title>Coffee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="eddi/style/bootstrap.css">
    <link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
    <link href="style/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="eddi/style/snippets.css">
    <script src="scripts/jquery.js"></script>
<style type="text/css">
        .vanilla{
          display: block;
        }
        .vanilla img{
          margin-top: -.8em;
        }
        .vanilla h4{
          padding-left: 4em;
        }
        .btn-file {
          background: none;
          position: absolute;
          bottom: 20px;
          left: 20px;
          
          overflow: hidden;
        }
        .btn-file:hover {
          color: lightblue;
        }
        .btn-file input[type=file] {
          position: absolute;
          top: 0;
          right: 0;
          text-align: left;
          filter: alpha(opacity=0);
          opacity: 0;
          background: red;
          cursor: inherit;
          display: block;
        }
        input[readonly] {
          background-color: white !important;
          cursor: text !important;
        }
        span.form-control-feedback {
          position: absolute;
          top: 10px;
          right: -2px;
          z-index: 2;
          display: block;
          width: 34px;
          height: 34px;
          line-height: 34px;
          text-align: center;
          color: #3596e0;
          left: initial;
          font-size: 14px;
        }
        h2{
          font-weight: bold;
          margin-bottom: .5em;
        }
    </style>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
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
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="home.php" class="page-scroll">Home</a></li>
            <li><a href="messages.php" class="page-scroll">Messages<?php if($pmcount[0] > 0) {echo(' <span class="badge">' . $pmcount[0] . '</span>');}else{} ?></a></li>
            <li><a href="profile.php" class="page-scroll"><?php echo $user['fullname']  ?></a></li>
            <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!-- Sidebar -->
    <div class="container-fluid" id="sidebar-nav-fixed affix">
            <ul class="sidebar-nav ">
                <li class="sidebar-brand">
                    <a href="home.php">
                        Home
                    </a>
                </li>
                <li>
                    <a class="active" href="home.php">Home</a>
                </li>
                <li>
                    <a href="messages.php">Messages</a>
                </li>
                <li>
                    <a href="myGroups_events.php">Groups</a>
                </li>
                <li>
                    <a href="myGroups_events.php">Events</a>
                </li>
                <li>
                    <a href="followers.php">Followers</a>
                </li>
                <li>
                    <a href="following.php">Following</a>
                </li>
                <li>
                    <input type="search" id="search" placeholder="Search for people" autocomplete="off">
                </li><ul id="results"></ul>
                
            </ul> 
        </div>
    <div class="container">

  
        <div class="content container-low col-lg-7 col-md-7 col-sm-7">
        <form class="well col-lg-12" action="" method="POST" enctype="multipart/form-data">
            <input name="title" id="title" placeholder="Add Title...">
            <textarea name="content" class="content" id="post-input" placeholder="Share something with your followers!" required></textarea>
            <button type="submit" id="postbutton" class="createpost">Post <i class="fa fa-share"></i></button>
            <button title="Max 5Mb" type="button" id="postbutton" class="addphoto"><span class="file-input btn-default btn-file addphoto"><i class="fa fa-camera-retro"></i><input type="file" name="image" id="image" accept="image/*"></span></button>
            <button type="button" id="postbutton" class="addtitlee"><i class="fa fa-plus"></i> Add Title</button>
        </form>
            <?php 
                        $requests[] = array();
                        $users[] = array();
                        $main[] = array();
                        $receiver = array();
                        $comment_error[] = "";
                        $id = $_SESSION['id'];#session
                        $fjoldiPosta = 10;

                        $requests = fyrsta($id, $db);

                        for ($i=0; $i < count($requests); $i++) { 
                            if ($requests[$i]["sender"] != $id) {
                                array_push($users, $requests[$i]["sender"]);
                            }
                            else{
                                array_push($users, $requests[$i]["receiver"]);
                            }
                        }

                        $sql = "SELECT post.id, user.image, user.fullname, post.likes, post.title, post.user_ID, post.content, post.photo, post.date_time FROM user JOIN post ON user.id = post.user_id";
                        $sql .= " WHERE post.user_id = " . $id . "";

                        for ($i=1; $i < count($users); $i++) { 
                            $sql.= " OR post.user_id = " . $users[$i];
                        }
                        $sql .= " ORDER BY post.date_time DESC LIMIT 10";
                        $result = mysqli_query($db, $sql); 
                        if (is_object($result) && $result->num_rows > 0) {
                            // output data of each row
                            while(is_object($result) && $row = $result->fetch_assoc()) {
                                for ($i=0; $i < count($log); $i++) {    
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
                                }
                        } else {
                            echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>No Posts :( </b><br> No post to show, post something!</div>';
                        }
                 ?>
        </div>
        <div class="container-low text-center col-lg-4 col-md-4 col-sm-4">
          
        </div>
    </div>

</body>
</html>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
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
