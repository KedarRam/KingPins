<!--
    Name: reset-password.php 
    Author: Kedar Ram
    Date: 2016-12-10

    Purpose: reset password

-->
<!-- common header -->
<?php
include_once 'common/database.php';
include_once 'common/utilities.php';

if(isset($_POST['ResetBtn'])){

    //security
      $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     $_SESSION['last_active'] = time();
     $_SESSION['fingerprint'] = $fingerprint;
    //errors
    $form_errors = array ();
    $required_fields = array('email','new_password','confirm_password');

    foreach($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
         }
    }

    //validate field data and length
    //email should be a valid address
    //password should be minimum of 6 character a-zA-Z0-9 allowed
    if(strlen(trim($_POST['new_password'])) < 6 ){
        $form_errors[] = "password should atleast be 6 characters long";
    }else{
        if(!preg_match("/^[a-zA-Z0-9]*$/",$_POST['new_password'])){
            $form_errors[] = "password can contain only lower, uppercase alphabets and digits 0-9";
        }
    }
    if(strlen(trim($_POST['confirm_password'])) < 6 ){
        $form_errors[] = "password should atleast be 6 characters long";
    }else{
        if(!preg_match("/^[a-zA-Z0-9]*$/",$_POST['confirm_password'])){
            $form_errors[] = "password can contain only lower, uppercase alphabets and digits 0-9";
        }
    }
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $form_errors[] = "email is not in valid format";
    }
//If no errors encrypt password and add into database, else display error
    if(empty($form_errors)){

    //collect data
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password != $confirm_password){
        
        $result = "New password and Confirm password do not match.";
    }else{
        try{
            //verify email address
            $sqlQuery = "SELECT email FROM employee WHERE email = :email";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':email' => $email));

            if($statement->rowCount() == 1){
                //found email
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                //update password in DB
                $sqlUpdate = "UPDATE employee SET password =:new_password WHERE email=:email";
                $statement = $db->prepare($sqlUpdate);
                $statement->execute(array(':new_password' => $hashed_password, ':email' => $email));

                $result = "Password updated";
                //redirectTo("index");
                
            }else{
                 
                $result = "Unable to update Password, check email address";
            }
        }catch (PDOException $ex){
            $result = "An error occured: " . $ex->getMessage(); 
        }
    }
}else{
    if(count($form_errors) > 0 ){
        $result = "There are " . count($form_errors) . " errors in the form.";
        $result .= "<ul style='color: red;'>";
        foreach($form_errors as $error){
            $result .= "<li> {$error}</li>";
        }
        $result .= "</ul></p>";
    }
}
}
?>






<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Password Reset Page</title>
</head>
<body>
<?php
$page_title="Welcome to KingPins FEC - Forgot Password Page";
include_once 'headerfooter/header.php';
?>

<div class="container">
<section class="col col-lg-7">
<br>
<br>
<h2>Password Reset Form</h2><hr>
<div>
<?php if(isset($result)) echo $result; ?>
 </div>
    <div class="clearfix"></div>

<form action="" method="post">
  <div class="form-group">
    <label for="emailField">Email Address</label>
    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="passwordField">New Password</label>
    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
  </div>
   <div class="form-group">
    <label for="passwordField2">Confirm Password</label>
    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat Password">
  </div>
  
  <button type="submit" name="ResetBtn" class="btn btn-primary pull-right" >Reset Password</button>
</form>
</section>
<br>
<br>
<br>
<br>
  <?php if($_SESSION['manager'] == 1): ?>
  <p><a href="mgrmain.php">Back</a></p>
  <?php else: ?>
  <p><a href="empmain.php">Back</a></p>
  <?php endif ?>
</div>


<?php
include_once 'headerfooter/footer.php';
?>
</body>
</head>


