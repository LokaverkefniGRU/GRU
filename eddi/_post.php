<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="style/recommended-styles.css">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<style type='text/css'>
		body {
    margin: 0;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 20px;
    color: #333333;
    background-color: #ffffff;
}

		li {
			margin-bottom: 4px;
			}
		textarea {
			padding: 8px 15px;
			background: #fefefe;
			-moz-border-radius: 2px;
			-webkit-border-radius: 2px;
			-o-border-radius: 2px;
			-ms-border-radius: 2px;
			-khtml-border-radius: 2px;
			border-radius: 2px;
			border: 1px solid #999;
			-moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
			-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
			-o-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
			overflow: auto;
			overflow-y: hidden;
			color:#444;
			
			height:100px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			}
		h2{
			border-bottom: 1px solid #CCC;
			margin-bottom: 5px;
			margin-top: 30px;
			}
			.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    list-style: none;
    background-color: lightblue;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.2);
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
}
li:hover{
	color: blue;
}
</style>
</head>
<body>
	<textarea id="light"></textarea>

</body>
</html>
	<script type='text/javascript' charset="UTF-8" src='http://code.jquery.com/jquery-1.8.2.js'></script>
	<script type='text/javascript' charset="UTF-8" src="scripts/mention.js"></script>
		<script type='text/javascript' charset="UTF-8" src="scripts/bootstrap-typeahead.js"></script>

<script type="text/javascript">
		$(document).ready(function(){
			
			$("#light").mention({
				users: [{
					name: 'Lindsay Made',
					username: 'LindsayM',
					image: 'http://placekitten.com/25/25'
				}, {
					name: 'Rob Dyrdek',
					username: 'robdyrdek',
					image: 'http://placekitten.com/25/24'
				}, {
					name: 'Rick Bahner',
					username: 'RickyBahner',
					image: 'http://placekitten.com/25/23'
				}, {
					name: 'Jacob Kelley',
					username: 'jakiestfu',
					image: 'http://placekitten.com/25/22'
				}, {
					name: 'John Doe',
					username: 'HackMurphy',
					image: 'http://placekitten.com/25/21'
				}, {
					name: 'Charlie Edmiston',
					username: 'charlie',
					image: 'http://placekitten.com/25/20'
				}, {
					name: 'Andrea Montoya',
					username: 'andream',
					image: 'http://placekitten.com/24/20'
				}, {
					name: 'Jenna Talbert',
					username: 'calisunshine',
					image: 'http://placekitten.com/23/20'
				}, {
					name: 'Eðvald',
					username: 'streetleague',
					image: 'http://placekitten.com/22/20'
				}, {
					name: 'Eðvald Atli',
					username: 'Loudmouthfoods',
					image: 'http://placekitten.com/21/20'
				}]
			});
});
</script>