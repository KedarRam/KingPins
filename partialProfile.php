 <!--
  Name: partialProfile.php 
  Author: Kedar Ram
  2016-12-16
 
  Purpose: Part of Profile update, When profile update/edit is clicked 
        Called within profile.php
-->
<?php
include_once 'common/database.php';
include_once 'common/utilities.php';

    //update active session_status
      $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     $_SESSION['last_active'] = time();
     $_SESSION['fingerprint'] = $fingerprint;

if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['updateProfileBtn'])){
    if(isset($_GET['user_identty'])){
        $url_encoded_id = $_GET['user_identity'];
        $decode_id = base64_decode($url_encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $id = $user_id_array[1];
    }else{
        $id=$_SESSION['id'];
    }    

    $sqlQuery = "SELECT * FROM employee WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array( ':id' => $id));

    while($rs = $statement->fetch())
    {
        $username = $rs['username'];
        $firstname = strtoupper($rs['firstname']);
        $lastname = strtoupper($rs['lastname']);
        $email = $rs['email'];
        $phone = $rs['phone'];
        $defaultgroup = $rs['defaultgroup'];
        $phone_cur = $rs['phone'];
        $date_joined = strftime("%b %d, %Y", strtotime($rs['joindate']));
    }
    $encode_id = base64_encode("encodeuserid{$id}");
}elseif(isset($_POST['updateProfileBtn'])){
    
    //update active session_status
      $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     $_SESSION['last_active'] = time();
     $_SESSION['fingerprint'] = $fingerprint;

    $form_errors = array();

    $required_fields = array('username','email','phone');

    foreach($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
         }
    }

    if((strlen(trim($_POST['username'])) < 4 ) || (strlen(trim($_POST['username'])) > 31 )){
        $form_errors[] = "username should atleast be 6 characters  with a max of 30 characters";
    }else{
        if(!preg_match("/^[a-zA-Z]*$/",$_POST['username'])){
            $form_errors[] = "username can contain only lower and uppercase alphabets";
        }
    }
    if(strlen(trim($_POST['phone'])) < 12 ){
        $form_errors[] = "phone number with hypens should be 12 characters long";
    }else{
        if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",$_POST['phone'])){
            $form_errors[] = "phone number should be digits entered in xxx-xxx-xxxx format";
        }
    }
     if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $form_errors[] = "email is not in valid format";
    }

    //collect data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $hidden_id = $_POST['hidden_id'];
    $firstname = strtoupper($_POST['firstname']);
    $lastname = strtoupper($_POST['lastname']);
    $date_joined = strftime("%b %d, %Y", strtotime($_POST['joindate']));

    $id=$_SESSION['id'];
    $sqlQuery = "SELECT * FROM employee WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array( ':id' => $id));

    while($rs = $statement->fetch())
    {
           
        $phone_cur = $rs['phone'];
       
    }

    //if duplicate error
    if (( $_SESSION['username'] != $username ) && (checkDuplicateUserName($username,$db))){
     $result =  "<ul style='color: red;'> $username is already in USE!</ul>";
    
    }elseif(( $_SESSION['email'] != $email ) && (checkDuplicateEmail($email,$db))){
        $result =  " <ul style='color: red;'>$email is already in USE!</ul>";
  
}elseif(empty($form_errors)){
   
    if( ( $phone != $phone_cur) || ( $_SESSION['email'] != $email ) || ( $_SESSION['username'] != $username )){
    try{
        $sqlUpdate = "UPDATE employee SET username =:username ,email =:email, phone =:phone, updatedate=now() WHERE id =:id";
                                        
        $statement = $db->prepare($sqlUpdate);
        $statement->execute(array(':username' => $username, ':email' => $email, ':phone' => $phone, ':id' => $hidden_id));

        if($statement->rowCount() == 1){
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

               $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
                $_SESSION['last_active'] = time();
                $_SESSION['fingerprint'] = $fingerprint;
          $result = "<ul style='color: green;'>Profile Updated Successfully.</ul>";
                        
        }else{
            $result ="<ul style='color: red;'>No updates for $username !</ul>";
                           
        }


    }catch (PDOException $ex){
                $result = "<ul style='color: red;'>An error occured: " . $ex->getMessage(). "</ul>";
    }
    }else{
         $result = "<ul style='color: red;'>No updates for $username !</ul>";
                         
    }
}else{
    $result = "There are " . count($form_errors) . " errors in the form ";
    $result .= "<ul style='color: red;'>";
    foreach($form_errors as $error){
        $result .= "<li> {$error}</li>";
    }
    $result .= "</ul></p>";
}


}


?>