<?php include_once 'common/session.php'; ?>
<?php include_once 'common/utilities.php'; ?>

<html>
<head lang="en">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php if(isset($page_title)) echo $page_title ?></title>
<?php $hide = 0; ?>
<?php date_default_timezone_set('America/New_York'); ?>
<!-- Bootstap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
     <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
     <link href="css/custom.css" rel="stylesheet" type="text/css">
     <script src="js/bootstrap.js"></script>
     <script src="js/bootstrap.min.js"></script>
     <script src="js/custom.js"></script>

<!-- JQuery -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  
  <!-- display calendar for report usage -->
  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker();
    $('#StartTime').datetimepicker({
      datepicker : false,
      ampm: true, // FOR AM/PM FORMAT
      format : 'g:i A'
    });
    $('#EndTime').datetimepicker({
      datepicker : false,
      ampm: true, // FOR AM/PM FORMAT
      format : 'g:i A'
    });
});
</script> 

</head>
<!--navigation bar -->
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img src="logos.jpg" class="img-thumbnail" alt="logo" width="50" height="50">
          <a class="navbar-brand" href="index.php">KingPinsFEC</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav"></i>
         	<?php if(isset($_SESSION['username'])): ?>
            <li><a href="reset-password.php">Reset Password</a></li>
            
         <?php if(isset($_SESSION['manager']) && ($_SESSION['manager'] == 1)): ?>
              <li><a href="profile.php"><?php echo $_SESSION['username'] ?> - Manager Profile</a></li>
              <li><a href="empmanager.php">Employee Manager</a><li>
              <li><a href="schedulemgr.php">Schedule Manager</a></li>
              <li><a href="customer.php">Customer Manager</a></li>
            <?php else: ?>
              <li><a href="profile.php"><?php echo $_SESSION['username'] ?> - Employee Profile</a></li>
              <li><a href="empschedulemgr.php">Schedule Manager</a></li> 
              <li><a href="customer.php">Customer Manager</a></li>          
              <?php endif ?>
              <li><a href="logout.php">Logout</a></li>
			<?php else: ?>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
	    <li><a href="help.php">Readme</a></li>
            <li><a href="login.php">Login</a></li>
			<?php endif ?>
            </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>