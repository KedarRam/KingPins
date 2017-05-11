<!--
    Name: profie.php 
    Author: Kedar Ram
    Date: 2016-12-10

    Purpose: display profile, 
    uses partialProfile.php for processing

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Edit Profile";
include_once 'headerfooter/header.php';
include_once 'partials/partialProfile.php';
?>

<div class="container">
<section class="col col-lg-7">
 <h2>Edit Profile</h2><hr>
 <div>
    <?php if(isset($result)) echo $result; ?>
 </div>
 <div class="clearfix"></div>

  <?php if(!isset($_SESSION['username'])): ?>
    <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
    <form method="post" action="">
    <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" name="username" class="form-control" id="usernameField" value="<?php if(isset($username)) echo $username; ?>"/>
    </div>
    <div class="form-group">
    <label for="firstnameField">First Name</label>
    <input type="text" name="firstname" class="form-control" id="firstnameField" readonly value="<?php if(isset($firstname)) echo $firstname; ?>"/>
    </div>
    <div class="form-group">
    <label for="lastnameField">Last Name</label>
    <input type="text" name="lastname" class="form-control" id="lastnameField" readonly value="<?php if(isset($lastname)) echo $lastname; ?>"/>
    </div>
    <div class="form-group">
    <label for="emailField">Email Address</label>
    <input type="text" name="email" class="form-control" id="emailField" value="<?php if(isset($email)) echo $email; ?>"/>
    </div>
    <div class="form-group">
    <label for="phoneField">Phone</label>
    <input type="text" name="phone" class="form-control" id="phoneField" value="<?php if(isset($phone)) echo $phone; ?>"/>
    </div>
    <div class="form-group">
    <label for="defaultgroupField">Default Group</label>
    <input type="text" name="defaultgroup" class="form-control" id="defaultgroupField" readonly value="<?php if(isset($defaultgroup)) echo $defaultgroup; ?>"/>
    </div>
    <div class="form-group">
    <label for="joinField">Join Date</label>
    <input type="text" name="joindate" class="form-control" id="joindateField" readonly value="<?php if(isset($date_joined)) echo $date_joined; ?>"/>
    </div>
    <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
    <button type="submit" name="updateProfileBtn" class="btn btn-primary pull-right">Update Profile</button>
    </form>
  <?php endif ?>
</section>
<br>
<p><a href="index.php">Back</a></p>
</div>

<?php
include_once 'headerfooter/footer.php';
?>


