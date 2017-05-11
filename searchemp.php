<!--
    Name: searchemp.php 
    Author: Kedar Ram
    Date: 2016-12-20

    Purpose: search employee

-->
<!-- common header -->
<?php
$page_title = "Welcome to KingPins FEC - Search Employees";
include_once 'headerfooter/header.php';
include_once 'partials/partialSearch.php'; //lookup an employee
include_once 'partials/partialMgreditProfile.php'; //edit an employee
?>
<!-- screen 1 - Search -->
<div class="container">
<section class="col col-lg-7">
<div>

<?php if($hide == 0): ?>
<br>
<br>
<h2>Search Employee</h2>
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> You are not authorized to view this page <a href="login.php">Login</a></p>
<?php else: ?>
<?php if(isset($result)) echo $result; ?>

 <form action="" method="post">
  <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" class="form-control" id="searchusername" name="searchusername" placeholder="Username">
  </div>
    <div class="form-group">
    <label for="firstnameField">Firstname</label>
    <input type="text" class="form-control" id="searchfirstname" name="searchfirstname" placeholder="Firstname">
  </div>
    <div class="form-group">
    <label for="usernameField">Lastname</label>
    <input type="text" class="form-control" id="searchlastname" name="searchlastname" placeholder="Lastname">
  </div>
 <div class="form-group">
    <label for="emailField">Email Address</label>
    <input type="text" class="form-control" id="searchemail" name="searchemail" placeholder="Email">
  </div>

<div><button type="submit" name="SearchBtn" class="btn btn-primary pull-right">Search
<span class="glyphicon glyphicon-search"></span></button></div>

<div></div>
<div><a class="pull-left" href="listallemp.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>">
<span class="glyphicon glyphicon-list-alt"></span>  List All Employees</a></div>

</section>
<br>
<br>
<br>
 <p><a href="empmanager.php">Back</a></p>
<?php endif ?>
</div>
</div>
<?php endif ?>
<!-- screen 2 - Edit-->
<?php if($hide == 1): ?>
<br>
<br>
<div class="container">
<div>
<h3>Edit Employee Profile</h3>
<?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <?php if(isset($result)) echo $result; ?>
  <section class="col col-lg-7">
  <form method="post" action="">
  <div class="form-group">
  <label for="empidField">Employee ID</label>
  <input type="text" name="id_disp" class="form-control" id="empidField"  readonly value="<?php if(isset($id_disp)) echo $id_disp; ?>">
  </div>
  <div class="form-group">
  <label for="usernameField">Username</label>
  <input type="text" name="username_disp" class="form-control" id="usernameField" value="<?php if(isset($username_disp)) echo $username_disp; ?>">
  </div>
  <div class="form-group">
  <label for="usernameField">First Name</label>
  <input type="text" name="firstname_disp" class="form-control" id="firstnameField" value="<?php if(isset($firstname_disp)) echo $firstname_disp; ?>">
  </div>
  <div class="form-group">
  <label for="usernameField">Last Name</label>
  <input type="text" name="lastname_disp" class="form-control" id="lastnameField" value="<?php if(isset($lastname_disp)) echo $lastname_disp; ?>">
  </div>
  <div class="form-group">
  <label for="emailField">Email Address</label>
  <input type="text" name="email_disp" class="form-control" id="emailField" value="<?php if(isset($email_disp)) echo $email_disp; ?>">
  </div>
  <div class="form-group">
  <label for="phoneField">Phone</label>
  <input type="text" name="phone_disp" class="form-control" id="phoneField" value="<?php if(isset($phone_disp)) echo $phone_disp; ?>">
  </div>
  <div class="form-group">
  <label for="defaultgroupField">Default Group</label>
  <input type="text" name="defaultgroup_disp" class="form-control" id="defaultgroupField" value="<?php if(isset($defaultgroup_disp)) echo $defaultgroup_disp; ?>">
  </div>
   <div class="form-group">
  <label for="joindateField">Join Date</label>
  <input type="text" name="joindate_disp" class="form-control" id="joindateField" value="<?php if(isset($date_joined_disp)) echo $date_joined_disp; ?>">
  </div>
  <div class="form-group">
  <label for="updatedateField">Last Update Date</label>
  <input type="text" name="updatedate_disp" class="form-control" id="updatedateField" readonly value="<?php if(isset($date_update_disp)) echo $date_update_disp; ?>">
  </div>
   <div class="form-group">
    <label for="isActiveField">Employee Active?</label>
    <label class="radio-inline"><input type="radio"  id="Yes" name="isActive_disp" value=1 <?php if($isActive_disp == 1):?> checked <?php endif ?> >Active</label>
    <label class="radio-inline"><input type="radio"  id="No" name="isActive_disp" value=0  <?php if($isActive_disp == 0):?> checked <?php endif ?>>InActive</label>
  </div>
  
  <div class="form-group">
    <label for="isManagerField">Is Employee a Manager?</label>
    <label class="radio-inline"><input type="radio" id="Yes" name="isManager_disp" value=1 <?php if($isManager_disp == 1):?> checked <?php endif ?>>Yes</label>
    <label class="radio-inline"><input type="radio" id="No" name="isManager_disp" value=0 <?php if($isManager_disp == 0):?> checked <?php endif ?>>No</label>
  </div>

 <button type="submit" name="updateEmpProfileBtn" class="btn btn-primary pull-right">Update Profile</button>
 </form>
  <?php endif ?>

</section>

 <p><a href="searchemp.php">Back</a></p>
</div>
</div>
<?php endif ?>

<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>
