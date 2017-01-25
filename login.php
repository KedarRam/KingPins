<!--
    Name: login.php 
    Author: Kedar Ram
    Date: 2016-12-02

    Purpose: Login form and validations

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Home Page";
include_once 'headerfooter/header.php';
include_once "common/database.php";
?>
<?php


//When Login Button is pressed
if(isset($_POST['LoginBtn'])){

    //create array to store errors
    $form_errors=array();
    $required_fields = array('username','password');

    //check if required fields are populated
    foreach ($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
        }
    }

    //If no errors
    if(empty($form_errors)){

        //collect values
        $username=trim($_POST['username']);
        $password=trim($_POST['password']);

        //Query table to check if username
        $sqlQuery = "SELECT id,username,password,email,isActive,isManager FROM employee WHERE username =:username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $username));

        if($statement->rowCount() == 0){
            //error if no records found
            $result = "<ul style='color: red;'>Invalid username</ul>";
        }else{
            //record found
            while($row = $statement->fetch()){
               
                //collect data
                $id=$row['id'];
                $username=$row['username'];
                $hashed_password=$row['password'];
                $email=$row['email'];
                $isActive=$row['isActive'];
                $isManager=$row['isManager'];

                //compare encrypted password
                if(!password_verify($password, $hashed_password)){
                    $result = "<ul style='color: red;'>Incorrect password</ul>";
                }else{
                    //if login successful
                    //setup global variables to use throughout the application
                    $_SESSION['id'] =$id;
                    $_SESSION['username']=$username;
                    $_SESSION['email']=$email;
                    $_SESSION['manager']=$isManager;

                    //security feature for timeout on inactivity
                    $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
                    $_SESSION['last_active'] = time();
                    $_SESSION['fingerprint'] = $fingerprint;

                    //Check if user is employee or manager
                    if(($isActive ==1) && ($isManager == 1)){
                     //redirect to Manager Main Menu
                      $_SESSION['manager']=$isManager;
                      header("location:mgrmain.php");
                    }elseif(($isActive ==1) && ($isManager == 0)){
                        //redirect to Emp Main Menu
                      $_SESSION['manager']=$isManager;
                          header("location:empmain.php");
                    }else{
                        //Employee inactive/deleted
                        $result = "<ul style='color: red;'>Employee not active, contact manager</ul>";
                    }
              }
            }
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
}

?>

<div class="container">
<div class="flag">
<h2> Welcome to KingPins FEC - Login Page </h2>
<!-- display messages -->
<?php if(isset($result)) echo $result; ?>
<!-- form to gather input -->
<form action="" method="post">
  Username:<br>
  <input type="text" name="username" value=""><br>
  Password:<br>
  <input type="password" name="password" value=""><br><br>
  <button type="submit" name="LoginBtn" >Login</button> 
  <a href="forgot-password.php"> Forgot Password? </a>
</form>
</div>
<!-- back -->
<div style="text-align:center"><a href="login.php">Back</a></div>
</div>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>