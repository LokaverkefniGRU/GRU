<?php
include 'movie.php';

function connect(){

	$servername = "10.200.10.24";
	$db_name = "1304982059_gru";
	$username = "1304982059";
	$password = "mypassword";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $db_name);	

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	return $conn;
}





function getMovies() {
	$conn = connect();


		$sql = "SELECT * FROM movies";
		$result = $conn->query($sql);
		$movies = [];
		  
		  
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$movie = new Movie($row["MoviesName"]);
		  		array_push($movies, $movie);
		    }
		} else {
		    echo "0 results";
		}
		$conn->close();

		return $movies;


};

function getMovie($id) {
	$movie = new Movie("Stepbrothers");
	$movie->id = $id;
	return $movie;
}
?>