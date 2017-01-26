<!--
    Name: partialSearchCust.php 
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

    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;

    //username not set, check if email is set
    if((($_POST['searchcustphone']) == "") && (($_POST['searchcustfirstname'])=="") && (($_POST['searchcustlastname'])=="")){
            $hide = 0;
             $result = "<ul style='color: red;'>Nothing to Search! </ul>";
    }          
    
    //either one is set    

        if((strlen(trim($_POST['searchcustphone'])))> 0){

          //phone should have right format
        if(strlen(trim($_POST['searchcustphone'])) < 12){
            $result = "<ul style='color: red;'>phone numer with hypens should be 12 characters long</ul>";
        }else{
            if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",trim($_POST['searchcustphone']))){
                $result = "<ul style='color: red;'>phone numer should be in xxx-xxx-xxxx format</ul>";
            }
        }
      //collect data
      $custphone= $_POST['searchcustphone'];

        try{
            $sqlQuery = "SELECT * FROM customer WHERE custphone = :custphone and custexit is NULL";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':custphone' => $custphone));

            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find customer with $custphone ! </ul> ";
                         
            }else
            {
                while($rs = $statement->fetch())
                {
                    $hide = 1;
            
                    $custphone_disp = $rs['custphone'];
                    $custfirstname_disp = strtoupper($rs['custfirstname']);
                    $custlastname_disp = strtoupper($rs['custlastname']);
                    $custenter_disp = strftime("%b %d, %Y", strtotime($rs['custenter']));
                    $enterempid_disp = $rs['enter_empid'];
        
                }   
            }
           
        }catch (PDOException $ex){
            $hide = 0;
            $result = "<ul style='color: red;'>Unable to find Customer with Phone:$custphone!</ul>";
                       
        }
         
          
       
        }elseif(strlen($_POST['searchcustfirstname']) > 0){
           

            $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
            $_SESSION['last_active'] = time();
            $_SESSION['fingerprint'] = $fingerprint;

            //check if lastname is also available for search
            if(strlen($_POST['searchcustlastname'])> 0) {
                //both first and last name available for search
                 $firstname = strtoupper($_POST['searchcustfirstname']);
                 $lastname = strtoupper($_POST['searchcustlastname']);

                 try{
                     $sqlQuery = "SELECT * FROM customer WHERE upper(custfirstname) = :firstname AND upper(custlastname) = :lastname and custexit is NULL</ul>";
                    $statement = $db->prepare($sqlQuery);
                    $statement->execute(array(':firstname' => $firstname, ':lastname' => $lastname));

                    if ($statement->rowCount() == 0)
                    {
                          $hide = 0;
                          $result = "<ul style='color: red;'>Unable to find Customer with Firstname:$firstname and Lastname: $lastname!</ul>";
                            
                    }elseif  ($statement->rowCount() > 1){
                        $hide = 0;
                        $result = "<ul style='color: red;'>More than one Customer with First Name:$firstname and Last Name:$lastname!</ul>";
                    }else
                    {
                        while($rs = $statement->fetch())
                        {
                            $hide = 1;
                            $custphone_disp = $rs['custphone'];
                            $custfirstname_disp = strtoupper($rs['custfirstname']);
                            $custlastname_disp = strtoupper($rs['custlastname']);
                            $custenter_disp = strftime("%b %d, %Y", strtotime($rs['custenter']));
                            $enterempid_disp = $rs['enter_empid'];
   
                        }   
                    }
           
                    }catch (PDOException $ex){
                        $hide = 0;
                        $result = "<ul style='color: red;'>Unable to find Customer with Firstname:$firstname and Lastname:$lastname!</ul>";
                            
                    }
            }else{
                //need to add firstname and lastname partial searches if time permits
                $firstname = strtoupper($_POST['searchcustfirstname']);

            $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
            $_SESSION['last_active'] = time();
            $_SESSION['fingerprint'] = $fingerprint;
    
            try{
            $sqlQuery = "SELECT * FROM customer WHERE upper(custfirstname) = :firstname";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':firstname' => $firstname));

            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Customer with Firstname:$firstname!</ul>";
                         
            }elseif  ($statement->rowCount() > 1){
                $hide = 0;
                $result = "<ul style='color: red;'>More than one customer with First Name:$firstname!</ul>";
            }else
            {
                while($rs = $statement->fetch())
                {
                    $hide = 1;
                    $custphone_disp = $rs['custphone'];
                    $custfirstname_disp = strtoupper($rs['custfirstname']);
                    $custlastname_disp = strtoupper($rs['custlastname']);
                    $custenter_disp = strftime("%b %d, %Y", strtotime($rs['custenter']));
                    $enterempid_disp = $rs['enter_empid'];
      
                }   
            }
           
             }catch (PDOException $ex){
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Customer with Firstname:$firstname!</ul>";
                            
            }
            }
    }elseif(strlen($_POST['searchcustlastname']) > 0){
    //need to add firstname and lastname partial searches if time permits
        $lastname = strtoupper($_POST['searchcustlastname']);

        $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    
        try{            
            $sqlQuery = "SELECT * FROM customer WHERE upper(custlastname) = :lastname";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':lastname' => $lastname));

            if ($statement->rowCount() == 0)
            {
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Customer with Lastname:$lastname!</ul>";
                           
            }elseif ($statement->rowCount() > 1){
                $hide = 0;
                $result = "<ul style='color: red;'>More than one Customer with Last Name:$lastname!</ul>";
                           
            }else{        
                while($rs = $statement->fetch())
                {
                $hide = 1;
       
                $custphone_disp = $rs['custphone'];
                $custfirstname_disp = strtoupper($rs['custfirstname']);
                $custlastname_disp = strtoupper($rs['custlastname']);
                $custenter_disp = strftime("%b %d, %Y", strtotime($rs['custenter']));
                $enterempid_disp = $rs['enter_empid'];
      
                }   
        
            }
            }catch (PDOException $ex){
                $hide = 0;
                $result = "<ul style='color: red;'>Unable to find Customer with lastname:$lastname!</ul>";
                       
            }
        }
    }

    ?>
