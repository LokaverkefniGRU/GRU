<?php 
include 'include/config.php';
session_start();

// Til að ná í fyrir post
$postid = $_GET['post'];
$query = "SELECT * FROM post WHERE id = '$postid' LIMIT 1";
$result = mysqli_query($db, $query);
$post = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$post && !isset($_SESSION['id'])) {
    header('Location: index.php');
}else if (!$post) {
	header('Location: 404.php');
}

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $user_id . " AND unread = 'yes' "));

// $query = "SELECT post.id, user.image, user.fullname, post.content, post.date_time FROM user JOIN post ON user.id = post.user_id WHERE id = " . $postid . "";
// // SELECT * FROM post WHERE id = " . $postid . "";
// $result = mysqli_query($db, $query);

$query = "SELECT user.image, user.fullname, post.content, post.date_time FROM post JOIN user ON user.id = post.user_id WHERE post.post = $postid ORDER BY date_time DESC";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    for ($i=0; $i < count($result); $i++) { 
        echo("<div class='well col-lg-12 col-md-12 col-sm-12'><hr>
                                                <button class='deletepost' style='float:right; position: absolute; top: 1px; right: 1px;'><i class='fa fa-times'></i></button>
                                                <div class='date'><small> <a class='smalltext' style='float: right' href='post.php?post=" . $row['id'] . "'>" . $row['date_time'] . " </a></small></div><div class='vanilla'>
                                                <img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $row['image'] . "'</img> <h4><a href='profile.php?id=" . $row['user_ID'] . "'>" . $row['fullname'] . "</a></h4></div>
                                                <div class='postcontent' style='margin-top: 2em;'><h4>" . $row['title'] . "</h4>" . $row['content'] . "</div><hr>
                                                <form action='' method='POST'>
                                                  <input type='hidden' value='" . $row['id'] . "' id='buttonid" . $row['id'] . "' name='buttonid" . $row['id'] . "'>
                                                  <button class='likebutton' id='" . $row['id'] . "' name='" . $row['id'] . "'><i class='fa fa-heart-o'></i></button><span class='likecounter'>" . $row['likes'] . "</span> 
                                                  <button class='opencomments'><i class='fa fa-comments'></i></button><span class='commentcounter'>3</span></div>"
    }
}


?>
