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
$sql = "SELECT * FROM hopar WHERE id = " . $group_id    ;
$result = mysqli_query($db, $sql);
$row = $result -> fetch_assoc();
$name = $row['name'];
$host_ID = $row['host_ID'];

$sql = "SELECT stada FROM hopar_join WHERE user_ID = $user_id";
$result = mysqli_query($db, $sql);
$row = $result -> fetch_assoc();
$user_status = $row['stada'];

#admin levels
#1 = host   Getur kickad admin og user. Getur addad user og gert admin. kanski: Tekid ut post sem er i hopnum eftir hvern sem er
#2 = Admin  Getir kickad og addad userum
#3 = User   Getur bara postad
if ($user_id == $host_ID) {
    $admin_level = 1;
}
else{
    if ($user_status == 5) {
        $admin_level = 2;
    }
    else{
        $admin_level = 3;
    }
}
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
<?php
    if ($admin_level == 1 || $admin_level == 2) {
        #all members
        echo "<h3>All members</h3>";
        $sql = "SELECT user_ID, stada FROM hopar_join WHERE hopar_ID = $group_id";
        $result = mysqli_query($db, $sql);
        while ($row = $result -> fetch_assoc()) {
            $sql = "SELECT id, fullname, image FROM user WHERE id = " . $row['user_ID'];
            $outcome = mysqli_query($db, $sql);
            $post = $outcome -> fetch_assoc();

            $sql = "SELECT name FROM hopar_stada WHERE ID = " . $row['stada'];
            $outcome = mysqli_query($db, $sql);
            $status = $outcome -> fetch_assoc();

            if ($row['user_ID'] == $host_ID) {
                $stada = "Host";
            }
            elseif ($row['stada'] == 5) {
                $stada = "Admin";
            }
            else{
                $stada = "User";
            }
            echo "<img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $post['image'] . "'</img>";   
            echo("<div class='well col-lg-12 col-md-12 col-sm-12><a href='profile.php?id=" . $post['id'] . "'><h5 style='color:#000'>" . $post['fullname'] . '</h5> ' . $stada . "</a>");
            echo "<br><br>";
            if ($admin_level == 1) {
                if ($stada == "Admin") {
                    echo "<a href='edit_user.php?user_id=" . $row['user_ID'] . "&thing_id=" . $group_id . "&action=kick&type=1'>Kick</a>";
                }
                elseif ($stada == "Host") {
                    echo "<a href='delete.php?thing_id=" . $group_id . "&type=1'>Delete group</a>";
                }
                else{
                    echo "<a href='edit_user.php?user_id=" . $row['user_ID'] . "&thing_id=" . $group_id . "&action=kick&type=1'>Kick</a>";
                    echo "<br><br>";
                    echo "<a href='edit_user.php?user_id=" . $row['user_ID'] . "&thing_id=" . $group_id . "&action=make_admin&type=1'>Make admin</a>";
                }
            }
            echo "</div>";
            }
        

        

        $inGroup = array();
        $allowed = array();

        #velja alla i hopnum
        $sql = "SELECT user_ID FROM hopar_join WHERE hopar_ID = $group_id";
        $result = mysqli_query($db, $sql);
        while ($row = $result -> fetch_assoc()) {
            array_push($inGroup, $row['user_ID']);
        }
        #velja alla followera notandans
        $sql = "SELECT receiver_ID FROM friend_request WHERE sender_ID = $user_id";
        $result = mysqli_query($db, $sql);

        while ($row = $result -> fetch_assoc()) {
            if (!(in_array($row['receiver_ID'], $inGroup))) {
                array_push($allowed, $row['receiver_ID']);
            }
        }
        echo "<br><h3>Add members</h3><br>";
        for ($i=0; $i < count($allowed); $i++) { 
            $sql = "SELECT id, fullname, image FROM user WHERE id = ". $allowed[$i];
            $result = mysqli_query($db, $sql);
            $row = $result -> fetch_assoc();

            echo "<img class='profilepic' style='height: 40px; width: 40px; float: left;' src='" . $row['image'] . "'</img>";   
            echo("<div class='well col-lg-12 col-md-12 col-sm-12><a href='profile.php?id=" . $row['id'] . "'><h5 style='color:#000'>" . $row['fullname'] . "</h5></a>");
            echo "<br><a href='addTo.php?user_id=" . $row['id'] . "&thing_id=" . $group_id . "&type=1'>Add to group</a></div>";
            
        }
    }
        else{
            header("Location: group.php?id=$group_id");
        }
        


    
    
 ?>
</div>
<a href="addTo.php?user_id=$row['id']&thing_id=$group_id&type=1"></a>
 </body>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
 <script src="scripts/bootstrap.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
  
 </html>