<!--
    Name: empschedulemgr.php 
    Author: Kedar Ram
    Date: 2017-01-10

    Purpose: Employee Schedule Menu

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Employee Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

if(isset($_POST['changeRequestBtn'])){
?>
<script> location.replace("empschedule.php"); </script>
<?php
}elseif(isset($_POST['deleteRequestBtn'])){
?>
<script> location.replace("deleterequestschedule.php"); </script>
<?php
}

?>

<div class="container">
<section class="col col-lg-7">
<h2>Schedule Manager</h2><hr>
<div>
    <?php if(isset($result)) echo $result; ?>
</div>
  <div class="clearfix"></div>

  <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <form method="post" action="">
  <div class="form-group">

 <button type="submit" name="changeRequestBtn" class="btn btn-primary btn-block active">My Schedule Change Request</button>

 <button type="submit" name="deleteRequestBtn" class="btn btn-primary btn-block active">Delete Requested Schedule Change</button>
 
 </form>
  <?php endif ?>
  </section>
  <br>
  <p><a href="empmain.php">Back</a></p>
  </div>

<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>