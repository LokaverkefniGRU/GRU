<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
	header('Location: index.php');
}

// Til að ná í fyrir user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);


if (isset($_POST['f_name'])) {
    $id = $_SESSION['id'];
    $f_name = strip_tags($_POST['f_name']);
    $l_name = strip_tags($_POST['l_name']);
    $full_name = strip_tags($f_name . " " . $l_name);
    $username = strip_tags($_POST['username']);
    $username = strtolower($username);
    $email = strip_tags($_POST['email']);
    $about = strip_tags($_POST['about']);
       
    $f_name = mysqli_real_escape_string($db, $f_name);
    $l_name = mysqli_real_escape_string($db, $l_name);
    $full_name = mysqli_real_escape_string($db, $full_name);
    $username = mysqli_real_escape_string($db, $username);
    $email = mysqli_real_escape_string($db, $email);
    $about = mysqli_real_escape_string($db, $about);
    $query = "UPDATE `user` SET  `username` =  '" . $username . "', `firstname` =  '" . $f_name . "', `lastname` =  '" . $l_name . "', `fullname` =  '" . $full_name . "', `email` =  '" . $email . "',`about` = '" . $about . "' WHERE `id` = '" . $id . "';";
    $result = mysqli_query($db,$query);
    header('Location: editprofile.php');
    if (!$result) {
      echo "Nigga";
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

      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions) === false){
         $errors[]="Extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760){
         $errors[]='File size must be less than 10mb!';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/profile/" . $file_name);
         $link = ("images/profile/" . $file_name);
         $query = "UPDATE `user` SET `image` = '" . $link . "' WHERE `user`.`id` = '" . $user_id . "';";
         $result = mysqli_query($db, $query);
         if (!$result) {
           echo "Error!";
         }
         header('Location: editprofile.php');
      }
   }
$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $user_id . " AND unread = 'yes' "));


?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title><?php echo $user['firstname']; ?> - Edit User</title>
    <?php styles(); ?>
    <link rel="stylesheet" type="text/css" href="css/global.css">
    <style type="text/css"></style>
   
</head> 
<body>
<nav class="navbar navbar-custom navbar-fixed-top one-page" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                    <input type="text" id="search" class="search-query" placeholder="Search for..." autocomplete="off">
                    <ul id="results"></ul>
                <a class="navbar-brand" href="home.php">Title</a>  
            </div>
            <div class="collapse navbar-collapse" id="custom-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation" class="active"><a href="home.php">Home <span class="badge">42</span></a></li>
                    <li role="presentation"><a href="#" data-toggle="modal" data-target="#messages">Messages <span class="badge"><?php print_r($pmcount[0]); ?></span></a></li>
                    <li role="presentation"><a href="profile.php"><?php echo $user['fullname']; ?></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-profile-cover">
<div class="container container-low">
<div class="col-md-4">
    <img class='img-thumbnail profile-pic' src='<?php echo($user['image'])?>'/>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
        <label for="image">Update Profile Picture</label>
        <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/jpg">
        <p class="help-block">Max size: 10mb</p>
        <p class="help-block color-red"><?php 
        if(isset($_FILES['image']))
        {
          if (!empty($errors)) {
           echo '<div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <b>Error! </b>'; 
                 print_r($errors[0]);
                echo '</div>';
           
          }
        }
          ?>  </p>
      </div>
      <button type="submit" class="btn btn-default">Update Profile Picture</button>
    </form>
</div>

<div class="col-md-8">
    <form action="" method="POST" id="form">
      <section class="content bgcolor-7">
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="text" name="f_name" id="f_name" placeholder="First Name" <?php echo 'value="' . $user['firstname'] . '"'; ?>>
          <label class="input__label input__label--nariko" for="input-20">
            <span class="input__label-content input__label-content--nariko">First name</span>
          </label>
        </span>
        <span class="input input--nariko">
          <input class="input__field input__field--nariko"  name="l_name" id="l_name" placeholder="Last Name" <?php echo 'value="' . $user['lastname'] . '"'; ?>>
          <label class="input__label input__label--nariko" for="input-21">
            <span class="input__label-content input__label-content--nariko">Last name</span>
          </label>
        </span>
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="text" name="fullname" id="fullname" placeholder="Fullname" <?php echo 'value="' . $user['fullname'] . '"'; ?> disabled/>
          <label class="input__label input__label--nariko" for="input-22">
            <span class="input__label-content input__label-content--nariko">Fullname</span>
          </label>
        </span>
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="text" name="username" id="username" placeholder="Username" <?php echo 'value="' . $user['username'] . '"'; ?>/>
          <label class="input__label input__label--nariko" for="input-22">
            <span class="input__label-content input__label-content--nariko">Username</span>
          </label>
        </span>
        
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="email" name="email" id="email" placeholder="Email" <?php echo 'value="' . $user['email'] . '"'; ?>/>
          <label class="input__label input__label--nariko" for="input-22">
            <span class="input__label-content input__label-content--nariko">Email</span>
          </label>
        </span>

        <!-- Change Email -->
        <div id="change_email" class="collapse">
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="email" name="new_email" id="new_email" placeholder="Your New Email"/>
          <label class="input__label input__label--nariko" for="input-22">
            <span class="input__label-content input__label-content--nariko">Enter Your New Email</span>
          </label>
        </span>
      </div>
      
        <span class="input input--nariko">
          <input class="input__field input__field--nariko" type="text" maxlength="70" name="about" id="email" placeholder="About your self" <?php echo 'value="' . $user['about'] . '"'; ?>/>
          <label class="input__label input__label--nariko" for="input-22">
            <span class="input__label-content input__label-content--nariko">About</span>
          </label>
        </span>
      </section>
      <button type="submit" class="btn btn-success">Submit </button>
    </form>
    <!-- Change Email -->
    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#change_email">Change Email</button>
  
</div>
           <div class="modal fade" id="modal-password" onkeypress="return runScript(p)" tabindex="-1" role="dialog" aria-labelledby="modal-password-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h3 class="modal-title" id="modal-password-label">Change Password!</h3>
                        <p>Enter your new password</p>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="" method="post" class="registration-form">
                        <div id="html_element"></div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="new_password" placeholder="New Password..." class="form-username form-control" id="password">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="new_password2" placeholder="New Password Again..." class="form-username form-control" id="password2">
                            </div>
                            <button id="submit" value="getResponse" class="btn">Change!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


 </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    <script type="text/javascript" src="scripts/input.js"></script>
    
 </html>