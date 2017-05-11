 <!--
  Name: partialMgreditProfile.php 
  Author: Kedar Ram
  2016-12-22
 
  Purpose: Process Logic for Manager edit employee profile
  When update/edit button is clicked, process the update after validation

-->
<?php
include_once 'common/database.php';
include_once 'common/utilities.php';

if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['updateEmpProfileBtn'])){
    if(isset($_GET['user_identity'])){
        $url_encoded_id = $_GET['user_identity'];
        $decode_id = base64_decode($url_encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $id = $user_id_array[1];
    }else{
        $id=$_SESSION['id'];
    }

    
    $encode_id = base64_encode("encodeuserid{$id}");

    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;
   
}elseif(isset($_POST['updateEmpProfileBtn'])){
      //Use the search id as identifier to be updated
       $id_cur=$_SESSION['id_disp'];

//Get values from DB for employee that the manager wants to update
    $sqlQuery = "SELECT * FROM employee WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array( ':id' => $id_cur));
    
    while($rs = $statement->fetch())
    {
        $username_cur = $rs['username'];
        $email_cur = $rs['email'];
        $phone_cur = $rs['phone'];
	    $defaultgroup_cur = $rs['defaultgroup'];
        $joindate_cur = strftime("%b %d, %Y", strtotime($rs['joindate']));
        $isManager_cur = $rs['isManager'];
        $isActive_cur = $rs['isActive'];
        $id_cur = intval($rs['id']);
    }
   
   
    $form_errors = array();

    //check validility of input data set
    $required_fields = array('username_disp','email_disp','phone_disp');

    foreach($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
         }
    }

    if((strlen(trim($_POST['username_disp'])) < 4 ) || (strlen(trim($_POST['username_disp'])) > 31 )){
        $form_errors[] = "username should atleast be 6 characters  with a max of 30 characters";
    }else{
        if(!preg_match("/^[a-zA-Z]*$/",$_POST['username_disp'])){
            $form_errors[] = "username can contain only lower and uppercase alphabets";
        }
    }
    if(strlen(trim($_POST['phone_disp'])) < 12 ){
        $form_errors[] = "phone number with hypens should be 12 characters long";
    }else{
        if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",$_POST['phone_disp'])){
            $form_errors[] = "phone number should be digits entered in xxx-xxx-xxxx format";
        }
    }
     if(!filter_var($_POST['email_disp'], FILTER_VALIDATE_EMAIL))
    {
        $form_errors[] = "email is not in valid format";
    }

    //collect data
    $email = $_POST['email_disp'];
    $username = $_POST['username_disp'];
    $phone = $_POST['phone_disp'];
    $defaultgroup = $_POST['defaultgroup_disp'];
    $isManager = intval($_POST['isManager_disp']);
  
    $isActive = intval($_POST['isActive_disp']);
    $joindate_comp = strftime("%b %d, %Y", strtotime($_POST['joindate_disp']));
    $joindate = date("Y-m-d H:i:s",strtotime($_POST['joindate_disp']));

    //check if there any data changes
    $change = 0;
    if ( $username != $username_cur){
     
        $change = 1;

    //if duplicate error
    
    if ((checkDuplicateUserName($username,$db))){
     $return = " <ul style='color: red;'>$username is already in USE!</ul>";
                  
    }
    }elseif ($email != $email_cur){
       
        $change = 1;
        
        //if duplicate error
        if((checkDuplicateEmail($email,$db))){
            $return = " <ul style='color: red;'>$email is already in USE!</ul>";
           
        }
    }elseif ($phone != $phone_cur){
       
        $change = 1;
    }elseif ($defaultgroup != $defaultgroup_cur){
    
        $change = 1;
    }elseif ($isActive != $isActive_cur){
      
        $change = 1;
      }elseif ($isManager != $isManager_cur){
         
          $change = 1;
      }elseif ($joindate_comp != $joindate_cur){
        
          $change = 1;
      }


  //if not errors update database
if(empty($form_errors)){
    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;
    //if no changes update
   if ($change == 1){
    try{

        $sqlUpdate = "UPDATE employee SET username =:username ,email =:email, phone =:phone, defaultgroup =:defaultgroup, isActive =:isActive, isManager =:isManager, joindate =:joindate, updatedate=now() WHERE id =:id";
                                        
        $statement = $db->prepare($sqlUpdate);
        $statement->execute(array(':username' => $username, ':email' => $email, ':phone' => $phone, ':defaultgroup' => $defaultgroup, ':isActive' => $isActive, ':isManager' => $isManager, ':joindate' => $joindate, ':id' => $id_cur));

        if($statement->rowCount() == 1){
            $_SESSION['id'] = $id;
          
            $return = "<ul style='color: green;'>Updated $username !</ul>";
                            
                      
        }


    }catch (PDOException $ex){
                $result = "An error occured: " . $ex->getMessage();
    }
   }else{
    $result = "<ul style='color: red;'>No updates for $username !</ul>";
                          
   }
}else{
    //populate result for displaying errors
    $result = "There are " . count($form_errors) . " errors in the form ";
    $result .= "<ul style='color: red;'>";
    foreach($form_errors as $error){
        $result .= "<li> {$error}</li>";
    }
    $result .= "</ul></p>";
}


}


?>
