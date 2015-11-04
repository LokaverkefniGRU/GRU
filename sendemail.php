<?php 
include 'include/config.php';

// $query = "SELECT email FROM user";
// $result = mysqli_query($db, $query);
// $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (isset($_POST['postur'])) {
	$sql = "SELECT * FROM user";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $to      = $row['email'];
        $subject = 'Please confirm your email for lokaverkefni.cd';
        $message = 'Your confirmation link: http://lokaverkefni.cf/confirmation.php?passkey=';
        $headers = 'From: no-reply@lokaverkefni.cf' . "\r\n" .
            'Reply-To: no-reply@lokaverkefni.cf' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
} else {
    echo "0 results";
}
}

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>123</title>
 </head>
 <body>
 <form action="" method="POST">
 	<input type="submit" value="submit">
 </form>

 </body>
 </html>