<?php 
include 'include/config.php';
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.php'; 
$max_file_size = 300000; // Leyfð stærð í bætum
?>

<html lang="en"> 
    <head> 
    	<title><?php echo($title['global']); ?></title>
        <meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
		<link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
		<link rel="stylesheet" type="text/css" href="http://meyerweb.com/eric/tools/css/reset/reset.css">
		<link rel="stylesheet" type="text/css" href="http://tsuts.tskoli.is/2t/0712982139/gru/css/style.css">
		<link rel="stylesheet" type="text/css" href="http://tsuts.tskoli.is/2t/0712982139/gru/css/animation.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

		
    </head> 
    <style type="text/css">

   body {
    background-image: url(http://thepaperwall.com/wallpapers/food_drink/big/big_6111e8db3f492e9b08c22c74653de72f5e753599.jpg);
   

    }
    </style>
    <body>

<div class="main-nav">
                    <nav class="navbar navbar-default navbar-fixed-top navbar-home" role="navigation">
        
            <div class="container">

                <div class="navbar-header">
                    <a class="navbar-brand pull-left" href="/"><img src="img/TM_Logo1.png" alt="Todaymade" /></a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> 

                <!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Our Work</a>
                            <ul class="dropdown-menu">
                                <li><a href="/approach">Approach</a></li>
                                <li><a href="/case-studies">Case Studies</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About Us</a>
                            <ul class="dropdown-menu">
                                <li><a href="/mission">Mission</a></li>
                                <li><a href="/team">Team</a></li>
                                <li><a href="/careers">Careers</a></li>
                            </ul>
                        </li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <li class="dropdown">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Sign Up</a></a>
                        	<ul class="dropdown-menu">
                                <form>
                                	<label>Username: </label><input type="text">
                                	<label>Password: </label><input type="password">
                                	<input type="submit">
                                </form>
                            </ul>

                        	<!-- <a href="#"><i class="fa fa-user"></i> Sign Up</a> -->
                        </li>
			        	<li><a href="#"><i class="fa fa-sign-in"></i> Login</a></li>
                    </ul>
                                    </div><!-- /.navbar-collapse -->

            </div> <!--/.container-fluid -->
        </nav>
    </div> <!-- /.main-nav -->



<!-- 
    <form id="Upload" class="" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
    	<h2>Register:</h2>
		<label>Username:</label>
			<input type="text" class="form-control" name="reg_username" >
		<label>Password:</label>
			<input type="password" class="form-control" name="reg_password" >
		<label>Name:</label>
			<input type="text" class="form-control" name="reg_name" >
		<label>Email:</label>
			<input type="email" class="form-control" name="reg_email" >
		<label>Simi:</label>
			<input type="text" class="form-control" name="reg_simanumer" >
		<label>Birthday</label>
			<select class="form-control" aria-label="Month" name="birthday_month" id="month" title="Month"><option value="0" selected="1">Month</option><option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option><option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option><option value="7">Jul</option><option value="8">Aug</option><option value="9">Sep</option><option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option></select>
			<select class="form-control" aria-label="Day" name="birthday_day" id="day" title="Day"><option value="0" selected="1">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>
			<select class="form-control" aria-label="Year" name="birthday_year" id="year" title="Year"><option value="0" selected="1">Year</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option></select>
		<label>Sex</label>
			<input type="radio" class="form-control" name="sex_radio"> Male
			<input type="radio" class="form-control" name="sex_radio"> Female (But Still Male ;);) )
			<br>
		<label>Mynd:</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?> "data-filename-placement="inside">
			<input id="file" type="file" name="file" data-filename-placement="inside" class="btn btn-default btn-sm" > 
		<div class="bil"></div>
			<input id="submit" type="submit" name="submit" value="Submit" class="btn btn-danger btn-lg" />
    </form> -->
    </body> 
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
</html> 