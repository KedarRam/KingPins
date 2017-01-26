<!--
    Name: partialSearch.php 
    Author: Kedar Ram
    Date: 2016-12-20

    Purpose: search employee

-->
<!-- common header -->
<?php
include_once 'common/database.php';
include_once 'common/utilities.php';

if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['SearchBtn'])){
    if(isset($_GET['user_identty'])){
        $url_encoded_id = $_GET['user_identity'];
        $decode_id = base64_decode($url_encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $id = $user_id_array[1];
}else{
    $id=$_SESSION['id'];
    }
    //security
        $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;

    $encode_id = base64_encode("encodeuserid{$id}");
}elseif (isset($_POST['SearchBtn'])){
$hide = 0;

    //security
     $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;

    //username not set, check if email is set
    if((($_POST['searchusername']) == "") && (($_POST['searchemail'])=="") && (($_POST['searchfirstname'])=="")&& (($_POST['searchlastname'])=="")){
            $hide = 0;
             $result = "<ul style='color: red;'>Nothing to Search!</ul>";
    }          
    
    //either one is set
    //If username is set (the repeating sections can be made into utility)
    if(strlen($_POST['searchusername']) > 0){
          //collect data
      $username = $_POST['searchusername'];
    
        try{
            $sqlQuery = "SELECT * FROM employee WHERE username = :usename";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':usename' => $username));

            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Employee with Username:$username!</ul>";
                         
            }else{
                while($rs = $statement->fetch())
                {
                $hide = 1;
                $id_disp = $rs['id'];
                $username_disp = $rs['username'];
                $firstname_disp = strtoupper($rs['firstname']);
                $lastname_disp = strtoupper($rs['lastname']);
                $email_disp = $rs['email'];
                $phone_disp = $rs['phone'];
                $date_joined_disp = strftime("%b %d, %Y", strtotime($rs['joindate']));
                $isActive_disp = $rs['isActive'];
                $isManager_disp = $rs['isManager'];
                $date_update_disp = strftime("%b %d, %Y", strtotime($rs['updatedate']));
                 $encode_id = base64_encode("encodeuserid{$id_disp}");
                $_SESSION['id_disp'] = $id_disp;
                }   
            }
           
          }catch (PDOException $ex){
            $hide = 0;
            $result = "<ul style='color: red;'>Unable to find Employee with Username:$username!</ul>";       
        }
         
    }elseif(strlen($_POST['searchemail']) > 0){

             $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
            $_SESSION['last_active'] = time();
            $_SESSION['fingerprint'] = $fingerprint;
            //collect data
            $email = $_POST['searchemail'];
  
        try{
            $sqlQuery = "SELECT * FROM employee WHERE email = :email";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':email' => $email));
        
            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Employee with Email:$email!</ul>";
                        
            }else 
            {
                while($rs = $statement->fetch())
                {
                    $hide = 1;
                    $id_disp = $rs['id'];
                    $username_disp = $rs['username'];
                    $firstname_disp = strtoupper($rs['firstname']);
                    $lastname_disp = strtoupper($rs['lastname']);
                    $email_disp = $rs['email'];
                    $phone_disp = $rs['phone'];
                    $date_joined_disp = strftime("%b %d, %Y", strtotime($rs['joindate']));
                    $isActive_disp = $rs['isActive'];
                    $isManager_disp = $rs['isManager'];
                    $date_update_disp = strftime("%b %d, %Y", strtotime($rs['updatedate']));
                    $encode_id = base64_encode("encodeuserid{$id_disp}");
                    $_SESSION['id_disp'] = $id_disp;
                }   
            }
            }catch (PDOException $ex){
                $hide = 0;
                 $result = "<ul style='color: red;'>Unable to find Employee with Email:$email!</ul>";
            }
       
       
    }elseif(strlen($_POST['searchfirstname']) > 0){

            $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
            $_SESSION['last_active'] = time();
            $_SESSION['fingerprint'] = $fingerprint;

            //check if lastname is also available for search
            if(strlen($_POST['searchlastname'])> 0) {
                //both first and last name available for search
                 $firstname = strtoupper($_POST['searchfirstname']);
                 $lastname = strtoupper($_POST['searchlastname']);

                 try{
                   $sqlQuery = "SELECT * FROM employee WHERE upper(firstname) = :firstname AND upper(lastname) = :lastname";
                   $statement = $db->prepare($sqlQuery);
                   $statement->execute(array(':firstname' => $firstname, ':lastname' => $lastname));

                   if ($statement->rowCount() == 0)
                   {
                      $hide = 0;
                      $result = "<ul style='color: red;'>Unable to find Employee with Firstname:$firstname and Lastname: $lastname!</ul>";
                            
                    }elseif  ($statement->rowCount() > 1){
                      $hide = 0;
                      $result = "<ul style='color: red;'>More than one employee with First Name:$firstname and Last Name:$lastname!</ul>";
                    }else
                    {
                         while($rs = $statement->fetch())
                        {
                            $hide = 1;
                            $id_disp = $rs['id'];
                            $username_disp = $rs['username'];
                            $firstname_disp = strtoupper($rs['firstname']);
                            $lastname_disp = strtoupper($rs['lastname']);
                            $email_disp = $rs['email'];
                            $phone_disp = $rs['phone'];
                            $date_joined_disp = strftime("%b %d, %Y", strtotime($rs['joindate']));
                            $isActive_disp = $rs['isActive'];
                            $isManager_disp = $rs['isManager'];
                            $date_update_disp = strftime("%b %d, %Y", strtotime($rs['updatedate']));
                            $encode_id = base64_encode("encodeuserid{$id_disp}");
                            $_SESSION['id_disp'] = $id_disp;
                        }   
                    }
           
                }catch (PDOException $ex){
                    $hide = 0;
                    $result = "<ul style='color: red;'>Unable to find Employee with Firstname:$firstname and Lastname:$lastname!</ul>";             
                }
            }else{
                //need to add firstname and lastname partial searches if time permits
                $firstname = strtoupper($_POST['searchfirstname']);

                $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
                $_SESSION['last_active'] = time();
                $_SESSION['fingerprint'] = $fingerprint;
    
            try{
                $sqlQuery = "SELECT * FROM employee WHERE upper(firstname) = :firstname";
                $statement = $db->prepare($sqlQuery);
                $statement->execute(array(':firstname' => $firstname));

                if ($statement->rowCount() == 0)
                {
                    $hide = 0;
                    $result = "<ul style='color: red;'>Unable to find Employee with Firstname:$firstname!</ul>";
                         
                }elseif  ($statement->rowCount() > 1){
                     $hide = 0;
                     $result = "<ul style='color: red;'>More than one employee with First Name:$firstname!</ul>";
                }else
                {
                    while($rs = $statement->fetch())
                    {
                        $hide = 1;
                        $id_disp = $rs['id'];
                        $username_disp = $rs['username'];
                        $firstname_disp = strtoupper($rs['firstname']);
                        $lastname_disp = strtoupper($rs['lastname']);
                        $email_disp = $rs['email'];
                        $phone_disp = $rs['phone'];
                        $date_joined_disp = strftime("%b %d, %Y", strtotime($rs['joindate']));
                        $isActive_disp = $rs['isActive'];
                        $isManager_disp = $rs['isManager'];
                        $date_update_disp = strftime("%b %d, %Y", strtotime($rs['updatedate']));
                        $encode_id = base64_encode("encodeuserid{$id_disp}");
                        $_SESSION['id_disp'] = $id_disp;
                    }   
                }
           
             }catch (PDOException $ex){
                 $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Employee with Firstname:$firstname!</ul>";
                            
             }
         }
    }elseif(strlen($_POST['searchlastname']) > 0){
    //need to add firstname and lastname partial searches if time permits
        $lastname = strtoupper($_POST['searchlastname']);

         $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    
        try{            
            $sqlQuery = "SELECT * FROM employee WHERE upper(lastname) = :lastname";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':lastname' => $lastname));

            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Employee with Lastname:$lastname!</ul>";
                           
            }elseif  ($statement->rowCount() > 1){
                $hide = 0;
                $result = "<ul style='color: red;'>More than one employee with Last Name:$lastname!</ul>";
                           
            }else{        
                while($rs = $statement->fetch())
                {
                    $hide = 1;
                    $id_disp = $rs['id'];
                    $username_disp = $rs['username'];
                    $firstname_disp = strtoupper($rs['firstname']);
                    $lastname_disp = strtoupper($rs['lastname']);
                    $email_disp = $rs['email'];
                    $phone_disp = $rs['phone'];
                    $date_joined_disp = strftime("%b %d, %Y", strtotime($rs['joindate']));
                    $isActive_disp = $rs['isActive'];
                    $isManager_disp = $rs['isManager'];
                    $date_update_disp = strftime("%b %d, %Y", strtotime($rs['updatedate']));
                    $encode_id = base64_encode("encodeuserid{$id_disp}");
                    $_SESSION['id_disp'] = $id_disp;
                }   
        
                }
             }catch (PDOException $ex){
                 $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Employee with lastname:$lastname!</ul>";
                       
            }
         
    }
}
?>
