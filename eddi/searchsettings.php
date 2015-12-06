<?php
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}
$teljari = 0;

// Define Output HTML Formating
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
// $html .= '<img width="30" height="30" src="image">';
$html .= '<h3>nameString</h3>';
$html .= '<h4>functionString</h4>';
$html .= '</a>';
$html .= '</li>';

$search_string = strip_tags($_POST['query']);        
$search_string = mysqli_real_escape_string($db, $search_string);

if (strlen($search_string) >= 1) {
	$query = 'SELECT * FROM user WHERE fullname LIKE "%' . $search_string . '%" OR username LIKE "%' . $search_string . '%"';
	$result = $db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	if (isset($result_array)) {	
		foreach ($result_array as $result) {

			// $display_img = preg_replace("/" . "/i", "<b class='highlight'>" . "</b>", $result['image']);
			$display_name = preg_replace("/" . $search_string . "/i", "<b class='highlight'>" . $search_string . "</b>", $result['fullname']);
			$display_username = preg_replace("/" . $search_string . "/i", "<b class='highlight'>" . $search_string . "</b>", $result['username']);
			$display_url = 'http://lokaverkefni.cf/profile.php?id=' . urlencode($result['id']);

			// Insert Name
			$output = str_replace('nameString', $display_name, $html);
			// Insert Function
			$output = str_replace('functionString', $display_username, $output);
			// Insert URL
			$output = str_replace('urlString', $display_url, $output);
			// Insert Pic
			// $output = str_replace('image', $display_img, $output);
			// Output
			echo($output);
			$teljari++;
			if ($teljari >= 7) {

				break;
			}
		}
	}else{
		// Birtir No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('nameString', '<b>No Results Found.</b>', $output);
		$output = str_replace('functionString', '', $output);
		// Output
		echo($output);
	}
}
?>