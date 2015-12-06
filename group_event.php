<?php 
	
	$config['dbuser'] = "2410982069"; //database user
	$config['dbpass'] = "svolu2"; //database password
	$config['dbname'] = "2410982069_gru"; //database we're connecting to
	$config['dbhost'] = "tsuts.tskoli.is";
	$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
	$db->set_charset("utf8");

	$session = 1446561116;

	#sækir nöfn allra hópa sem notandi hefur stofnað(svo það sé ekki hægt að búa til marga hópa með nákvæmlega sama nafni

		$query = "SELECT name FROM hopar WHERE host_ID = $session";
		$result = mysqli_query($db, $query);
		$nofn = $result -> fetch_assoc();

      $query = "SELECT confirmed FROM user WHERE id = $session";
      $result = mysqli_query($db, $query);
      $row = $result-> fetch_assoc();
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Create</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
<!-- PRELOADER -->
<div id="preloader" style="display: none;">
<div id="status" style="display: none;"></div>
</div>
<!-- BEGINNING OF HTML -->
<div class="page">

    <div class="container">
        <div class="logreg-buttons">
            <button type="button" data-toggle="collapse" data-target="#group">Create group</button>
            <button type="button" data-toggle="collapse" data-target="#event">Create event</button>
        </div>

        <!-- GROUP -->
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div id="group" data-parent="#accordion" class="collapse login col-lg-2">
                
                    <form action="createGroup.php" method="POST" class="form-horizontal">
                        <legend>Create group</legend>
                        <span id="login_reg"></span>
                        <!-- Text input-->
                        <div class="form-group">
                            <div class="col-lg-12">
                            <input id="group_name" name="group_name" type="text" placeholder="Group name" class="form-control input-md">
                            </div>
                        </div>

                       <div class="form-group">
                           <div>
                               <button type="submit" id="groupButton" class="btn btn-primary">Submit</button>
                           </div>
                       </div>
                   </form>
               </div>
               </div>
           </div>


           <!-- EVENT -->
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div id="event" data-parent="#accordion" class="collapse login col-lg-2">
                    <form action="createEvent.php" method="POST" class="form-horizontal">
                        <legend>Create event</legend>
                        <span id="login_reg"></span>
                        <!-- Text input-->
                        <div class="form-group">
                            <div class="col-lg-12">
                            <input id="event_name" name="event_name" type="text" placeholder="Name" class="form-control input-md thing123456">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-12">
                            <input id="event_description" name="event_description" type="textarea" placeholder="Description" class="form-control input-md thing123456">
                            </div>
                        </div>

                        <!-- date -->
                        <label>Start date</label>
                        <div class="form-group">
				              <div class="col-lg-12">
				              	<!-- Dagar -->
				                <select name="start_day" class="form-control inout-sm thing123456" id="start_date">
				                    <option>Day</option>
				                    <option>1</option>
				                    <option>2</option>
				                    <option>3</option>
				                    <option>4</option>
				                    <option>5</option>
				                    <option>6</option>
				                    <option>7</option>
				                    <option>8</option>
				                    <option>9</option>
				                    <option>10</option>
				                    <option>11</option>
				                    <option>12</option>
				                    <option>13</option>
				                    <option>14</option>
				                    <option>15</option>
				                    <option>16</option>
				                    <option>17</option>
				                    <option>18</option>
				                    <option>19</option>
				                    <option>20</option>
				                    <option>21</option>
				                    <option>22</option>
				                    <option>23</option>
				                    <option>24</option>
				                    <option>25</option>
				                    <option>26</option>
				                    <option>27</option>
				                    <option>28</option>
				                    <option>29</option>
				                    <option>30</option>
				                    <option>31</option>
				                </select>
				                <!-- mánuðir -->
				                <select name="start_month" class="form-control inout-sm thing123456" id="start_month">
				                    <option>Month</option>
				                    <option>1</option>
				                    <option>2</option>
				                    <option>3</option>
				                    <option>4</option>
				                    <option>5</option>
				                    <option>6</option>
				                    <option>7</option>
				                    <option>8</option>
				                    <option>9</option>
				                    <option>10</option>
				                    <option>11</option>
				                    <option>12</option>
				                </select>
				                <!-- ár. Til 1900 -->
				                <select name="start_year" class="form-control inout-sm thing123456" id="start_year">
				                    <option>Year</option>
				                    <option value="2016">2018</option>
				                    <option value="2016">2017</option>
				                    <option value="2016">2016</option>
				                    <option value="2015">2015</option>
				                </select>
				            </div>
				        </div>
				        <!-- Time -->
				        <div class="from-group">
				        	<div class="col-lg-12">
				        		<select name="start_hh" class="form-control inout-sm thing123456" id="start_hh">
				        			<option>Hours</option>
				        			<option>00</option>
				        			<option>01</option>
				        			<option>02</option>
				        			<option>03</option>
				        			<option>04</option>
				        			<option>05</option>
				        			<option>06</option>
				        			<option>07</option>
				        			<option>08</option>
				        			<option>09</option>
				        			<option>10</option>
				        			<option>11</option>
				        			<option>12</option>
				        			<option>13</option>
				        			<option>14</option>
				        			<option>15</option>
				        			<option>16</option>
				        			<option>17</option>
				        			<option>18</option>
				        			<option>19</option>
				        			<option>20</option>
				        			<option>21</option>
				        			<option>22</option>
				        			<option>23</option>
				        		</select>
				        		<select name="start_mm" class="form-control inout-sm thing123456" id="start_mm">
				        			<option>Minutes</option>
				        			<option>00</option>
				        			<option>01</option>
				        			<option>02</option>
				        			<option>03</option>
				        			<option>04</option>
				        			<option>05</option>
				        			<option>06</option>
				        			<option>07</option>
				        			<option>08</option>
				        			<option>09</option>
				        			<option>10</option>
				        			<option>11</option>
				        			<option>12</option>
				        			<option>13</option>
				        			<option>14</option>
				        			<option>15</option>
				        			<option>16</option>
				        			<option>17</option>
				        			<option>18</option>
				        			<option>19</option>
				        			<option>20</option>
				        			<option>21</option>
				        			<option>22</option>
				        			<option>23</option>
				        			<option>24</option>
				        			<option>25</option>
				        			<option>26</option>
				        			<option>27</option>
				        			<option>28</option>
				        			<option>29</option>
				        			<option>30</option>
				        			<option>31</option>
				        			<option>32</option>
				        			<option>33</option>
				        			<option>34</option>
				        			<option>35</option>
				        			<option>35</option>
				        			<option>36</option>
				        			<option>37</option>
				        			<option>38</option>
				        			<option>39</option>
				        			<option>40</option>
				        			<option>41</option>
				        			<option>42</option>
				        			<option>43</option>
				        			<option>44</option>
				        			<option>45</option>
				        			<option>46</option>
				        			<option>47</option>
				        			<option>48</option>
				        			<option>49</option>
				        			<option>50</option>
				        			<option>51</option>
				        			<option>52</option>
				        			<option>53</option>
				        			<option>54</option>
				        			<option>55</option>
				        			<option>56</option>
				        			<option>57</option>
				        			<option>58</option>
				        			<option>59</option>
				        		</select>
				        	</div>
				        </div>

                        <!-- Time to -->
                        <label>End date</label>
                        <div class="form-group">
				              <div class="col-lg-12">
				              	<!-- Dagar -->
				                <select name="end_date" class="form-control inout-sm thing123456" id="end_date">
				                    <option>Day</option>
				                    <option>1</option>
				                    <option>2</option>
				                    <option>3</option>
				                    <option>4</option>
				                    <option>5</option>
				                    <option>6</option>
				                    <option>7</option>
				                    <option>8</option>
				                    <option>9</option>
				                    <option>10</option>
				                    <option>11</option>
				                    <option>12</option>
				                    <option>13</option>
				                    <option>14</option>
				                    <option>15</option>
				                    <option>16</option>
				                    <option>17</option>
				                    <option>18</option>
				                    <option>19</option>
				                    <option>20</option>
				                    <option>21</option>
				                    <option>22</option>
				                    <option>23</option>
				                    <option>24</option>
				                    <option>25</option>
				                    <option>26</option>
				                    <option>27</option>
				                    <option>28</option>
				                    <option>29</option>
				                    <option>30</option>
				                    <option>31</option>
				                </select>
				                <!-- mánuðir -->
				                <select name="end_month" class="form-control inout-sm thing123456" id="end_month">
				                    <option>Month</option>
				                    <option>1</option>
				                    <option>2</option>
				                    <option>3</option>
				                    <option>4</option>
				                    <option>5</option>
				                    <option>6</option>
				                    <option>7</option>
				                    <option>8</option>
				                    <option>9</option>
				                    <option>10</option>
				                    <option>11</option>
				                    <option>12</option>
				                </select>
				                <!-- ár. Til 1900 -->
				                <select name="end_year" class="form-control inout-sm thing123456" id="end_year">
				                    <option>Year</option>
				                    <option value="2016">2018</option>
				                    <option value="2016">2017</option>
				                    <option value="2016">2016</option>
				                    <option value="2015">2015</option>
				                </select>
				            </div>
				        </div>

				        <!-- Time -->
				        <div class="from-group">
				        	<div class="col-lg-12">
				        		<select name="end_hh" class="form-control inout-sm thing123456" id="end_hh">
				        			<option>Hours</option>
				        			<option>00</option>
				        			<option>01</option>
				        			<option>02</option>
				        			<option>03</option>
				        			<option>04</option>
				        			<option>05</option>
				        			<option>06</option>
				        			<option>07</option>
				        			<option>08</option>
				        			<option>09</option>
				        			<option>10</option>
				        			<option>11</option>
				        			<option>12</option>
				        			<option>13</option>
				        			<option>14</option>
				        			<option>15</option>
				        			<option>16</option>
				        			<option>17</option>
				        			<option>18</option>
				        			<option>19</option>
				        			<option>20</option>
				        			<option>21</option>
				        			<option>22</option>
				        			<option>23</option>
				        		</select>
				        		<select name="end_mm" class="form-control inout-sm thing123456" style="margin-bottom: 20px;" id="end_mm">
				        			<option>Minutes</option>
				        			<option>00</option>
				        			<option>01</option>
				        			<option>02</option>
				        			<option>03</option>
				        			<option>04</option>
				        			<option>05</option>
				        			<option>06</option>
				        			<option>07</option>
				        			<option>08</option>
				        			<option>09</option>
				        			<option>10</option>
				        			<option>11</option>
				        			<option>12</option>
				        			<option>13</option>
				        			<option>14</option>
				        			<option>15</option>
				        			<option>16</option>
				        			<option>17</option>
				        			<option>18</option>
				        			<option>19</option>
				        			<option>20</option>
				        			<option>21</option>
				        			<option>22</option>
				        			<option>23</option>
				        			<option>24</option>
				        			<option>25</option>
				        			<option>26</option>
				        			<option>27</option>
				        			<option>28</option>
				        			<option>29</option>
				        			<option>30</option>
				        			<option>31</option>
				        			<option>32</option>
				        			<option>33</option>
				        			<option>34</option>
				        			<option>35</option>
				        			<option>35</option>
				        			<option>36</option>
				        			<option>37</option>
				        			<option>38</option>
				        			<option>39</option>
				        			<option>40</option>
				        			<option>41</option>
				        			<option>42</option>
				        			<option>43</option>
				        			<option>44</option>
				        			<option>45</option>
				        			<option>46</option>
				        			<option>47</option>
				        			<option>48</option>
				        			<option>49</option>
				        			<option>50</option>
				        			<option>51</option>
				        			<option>52</option>
				        			<option>53</option>
				        			<option>54</option>
				        			<option>55</option>
				        			<option>56</option>
				        			<option>57</option>
				        			<option>58</option>
				        			<option>59</option>
				        		</select>
				        	</div>
				        </div>
				        <br>
                        <div class="form-group">
                            <div class="col-lg-12">
                            <input name="location" id="event_location" name="" type="text" placeholder="Location" class="form-control input-md thing123456">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-12">
                            <label>Make event private</label><input id="event_private" name="private" type="checkbox" class="form-control input-sm">
                            </div>
                        </div>

                       <div class="form-group">
                           <div class="">
                               <button type="submit" id="eventButton" class="btn btn-primary">Submit</button>
                           </div>
                       </div>
                   </form>

               </div>
               </div>
           </div>
    </div>
</div>
<h2 id="DoneDate"></h2>
<h2 id="DoneRest"></h2>
<h2 id="Done"></h2>
</body>
</html>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){


	var done = false;/*var sem segir til um hvort formið sé tilbúið til að vera submittað*/
	var doneDate = false; /*var sem segir til um hvort dateid se rett og ma gera*/
	var doneRest = false;/*var sem segir til um hvort restin se klar. Nafn, description, location*/

	var groupButton = document.getElementById("groupButton");
	var eventButton = document.getElementById('eventButton');
	eventButton.disabled=true;
	groupButton.disabled = true;

	var endDate = document.getElementById('end_date');
	var endMonth = document.getElementById('end_month');
	var endYear = document.getElementById('end_year');
	var end_hh = document.getElementById('end_hh');
	var end_mm = document.getElementById('end_mm');

	var startDate = document.getElementById('start_date');
	var startMonth = document.getElementById('start_month');
	var startYear = document.getElementById('start_year');
	var start_hh = document.getElementById('start_hh');
	var start_mm = document.getElementById('start_mm');

	var name = document.getElementById('event_name');
	var description = document.getElementById('event_description');
	var location = document.getElementById('event_location');

	endDate.disabled=true; endMonth.disabled=true; endYear.disabled=true; end_hh.disabled=true; end_mm.disabled=true; 

		/*ef einhvad af start dotinu er ekki buid ad breyta er ekki haegt ad breyta thvi*/
		function thing(){

		endDate = document.getElementById('end_date');
		endMonth = document.getElementById('end_month');
		endYear = document.getElementById('end_year');
		end_hh = document.getElementById('end_hh');
		end_mm = document.getElementById('end_mm');

		startDate = document.getElementById('start_date');
		startMonth = document.getElementById('start_month');
		startYear = document.getElementById('start_year');
		start_hh = document.getElementById('start_hh');
		start_mm = document.getElementById('start_mm');

		var name = document.getElementById('event_name');
		var description = document.getElementById('event_description');
		var location = document.getElementById('event_location');

		if (startDate.value === 'Day' ||startMonth.value === 'Month' || startYear.value === 'Year' || start_hh.value === 'Hours' || start_mm.value === 'Minutes') {
			endDate.disabled=true; endMonth.disabled=true; endYear.disabled=true; end_hh.disabled=true; end_mm.disabled=true; 
		}
		else{
			endDate.disabled=false;
			endMonth.disabled=false;
			endYear.disabled=false;
			end_hh.disabled=false;
			end_mm.disabled=false;
		};

		var start = new Date(startDate.value + "-" + startMonth.value + "-" + startYear.value + " " + start_hh.value + ":" + start_mm.value + ":00");
		var end = new Date(endDate.value + "-" + endMonth.value + "-" + endYear.value + " " + end_hh.value + ":" + end_mm.value + ":00");

		if (end > start) {
			doneDate = true;
		}
		else if (end == start) {
			if (end_hh.value>start_hh.value) {};
		}
		else{
			doneDate = false;
		};

		if (name.value === "") {
			doneRest = false;
		}
		else if (description.value === "") {
			doneRest = false;
		}
		else if (location.value === "") {
			doneRest = false;
		}
		else{
			doneRest = true;
		};

		if (doneDate === true && doneRest === true) {
			done=true;
		};

		if (done === true) {
			eventButton.disabled = false;
		};
	};
	$(".thing123456").change(function() {thing();});
	$(".thing123456").keydown(function() {thing();});
	$("#group_name").keydown(function(){
		var groupName = document.getElementById("group_name");
		if (groupName.value==="") {
			groupButton.disabled = true;
		}
		else{
			groupButton.disabled = false;
		};
	});
	});
</script>