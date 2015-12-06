<?php 
include 'include/config.php';
session_start();
$log[] = array();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
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
    }
}

$id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (isset($_POST['content'])) {
    $user_id = $_SESSION['id'];
    $id_post = time();
    $content = strip_tags($_POST['content']);
    $title = strip_tags($_POST['title']);
    $content = mysqli_real_escape_string($db, $content);
    $title = mysqli_real_escape_string($db, $title);
    $query = "INSERT INTO post (id, post, user_id, title, content) VALUES ('$id_post', '$id_post', '$user_id', '$title', '$content');";
    $result = mysqli_query($db, $query);
    if (!$result){
        echo('<script> alert("We could not submit your post at this time, please try again later!")</script>');
    }else{
        // Til að það komi ekki "Confirm Form Resubmission"
        header('Location: _home.php');
    }
}

$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $id . " AND unread = 'yes' "));
   
   
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - Coffee</title>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script type='text/javascript' charset="UTF-8" src='http://code.jquery.com/jquery-1.8.2.js'></script>
    <style type="text/css">
        .vanilla{
          display: block;
        }
        .vanilla img{
          margin-top: -.8em;
        }
        .vanilla h4{
          padding-left: 2.5em;
        }
    </style>
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
          <a class="navbar-brand" href="index.html">Coffee</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#tf-home" class="page-scroll">Home</a></li>
            <li><a href="#tf-about" class="page-scroll">About</a></li>
            <li><a href="#tf-team" class="page-scroll">Team</a></li>
            <li><a href="#tf-services" class="page-scroll">Services</a></li>
            <li><a href="#tf-works" class="page-scroll">Portfolio</a></li>
            <li><a href="#tf-testimonials" class="page-scroll">Testimonials</a></li>
            <li><a href="logout.php" class="page-scroll"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!-- Sidebar -->
    <div class="container-fluid" id="sidebar-nav-fixed affix">
            <ul class="sidebar-nav ">
                <li class="sidebar-brand">
                    <a href="_home.php">
                        Home
                    </a>
                </li>
                <li>
                    <a class="active" href="_home.php">Home</a>
                </li>
                <li>
                    <a href="#">Shortcuts</a>
                </li>
                <li>
                    <a href="#">Overview</a>
                </li>
                <li>
                    <a href="#">Events</a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Services</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
        </div>
    <div class="container">
        <div class="content container-low col-lg-7 col-md-7 col-sm-7">
        <form class="well col-lg-12" action="" method="POST">
            <input name="title" id="title" placeholder="Add Title...">
            <textarea name="content" class="col-lg-12" placeholder="Share something with your friends!" required></textarea>
            <button type="submit" id="postbutton" class="createpost">Post <i class="fa fa-share"></i></button>
            <button type="button" id="postbutton" class="addphoto"><i class="fa fa-camera-retro"></i></button>
            <button type="button" id="postbutton" class="addtitlee"><i class="fa fa-plus"></i> Add Title</button>
        </form>
        

            <?php 
                    $friends = array();
                    $sql = "SELECT sender_id, receiver_id FROM friend_request WHERE sender_id = $id";
                    $result = $db->query($sql);
                    if (is_object($result) && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $receiver = $row['receiver_id'];
                            array_push($friends, $receiver);
                        }
                    } else {
                        echo "<p>You are not following anyone.. Search for people!</p><br>";
                    }
                        
                        $requests[] = array();
                        $users[] = array();
                        $main[] = array();
                        $receiver = array();
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

                        $sql = "SELECT post.id, user.image, user.fullname, post.likes, post.title, post.user_ID, post.content, post.date_time FROM user JOIN post ON user.id = post.user_id";
                        $sql .= " WHERE post.user_id = " . $id . "";

                        for ($i=1; $i < count($users); $i++) { 
                            $sql.= " OR post.user_id = " . $users[$i];
                        }
                        $sql .= " ORDER BY post.date_time DESC LIMIT 1";
                        $result = mysqli_query($db, $sql); 
                        if (is_object($result) && $result->num_rows > 0) {
                            // output data of each row
                            while(is_object($result) && $row = $result->fetch_assoc()) {
                                for ($i=0; $i < count($log); $i++) {    
                                  $AJAXid = $row['id'];
                                    echo("<div class='well col-lg-12 col-md-12 col-sm-12'><hr>
                                                <button class='deletepost' style='float:right; position: absolute; top: 1px; right: 1px;'><i class='fa fa-times'></i></button>
                                                <div class='date'><small> <a class='smalltext' style='float: right' href='post.php?post=" . $row['id'] . "'>" . $row['date_time'] . " </a></small></div><div class='vanilla'>
                                                <img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $row['image'] . "'</img> <h4><a href='profile.php?id=" . $row['user_ID'] . "'>" . $row['fullname'] . "</a></h4></div>
                                                <div class='postcontent' style='margin-top: 2em;'><h4>" . $row['title'] . "</h4>" . $row['content'] . "</div><hr>
                                                <form action='' method='POST'>
                                                  <input type='hidden' value='" . $row['id'] . "' id='buttonid" . $row['id'] . "' name='buttonid" . $row['id'] . "'>
                                                  <button class='likebutton' id='" . $row['id'] . "' name='" . $row['id'] . "'><i class='fa fa-heart-o'></i></button><span class='likecounter'>" . $row['likes'] . "</span> 
                                                  <button class='opencomments'><i class='fa fa-comments'></i></button><span class='commentcounter'>3</span></div>
                                                </form>
                                                ");
                                ?>
                                <script>
                                      $("#buttonid<?php echo $AJAXid ?>").on('click',function(){

                                          // get the values from the input fields
                                          var AJAXid = $('#buttonid<?php echo $AJAXid ?>')[0].value;
                                        
                                          $.ajax({
                                              url: "assets/php/like.php",
                                              type: "POST",
                                              data: {
                                                AJAXid: AJAXid
                                              },
                                              success: function(data){
                                                  if (data == true) {
                                                    console.log(data);
                                                  }
                                                  else{
                                                    console.log(data);
                                                  }
                                              }
                                          })
                                       })
                                </script>
                                <?php
                                  }
                                }
                        } else {
                            echo "<h2 style='color: red'>No Posts to show, post something!</h2>";
                        }
                 ?>
        </div>
    </div>

</body>
</html>
<script type='text/javascript' charset="UTF-8" src='http://code.jquery.com/jquery-1.8.2.js'></script>
    

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
