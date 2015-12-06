<?php 
include 'include/config.php';
session_start();
if (!isset($_SESSION['id'])){
  header('Location: index.php');
}?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php styles(); ?>

  <style type="text/css">
  /* custom inclusion of right, left and below tabs */

.tabs-below > .nav-tabs,
.tabs-right > .nav-tabs,
.tabs-left > .nav-tabs {
  border-bottom: 0;
}

.tab-content > .tab-pane,
.pill-content > .pill-pane {
  display: none;
}

.tab-content > .active,
.pill-content > .active {
  display: block;
}

.tabs-below > .nav-tabs {
  border-top: 1px solid #ddd;
}

.tabs-below > .nav-tabs > li {
  margin-top: -1px;
  margin-bottom: 0;
}

.tabs-below > .nav-tabs > li > a {
  -webkit-border-radius: 0 0 4px 4px;
     -moz-border-radius: 0 0 4px 4px;
          border-radius: 0 0 4px 4px;
}

.tabs-below > .nav-tabs > li > a:hover,
.tabs-below > .nav-tabs > li > a:focus {
  border-top-color: #ddd;
  border-bottom-color: transparent;
}

.tabs-below > .nav-tabs > .active > a,
.tabs-below > .nav-tabs > .active > a:hover,
.tabs-below > .nav-tabs > .active > a:focus {
  border-color: transparent #ddd #ddd #ddd;
}

.tabs-left > .nav-tabs > li,
.tabs-right > .nav-tabs > li {
  float: none;
}

.tabs-left > .nav-tabs > li > a,
.tabs-right > .nav-tabs > li > a {
  min-width: 74px;
  margin-right: 0;
  margin-bottom: 3px;
}

.tabs-left > .nav-tabs {
  float: left;
  margin-right: 19px;
  border-right: 1px solid #ddd;
}

.tabs-left > .nav-tabs > li > a {
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
     -moz-border-radius: 4px 0 0 4px;
          border-radius: 4px 0 0 4px;
}

.tabs-left > .nav-tabs > li > a:hover,
.tabs-left > .nav-tabs > li > a:focus {
  border-color: #eeeeee #dddddd #eeeeee #eeeeee;
}

.tabs-left > .nav-tabs .active > a,
.tabs-left > .nav-tabs .active > a:hover,
.tabs-left > .nav-tabs .active > a:focus {
  border-color: #ddd transparent #ddd #ddd;
  *border-right-color: #ffffff;
}

.tabs-right > .nav-tabs {
  float: right;
  margin-left: 19px;
  border-left: 1px solid #ddd;
}

.tabs-right > .nav-tabs > li > a {
  margin-left: -1px;
  -webkit-border-radius: 0 4px 4px 0;
     -moz-border-radius: 0 4px 4px 0;
          border-radius: 0 4px 4px 0;
}

.tabs-right > .nav-tabs > li > a:hover,
.tabs-right > .nav-tabs > li > a:focus {
  border-color: #eeeeee #eeeeee #eeeeee #dddddd;
}

.tabs-right > .nav-tabs .active > a,
.tabs-right > .nav-tabs .active > a:hover,
.tabs-right > .nav-tabs .active > a:focus {
  border-color: #ddd #ddd #ddd transparent;
  *border-left-color: #ffffff;
}
</style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h3>Tabs</h3>
        <!-- tabs -->
        <div class="tabbable">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#one" data-toggle="tab">One</a></li>
            <li><a href="#two" data-toggle="tab">Two</a></li>
            <li><a href="#twee" data-toggle="tab">Twee</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="one">Lorem ipsum dolor sit amet, charetra varius quam sit amet vulputate. 
            Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero.</div>
            <div class="tab-pane" id="two">Secondo sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
            Aliquam in felis sit amet augue.</div>
            <div class="tab-pane" id="twee">Thirdamuno, ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
            Quisque mauris augue, molestie tincidunt condimentum vitae.</div>
           </div>
        </div>
        <!-- /tabs -->

    </div>
    <div class="col-md-6"><h3>Tabs -below</h3>
    
            
      <!-- tabs bottom -->
      <div class="tabbable tabs-below">
        <div class="tab-content">
         <div class="tab-pane active" id="one_">Lorem ipsum dolor sit amet, charetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero.</div>
         <div class="tab-pane" id="two_">Secondo sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
         Aliquam in felis sit amet augue.</div>
         <div class="tab-pane" id="twee_">Thirdamuno, ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae. </div>
        </div>
        <ul class="nav nav-tabs">
          <li><a href="#one_" data-toggle="tab">One</a></li>
          <li><a href="#two_" data-toggle="tab">Two</a></li>
          <li><a href="#twee_" data-toggle="tab">Twee</a></li>
        </ul>
      </div>
      <!-- /tabs -->
      
    
    </div>
  </div><!-- /row -->
  <div class="row">
    
    <div class="col-md-6"><h3>Tabs -left</h3>
            
      <!-- tabs left -->
      <div class="tabbable tabs-left">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#a" data-toggle="tab">One</a></li>
          <li><a href="#b" data-toggle="tab">Two</a></li>
          <li><a href="#c" data-toggle="tab">Twee</a></li>
        </ul>
        <div class="tab-content">
         <div class="tab-pane active" id="a">Lorem ipsum dolor sit amet, charetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero.</div>
         
         <div class="tab-pane" id="b">Secondo sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
         Aliquam in felis sit amet augue.</div>
         <div class="tab-pane" id="c">Thirdamuno, ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae. </div>
        </div>
      </div>
      <!-- /tabs -->
      
    </div>
    
     <div class="col-md-6"><h3>Tabs -right</h3>
            
      <!-- tabs right -->
      <div class="tabbable tabs-right">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#1" data-toggle="tab">One</a></li>
          <li><a href="#2" data-toggle="tab">Two</a></li>
          <li><a href="#3" data-toggle="tab">Twee</a></li>
        </ul>
        <div class="tab-content">
         <div class="tab-pane active" id="1">Lorem ipsum dolor sit amet, charetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero.</div>
         <div class="tab-pane" id="2">Secondo sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
         Aliquam in felis sit amet augue.</div>
         <div class="tab-pane" id="3">Thirdamuno, ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
         Quisque mauris augue, molestie tincidunt condimentum vitae. </div>
        </div>
      </div>
      <!-- /tabs -->
      
    </div>
    
  </div><!-- /row -->
</div>
  
  
  

<hr>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://todaymade.com/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://todaymade.com/js/respond.min.js"></script>
    <script type="text/javascript" src="scripts/input.js"></script>
</html>



