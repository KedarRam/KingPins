<!--
    Name: searchcust.php 
    Author: Kedar Ram
    Date: 2016-12-30

    Purpose: search customer
        uses partialSearchCust.php

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Customer Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';
include_once 'partials/partialSearchCust.php';

if(isset($_POST['updateCustExitBtn'])){

        $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    
        $custphone_disp = $_POST['custphone_disp'];
        $custfirstname_disp = strtoupper($_POST['custfirstname_disp']);
        $custlastname_disp = strtoupper($_POST['custlastname_disp']);
        $custenter_disp = strftime("%b %d, %Y", strtotime($_POST['custenter_disp']));
        $enterempid_disp = $_POST['enterempid_disp'];
        $id=$_SESSION['id'];

        
        $ret=markcustexit($custphone_disp,$id,$db);

        if ($ret){
            $result="<ul style='color: green;'>Customer exit complete for $custphone_disp!</ul>";
        }else{
            $result = "<ul style='color: red;'>Unable to exit Customer - make a note for $custphone_disp and contact manager<\ul>";
        }

    }
?>

<?php if($hide == 0): ?>
<br>
<br>
<h2>Search Customer</h2>
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> You are not authorized to view this page <a href="login.php">Login</a></p>
<?php else: ?>
<?php if(isset($result)) echo $result; ?>
<section class="col col-lg-7">
 <form action="" method="post">
  </div>
    <div class="form-group">
    <label for="custphoneField">Customer Phone Number</label>
    <input type="text" class="form-control" id="searchcustphone" name="searchcustphone" placeholder="xxx-xxx-xxxx">
  </div>
  <div class="form-group">
    <label for="usernameField">Customer First Name </label>
    <input type="text" class="form-control" id="searchcustfirstname" name="searchcustfirstname" placeholder="First Name">
 </div>
    <div class="form-group">
    <label for="usernameField">Customer Last Name</label>
    <input type="text" class="form-control" id="searchcustlastname" name="searchcustlastname" placeholder="Last Name">
  </div>
 

<div><button type="submit" name="SearchBtn" class="btn btn-primary pull-right">Search
<span class="glyphicon glyphicon-search"></span></button></div>

<div></div>
<div><a class="pull-left" href="listallcust.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>">
<span class="glyphicon glyphicon-list-alt"></span>  List All Active Customers</a></div>
</form>
</section>
 <p><a href="customer.php">Back</a></p>
</div>
</div>
<?php endif ?>
<?php endif ?>

<?php if($hide == 1): ?>
<div class="container2">
<div>
<h1>Customer Profile</h1>
<?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <?php if(isset($result)) echo $result; ?>
  <section class="col col-lg-7">
  <form method="post" action="">
  <div class="form-group">
  <label for="phoneField">Customer Phone</label>
  <input type="text" name="custphone_disp" class="form-control" id="custphoneField" value="<?php if(isset($custphone_disp)) echo $custphone_disp; ?>">
  </div>    
<div class="form-group">
  <label for="usernameField">Customer First Name</label>
  <input type="text" name="custfirstname_disp" class="form-control" id="custfirstnameField" value="<?php if(isset($custfirstname_disp)) echo $custfirstname_disp; ?>">
  </div>
  <div class="form-group">
  <label for="usernameField">Customer Last Name</label>
  <input type="text" name="custlastname_disp" class="form-control" id="custlastnameField" value="<?php if(isset($custlastname_disp)) echo $custlastname_disp; ?>">
  </div>
  <div class="form-group">
  <label for="enterdateField">Customer Enter Date Time</label>
  <input type="text" name="custenter_disp" class="form-control" id="custenterdateField" value="<?php if(isset($custenter_disp)) echo $custenter_disp; ?>">
  </div>
  <div class="form-group">
  <label for="enterempidField">Customer Entered by Employee #</label>
  <input type="text" name="enterempid_disp" class="form-control" id="enterempidField" value="<?php if(isset($enterempid_disp)) echo $enterempid_disp; ?>">
  </div>
   <button type="submit" name="updateCustExitBtn" class="btn btn-primary pull-right">Exit Customer</button>
 </form>
  <?php endif ?>

</section>


 <p><a href="searchcust.php">Back</a></p>
</div>
</div>
<?php endif ?>
<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>