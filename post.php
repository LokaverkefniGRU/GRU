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


// $query = "SELECT post.id, user.image, user.fullname, post.content, post.date_time FROM user JOIN post ON user.id = post.user_id WHERE id = " . $postid . "";
// // SELECT * FROM post WHERE id = " . $postid . "";
// $result = mysqli_query($db, $query);

?>

<html>
<head>
	<title>Post</title>
	<link rel="stylesheet" type="text/css" href="eddi/style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script type='text/javascript' charset="UTF-8" src='http://code.jquery.com/jquery-1.8.2.js'></script>
</head>
<body>

	<div class="container">
<?php
$AJAXid = $_GET['post'];
$query = "SELECT COUNT(user_id) AS likes FROM post_like WHERE post_id = $AJAXid";//velur fjölda likes á þessum post sem likes nafnið er til að einfalda kóðann
$outcome = mysqli_query($db, $query); #útkoman úr queryinu
$likes = $outcome -> fetch_assoc();

 	$comment_query = "SELECT * FROM comment WHERE post_id = '" . $AJAXid . "'";
 	$comment_result = mysqli_query($db, $comment_query);
 	$commet_row = $comment_result -> fetch_assoc();

	$query = "SELECT post.id, user.image, user.fullname, post.likes, post.title, post.user_ID, post.content, post.date_time, post.photo FROM post JOIN user ON user.id = post.user_id WHERE post.post = $postid ORDER BY date_time DESC";
	$result = mysqli_query($db, $query);
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	        echo("<div class='well col-lg-12 col-md-12 col-sm-12' name='" . $row['id'] . "'><hr>
                <button class='deletepost' style='float:right; position: absolute; top: 1px; right: 1px;'><i class='fa fa-chevron-down'></i></button>
                <div class='date'><small> <a class='smalltext' style='float: right' href='post.php?post=" . $row['id'] . "'>" . $row['date_time'] . " </a></small></div><div class='vanilla'>
                <img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $row['image'] . "'</img> <h4><a href='profile.php?id=" . $row['user_ID'] . "'>" . $row['fullname'] . "</a></h4></div>
                <div class='postcontent' style='margin-top: 2em;'><h2>" . $row['title'] . "</h2>" . $row['content'] . "</div><hr>");
                if ($row['photo'] != NULL) {
                  echo("<img class='postcontentphoto' style='width: 100%; height: auto; max-height: 1000px;' src='" . $row['photo'] . "'></img><hr>");
                }
                                    
                echo("<input type='hidden' value='" . $row['id'] . "' id='buttonidd" . $row['id'] . "' name='buttonidd" . $row['id'] . "'>
                <button type='button' class='likebutton' id='" . $row['id'] . "' name='" . $row['id'] . "'><i class='fa fa-heart-o'></i></button><span class='likecounter' id='likecounter" . $row['id'] . "'>" . $likes['likes'] . "</span> 
                <button type='button' class='opencomments' data-toggle='collapse' data-target='#com" . $row['id'] . "'><i class='fa fa-comments'></i></button><span class='commentcounter'>3</span>
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
	    
	}
?>
</div>
</body>
</html>





