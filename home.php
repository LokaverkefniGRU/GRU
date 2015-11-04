<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}
// if (isset($_SESSION['id'])){
//     $query = "UPDATE `user` SET  `online` =  '1' WHERE `id` = '" . $_SESSION['id'] . "';";
//     $result = mysqli_query($db, $query);
// }


$id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (isset($_POST['content'])) {
    $user_id = $_SESSION['id'];
    $id = time();
    $content = strip_tags($_POST['content']);
    $content = mysqli_real_escape_string($db, $content);
    $query = "INSERT INTO post (id, user_id, content) VALUES ('$id', '$user_id', '$content');";
    $result = mysqli_query($db, $query);
    if (!$result){
        echo('<script> alert("We could not submit your post at this time, please try again later!")</script>');
        header('Location: home.php');
    }else{
        // Til að það komi ekki "Confirm Form Resubmission" i chrome
        header('Location: home.php');
    }
}
?>

<html lang="en"> 
    <head> 
    <title><?php echo($title['global']) ?></title>
    <?php styles(); ?>
    <!-- <link href="style/style.css" rel="stylesheet" type="text/css" /> -->
    </head> 
    <style type="text/css">
    body{
        background: #fff;
    }
    .col-xs-6,.col-sm-3{
        
    }
    .col-md-8{
        
    }
    .container-low{
    margin-top: 5em;

}
.search-bar{
    float: left;
    
}
    </style>
    <body>
    <div class="main-nav">
     <nav class="navbar navbar-default navbar-fixed-top navbar-home" role="navigation">
             <div class="container">
             <div class="row search-bar">
                
                
             </div>
                 <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> <!--.navbar-header -->

                 <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                     <ul class="nav navbar-nav navbar-right">
                         <li class="dropdown">
                             <a href="/" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Home</a>
                             <ul class="dropdown-menu">
                                 <li><a href="#" class="launch-modal" data-modal-id="modal-login">Create Post</a></li>
                             </ul>
                         </li>
                         <li><a href="#">Blog</a></li>
                         <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="profile.php" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"><?php echo($user['fullname'])?></a>
                                    <ul class="dropdown-menu">
                                            <li><a href="editprofile.php">Edit Profile</a></li>
                                            <li><a href="logout.php"><i class="fa fa-sign-in"></i> Logout</a></li>
                                        </ul>
                                    </li>
                            </ul>
                            </li>
                     </ul>
                     </div>
             </div>
         </nav>
     </div>
        <!-- <input type="text" id="search" autocomplete="off">
        <ul style="border:1px solid black" id="results"></ul> -->
   <!-- LOGIN -->
        <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-login-label">Create Post</h3>
                        <p>Be Creative!</p>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form role="form" action="" method="post" class="registration-form">
                            <div class="form-group">
                                <label class="sr-only" for="form-about-yourself">Content</label>
                                <textarea name="content" placeholder="Content..." class="form-about-yourself form-control" id="form-about-yourself"></textarea>
                            </div>
                                                       
                            <button type="submit" class="btn">Post</button>
                        </form>
                        
                    </div>
                    
                </div>
            </div>
        </div>

    <div class="container">
        <div class="container-low">
            <div class="row">
                <?php 
                    // $sql = "SELECT user.firstname, user.fullname, post.ID, post.content, post.user_ID, post.time FROM post
                    //         JOIN user ON post.user_ID = user.ID
                    //         JOIN friend_request ON user.ID = friend_request.receiver_ID
                    //         WHERE friend_request.sender_ID = $id OR friend_request.receiver_ID = $id AND confirmed =1";
                    $sql = "SELECT * FROM post";
                    //$sql = "SELECT * FROM user";
                    $result = $db->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo($row['content'] . " - <a href=http://lokaverkefni.cf/profile.php?id=" . $row['user_id'] . ">" . $row['post_username'] . "</a><br>");
                        }
                    } else {
                        echo "0 results";
                    }
                 ?>

            </div>
        </div>
    </div>
    </body> 
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</html> 