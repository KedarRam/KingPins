<!-- Name: customer.php 
    Author: Kedar Ram
    Date: 2017-01-06

    Purpose: Customer Manager Menu

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Customer Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

// Redirect based in option chosen
if(isset($_POST['createCustBtn'])){
?>
     <script> location.replace("createcust.php"); </script>
<?php      //search cust
}elseif(isset($_POST['SearchCustBtn'])){
?>
    <script> location.replace("searchcust.php"); </script>
<?php    //report customer
}elseif(isset($_POST['ReportCustBtn'])){
?>    
    <script> location.replace("reportcust.php"); </script>
<?php
}
?>

<div class="container">
<section class="col col-lg-7">
<h2>Customer Manager</h2><hr>
<div>
    <?php if(isset($result)) echo $result; ?>
</div>
  <div class="clearfix"></div>

  <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <form method="post" action="">
  <div class="form-group">

 <button type="submit" name="createCustBtn" class="btn btn-primary btn-block active">Create Customer</button>
 
 <button type="submit" name="SearchCustBtn" class="btn btn-primary btn-block active">Search Customer</button>

  <button type="submit" name="ReportCustBtn" class="btn btn-primary btn-block active">Report on Customers</button>
 </form>
  <?php endif ?>
  </section>
  <br>
  <!--if manager go to mgrmain else empmain -->
  <?php if ($_SESSION['manager'] == 1): ?>
  <p><a href="mgrmain.php">Back</a></p>
  <?php else: ?>
  <p><a href="empmain.php">Back</a></p>
  <?php endif ?>
  </div>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>
