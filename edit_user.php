<?php 
include 'include/config.php';

$thing_id = $_GET['thing_id'];
$type = $_GET['type'];
$user = $_GET['user_id'];
$action = $_GET['action'];

if ($type == 1) {#group
    if ($action == "make_admin") {
        #UPDATE `hopar_join` SET `stada`= 5 WHERE user_ID = $user
        $sql = "UPDATE `hopar_join` SET `stada`= 5 WHERE user_ID = $user";
        $result = mysqli_query($db, $sql);
    }

    elseif ($action == "kick") {
        $sql = "DELETE FROM `hopar_join` WHERE user_ID = $user";
        $result = mysqli_query($db, $sql);  
    }

    if (!$result) {
            echo $sql;
        }
        else{
            header("Location:group_members.php?id=$thing_id");
        }

}
elseif ($type == 0) {#event
    if ($action == "make_admin") {
        $sql = "UPDATE event_invite SET status= 4 WHERE receiver_ID = $user";
        $result = mysqli_query($db, $sql);
    }

    elseif ($action == "kick") {
        $sql = "DELETE FROM event_invite WHERE receiver_ID = $user";
        $result = mysqli_query($db, $sql);  
    }

    if (!$result) {
            echo $sql;
        }
        else{
            header("Location:event_members.php?id=$thing_id");
        }
}
else{
    header("Location:index.php");
}
?>