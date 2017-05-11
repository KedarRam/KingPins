<!--
    Name: createemp.php 
    Author: Kedar Ram
    Date: 2016-12-05

    Purpose: Create Employee

-->
<!-- common header -->
<?php
include_once "common/database.php";
include_once "common/utilities.php";
include_once 'headerfooter/header.php';

if(isset($_POST['CreateBtn'])){

    //update active session_status
    $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     $_SESSION['last_active'] = time();
     $_SESSION['fingerprint'] = $fingerprint;

    //create array to store errors
    $form_errors=array();
    $required_fields = array('username','firstname','lastname','email','phone');

    //check required fields
    foreach ($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
        }
    }

    //validate data 
    //username should be atleast 4 characters and not over 30 long and use upper and lowercase alphabets
    if(strlen(trim($_POST['username'])) < 4 || (strlen(trim($_POST['username'])) > 31)){
        $form_errors[] = "Username should be atleast 4 characters long and under 30 characters";
        if(!preg_match("/^[a-zA-Z0-9]*$/",trim($_POST['username']))){
            $form_errors[] = "username should only be lower and upper case alphabets and can contain number 0-9";
        }
    }   
    //firstname and lastname should contain only alphabets
        if(!preg_match("/^[a-zA-Z ]*$/",trim($_POST['firstname']))){
            $form_errors[] = "firstname should only be lower and upper case alphabets";
        }
         if(!preg_match("/^[a-zA-Z ]*$/",trim($_POST['lastname']))){
            $form_errors[] = "lastname should only be lower and upper case alphabets";
        }

    //email should have right format
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $form_errors[] = "Email is not in valid format";
    }

    //phone should have right format
    if(strlen(trim($_POST['phone'])) < 12){
        $form_errors[] = "phone numer with hypens should be 12 characters long";
    }else{
        if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",trim($_POST['phone']))){
            $form_errors[] = "phone numer should be in xxx-xxx-xxxx format";
        }
    }

    //collect values
    $username=trim($_POST['username']);
    $password=trim($_POST['password']);
    $firstname=trim($_POST['firstname']);
    $lastname=trim($_POST['lastname']);
    $email=trim($_POST['email']);
    $phone=trim($_POST['phone']);
    $defaultgroup=trim($_POST['defaultgroup']);
    $isActive=$_POST['isActive'];
    $isManager=$_POST['isManager'];

    //check for duplicate username or email
    if(checkDuplicateEmail($email,$db)){
        $result = "<ul style='color: red;'>Email is already in use</ul>";
    }elseif(checkDuplicateUsername($username,$db)){
        $result = "<ul style='color: red;'>Username is already in use</ul>";
    }elseif(empty($form_errors)){

    //encrypt password 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try{

        $sqlInsert = "INSERT INTO employee (username,password,firstname,lastname,email,phone,defaultgroup,joindate,isActive,isManager)
        VALUES(:username,:password,:firstname,:lastname,:email,:phone,:defaultgroup,now(),:isActive,:isManager)";
        $statement = $db->prepare($sqlInsert);
        $statement->execute(array(':username' => $username,':password' => $hashed_password, ':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':phone' => $phone, ':defaultgroup' => $defaultgroup, ':isActive' => $isActive, ':isManager' => $isManager));

        if($statement->rowCount() == 0){
            $result = "<ul style='color: red;'>Unable to create employee</ul>";
        }else{
                   //if login successful
                   $result = "<ul style='color: green;'>Created employee</ul>";
                   // header("location:index.php");
                    }
        }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
        }
    }else{
        //display errors
        if(count($form_errors) > 0){
            $result = "There are " . count($form_errors) . " errors in this form";
            $result .= "<ul style='color: red;'>";
            foreach($form_errors as $error){ 
                $result .= "<li> {$error}</li>";
            }
            $result .= "</ul>";
        }
    }
} //end button click

?>

<!-- Form -->
<div class="container">
<section class="col col-lg-7">
<div class="clearfix"></div>
<h3> Create Employee</h3>
 <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
<div>
<?php if(isset($result)) echo $result; ?>
<form action="" method="post">
<div class="form-group">
 <label for="usernameField">Username:</label>
  <input type="text"  name="username" class="form-control" id="username" value=""></input>
  </div>
  <div class="form-group">
  <label for="passwordField">Password:</label>
  <input type="password"  name="password"  class="form-control" id="password" value="kingpins"></input>
  </div>
  <div class="form-group">
  <label for="firstnameField">First name:</label>
   <input type="text"  name="firstname" class="form-control" id="firstname" value=""></input>
   </div>
   <div class="form-group">
  <label for="lastnameField">Last name:</label>
   <input type="text"  name="lastname" class="form-control" id="lastname" value=""></input>
   </div>
   <div class="form-group">
  <label for="emailField">Email:</label>
   <input type="text"  name="email" class="form-control" id="email" value=""></input>
   </div>
   <div class="form-group">
  <label for="phoneField">Phone (xxx-xxx-xxxx):</label>
   <input type="text"  name="phone" class="form-control" id="phone" value=""></input>
   </div>
  <div class="form-group">
  <label for="defaultgroupField">Default Group</label>
  <div class="selectContainer">
      <select class="form-control" name="defaultgroup">
          <option value="">Choose a group</option>
          <option value="0">Vacation</option>
          <option value="1">Group 1 - Tue and Fri off; Sat-Sun 11AM-3PM, Mon,Wed,Thur 7PM-11PM</option>
          <option value="2">Group 2 - Mon and Thu off; Tue-Wed,Fri-Sun 3PM-7PM</option>
          <option value="3">Group 3 - Sun and Wed off; Mon and Thu 3PM-7PM, Tue and Fri-Sat 7PM-11PM</option>
  </select>
  </div>
  </div>
   <div class="form-group">
   <label for="activeField">Is Active Employee:</label>
   <input type="radio" id="Yes" name="isActive" value=1 checked>Active</input>
   <input type="radio" id="No" name="isActive" value=0>InActive</input><br>
   </div>
   <div class="form-group">
   <label for="managerField">Is Manager:</label>
   <input type="radio" id="Yes" name="isManager" value=1>Manager</input>
   <input type="radio" id="No" name="isManager" value=0 checked>Not Manager</input>

  </div>
 
  <button type="submit" name="CreateBtn" class="btn btn-primary pull-right">Create Employee</button>
</form>
  <?php endif ?>
 </section>
  <br>
  <p><a href="empmanager.php">Back</a></p>
  </div>
<!-- common header -->
<?php
include_once 'headerfooter/footer.php';
?>
