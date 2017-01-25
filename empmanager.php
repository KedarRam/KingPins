<!--
    Name: empmanager.php 
    Author: Kedar Ram
    Date: 2016-12-30

    Purpose: Menu to manage employee

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Employee Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

if(isset($_POST['createEmpBtn'])){
?>
     <script> location.replace("createemp.php"); </script>
<?php
}elseif(isset($_POST['searchEmpBtn'])){
    //redirectTo("searchemp"); 
?>
     <script> location.replace("searchemp.php"); </script>
<?php
}elseif(isset($_POST['listEmpBtn'])){
    //redirectTo("listallemp"); 
?>
    <script> location.replace("listallemp.php"); </script>
<?php
}

    //security
    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;

?>

<div class="container">
<section class="col col-lg-7">
<h2>Employee Manager</h2><hr>
<div>
    <?php if(isset($result)) echo $result; ?>
</div>
  <div class="clearfix"></div>

  <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <form method="post" action="">
  <div class="form-group">

 <button type="submit" name="createEmpBtn" class="btn btn-primary btn-block active">Create Employee</button>
 
 <button type="submit" name="searchEmpBtn" class="btn btn-primary btn-block active">Search, Edit Employee</button>

 <button type="submit" name="listEmpBtn" class="btn btn-primary btn-block active">List Employees</button>

 </form>
  <?php endif ?>
  </section>
  <br>
  <p><a href="mgrmain.php">Back</a></p>
  </div>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>