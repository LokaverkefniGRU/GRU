<?php 
    session_start();
$config['dbuser'] = "GRU_H14"; //database user
$config['dbpass'] = "bananabomba98"; //database password
$config['dbname'] = "gru_h14_gru"; //database we're connecting to
$config['dbhost'] = "tsuts.tskoli.is";

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
$db->set_charset("utf8");

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $username = strip_tags($_POST['username']);
    $username = strtolower($username);
    $username = mysqli_real_escape_string($db, $username);
    $password = strip_tags($_POST['password']);
    $password = mysqli_real_escape_string($db, $password);


    $result = mysqli_query($db, "SELECT id, username, password, salt FROM  `user` WHERE username =  '$username'");
    $array = mysqli_fetch_array($result);

    $id = $array[0];
    $dbusername = $array[1];
    $dbpassword = $array[2];
    $CryptSalt = $array[3];

    $hashed_password = crypt($password, $CryptSalt);
    if($dbusername == $username && $dbpassword == $hashed_password){
        $_SESSION['id'] = $id;
        // Fyrir ip á undan
        $query = "SELECT ip FROM user WHERE id = '" . $id . "';";
        $result = mysqli_query($db, $query);
        $iptala = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $query = "UPDATE `user` SET  `last_ip` =  '" . $iptala['ip'] . "' WHERE `id` = $id;";
        $result = mysqli_query($db, $query);

        // Fyrir ip nuna
        $query = "UPDATE `user` SET  `ip` =  '" . $ip . "' WHERE `id` = $id;";
        $result = mysqli_query($db, $query);
        header("Location: ../../home.php");
    }
 ?>