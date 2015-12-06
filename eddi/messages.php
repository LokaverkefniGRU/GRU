<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}

// ?id=12313513 <-- conversion id
// style messages
// search í messages
// New messages takki
// Sjá öll ólesin skilaboð
// Birta user og myndir af sendara
// lata unread breytast í no ef það er buið að lesa skiló

$msg_sent = array();
$no_msg = array();
$print_messages = "";

if (!empty($msg_sent)) {
    print_r($msg_sent[0]);
}

$id = $_SESSION['id'];
$query = "SELECT * FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Get messages count
$pmcount = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM messages WHERE receiver = " . $user['id'] . " AND unread = 'yes' "));

$res = mysqli_query($db, 'SELECT * FROM messages WHERE receiver = ' . $user['id'] . ' AND unread=\'yes\' ORDER BY id DESC');

if (mysqli_num_rows($res) == 0){
      $no_msg[] = 'You have no messages.. Send message to someone';
    }
else
{
    while ($row = mysqli_fetch_assoc($res))
    {
        // Herna kemur allt úr message db
        $print_messages .= "msg: " . $row['msg'] . "<input type='checkbox'>Mark as read (Hafa þetta sem ajax ekki checkbox :))<br>";
    }
}

if (isset($_POST['receiver'])) {
    $msg_id = rand(0, 999999999);
    $receiver = strip_tags($_POST['receiver']);
    $msg = strip_tags($_POST['msg']);
    $receiver = mysqli_real_escape_string($db, $receiver);
    $msg = mysqli_real_escape_string($db, $msg);
    $query = "INSERT INTO `messages` (`id`, `sender`, `receiver`, `msg`, `unread`) VALUES ('$msg_id', '$id', '$receiver', '$msg', 'yes');";
    $result = mysqli_query($db, $query) or die('Nigga');
    $msg_sent[] = "Your message has been sent!";

}

?>
<html lang="en"> 
    <head> 
        <title><?php echo($title['global']) ?></title>
        <?php styles(); ?>
        <link rel="stylesheet" type="text/css" href="style/style.css"/>
        <link rel="stylesheet" type="text/css" href="style/main.css">
        <link rel="stylesheet" type="text/css" href="css/nav.css">
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
                    <li role="presentation"><a href="#">Messages <span class="badge"><?php print_r($pmcount[0]); ?></span></a></li>
                    <li role="presentation"><a href="profile.php">Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <br>
    <br>
       
       <?php  
        

        if (!empty($no_msg)) {
           print_r($no_msg[0]);
        }else{
            echo($print_messages);
        }

        ?>

        <form action="" method="POST">
            <input type="text" name="receiver" placeholder="Id user">
            <textarea name="msg"></textarea>
            <input type="submit">
        </form>
   
   
    </body>

    </style>
    <script type="text/javascript" src="https://somawebproduction.s3.amazonaws.com/assets/project-chimp/main-796ddfc4af918d82ea5db2db5600ed43.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/custom.js"></script>
    <script type="text/javascript">
        
    </script>
</html> 