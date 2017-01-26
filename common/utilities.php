<!--
  Name: utilities.php 
  Author: Kedar Ram
  2016-12-03
 
  Purpose: Functions used within the application

  Functions:
  guard() - inactivity check
  signout() - logout if inactive
  redirectTo($page) - move to different page
  checkDuplicatePhone($value,$db) - check unique customer phone
  checkDuplicateEmail($value,$db) - check unique email
  checkDuplicateUsername($value,$db) - check unique username
  markcustexit($custphone,$exit_empid,$db) - mark customer exit time
  checkDuplicateempidforweeknumber($empid,$weeknumber,$db) - check uniqye empid and weeknumber
  AllApprove($id,$db) - approve all pending schedule change request
  default_count_by_group($db,$emptype) - count default from employee db
  change_unapproved_count_by_group($db,$emptype,$date) - count change request from employee db and emp_schedule_change
  change_approved_count_by_group($db,$emptype,$date) - approved change request count
  getgroup($db,$dow) - get group info from schedule table
  highlightgroup($db,$id,$weeknumber) - identify group for the current week
  getdefaultgroup($db,$id)
  getchangegroup($db,$id,$dow)
  highlightText($text)
-->
<?php

//Redirect pages
function redirectTo($page){
    //header("location:{$page}.php");
    header("Location: {$page}.php");
    //Warning: Cannot modify header information - headers already sent by (output started at /some/file.php:12) in /some/file.php on line 23
    //needed to switch to java script to avoid errors - towards the end of the project as a quickfix
}

//checkDuplicatePhones there cannot be duplicate phone number for a customer already in the facility
function checkDuplicatePhone($value,$db){
    try{
        $sqlQuery = "SELECT * from customer WHERE custphone=:custphone and custexit is NULL";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':custphone' => $value));

        if($statement->rowCount() > 0){
            return true;
        }
        return false;
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
}

//checkDuplicateEmail - unique for an employee
function checkDuplicateEmail($value,$db){
    try{
        $sqlQuery = "SELECT * from employee WHERE email=:email";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':email' => $value));

        if($row = $statement->fetch()){
            return true;
        }
        return false;
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
}

//checkDuplicateUsername - unique for an employee
function checkDuplicateUsername($value,$db){
    try{
        $sqlQuery = "SELECT * from employee WHERE username=:username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $value));

        if($row = $statement->fetch()){   
            return true;
        }
        return false;
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
}

//mark customer exit - mark a customer as exited the facility
function markcustexit($custphone,$exit_empid,$db){
try{
        $sqlUpdate = "UPDATE customer SET custexit=now(), exit_empid =:exit_empid WHERE custphone =:custphone";
        $statement = $db->prepare($sqlUpdate);
        $statement->execute(array(':exit_empid' => $exit_empid, ':custphone' => $custphone));
      
        if($statement->rowCount() == 1){
            return true;
        }
        return false;

}catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() ."</ul>";
    }
}

//security feature for logout if inctive for a period of time
function guard(){
    $isValid = true;
    $inactive = 60 * 10; //10 mins 
    $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

    if((isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] != $fingerprint)){
        $isValid = false;
       signout();
    }elseif ((isset($_SESSION['last_active']) && (time() - $_SESSION['last_active']) > $inactive) && $_SESSION['username'])
{
    $isValid = false;
    signout();
}else{
    $_SESSION['last_active'] = time();
}
    return $isValid;
}

//signout user on logout
function signout(){
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    unset($_SESSION['manager']);
    unset($_SESSION['last_active']);
    unset($_SESSION['fingerprint']);

    session_destroy();
    session_regenerate_id(true);
    redirectTo('index');
}

//check for unique employeeID and work week combination
function checkDuplicateempidforweeknumber($empid,$weeknumber,$db){
      try{
        $sqlQuery = "SELECT * from emp_schedule_change WHERE empid=:empid and weeknumber =:weeknumber and delete_indicator !=1";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':empid' => $empid, ':weeknumber' => $weeknumber));
        
        if($row = $statement->fetch()){
            return true;
        }
        return false;
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
    }
 }

// approve all outstanding employee group change request
function AllApprove($id,$db){
      try{
        $sqlQuery = "UPDATE emp_schedule_change SET approved=1, approvedby=:id WHERE delete_indicator != 1";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':id' => $id));
        
        if($statement->rowCount() == 1){
            return true;
        }
        return false;
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
    }
 }

//Gather default count by group 
function default_count_by_group($db,$emptype){
      if ($emptype == "emp"){
             $ismanager=0;
    }else{
        $ismanager=1;
    }
  
     try{
        $sqlQuery = "SELECT defaultgroup, count(id) as count FROM employee WHERE isActive=1 and isManager=:ismanager group by defaultgroup";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('ismanager' => $ismanager));

        if($statement->rowCount() > 0){
            $ret = "";
        while($row = $statement->fetch()){
            if($row['defaultgroup'] == 0){
                $ret = $ret . "GROUP0:" . $row['count'] . ",";
            }else{
                if($row['defaultgroup'] == 1){  
                    $ret = $ret . "GROUP1:" . $row['count'] . ",";
                }else{
                    if($row['defaultgroup'] == 2){
                        $ret = $ret . "GROUP2:" . $row['count'] . ",";
                    }else{
                        if($row['defaultgroup'] == 3){
                            $ret = $ret . "GROUP3:" . $row['count'] . ",";
                        }
                    }
                }
            }

            
        
        }
        //ret should contain
        //GROUP0:5,GROUP1:5,GROUP2:5,GROUP3:5
       
        return $ret;
        }else{
            $retnodata="GROUP0:0,GROUP1:0,GROUP2:0,GROUP3:0";
            return $retnodata;
        }
    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
    

 }

function change_unapproved_count_by_group($db,$emptype,$date){
//GROUP0:GROUP1:5,GROUP0:GROUP2:5,GROUP0:GROUP3:5,GROUP1:GROUP0:5 etc
//convert date to weeknumber 
$weeknumber=date("W",strtotime($date));

//check emptype
if ($emptype == "emp"){
    $ismanager=0;
}else{
    $ismanager=1;
}



     try{
        $sqlQuery = "SELECT defaultgroup, changenumber, count(id) as count FROM employee e, emp_schedule_change s WHERE isActive=1 and isManager= :ismanager and delete_indicator !=1 and approved is NULL and weeknumber =:weeknumber and e.id=s.empid group by defaultgroup changenumber";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('weeknumber' => $weeknumber, 'ismanager' => $ismanager));

        if($statement->rowCount() > 0){
            $ret = "";
            while($row = $statement->fetch()){
                $ret = $ret . $row['defaultgroup'] . ":" . $row['changenumber'].":".$row['count'] . ",";
            }
            //ret should contain
            //GROUP0:5,GROUP1:5,GROUP2:5,GROUP3:5
            return $ret;
        }else{
            $retnodata="GROUP0:GROUP1:0,GROUP0:GROUP2:0,GROUP0:GROUP3:0,";
            $retnodata.="GROUP1:GROUP0:0,GROUP1:GROUP2:0,GROUP1:GROUP3:0,";
            $retnodata.="GROUP2:GROUP0:0,GROUP2:GROUP1:0,GROUP2:GROUP3:0,";
            $retnodata.="GROUP3:GROUP0:0,GROUP3:GROUP1:0,GROUP3:GROUP2:0";
            return $retnodata;
        }

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
  
}

function change_approved_count_by_group($db,$emptype,$date){
//GROUP0:GROUP1:5,GROUP0:GROUP2:5,GROUP0:GROUP3:5,GROUP1:GROUP0:5 etc
//convert date to weeknumber 
$weeknumber=date("W",strtotime($date));

//check emptype
if ($emptype == "emp"){
    $ismanager=0;
}else{
    $ismanager=1;
}

     try{
        $sqlQuery = "SELECT defaultgroup, changenumber, count(id) as count FROM employee e, emp_schedule_change s WHERE isActive=1 and isManager= :ismanager and delete_indicator !=1 and approved=1 and weeknumber =:weeknumber and e.id=s.empid group by defaultgroup changenumber";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('weeknumber' => $weeknumber, 'ismanager' => $ismanager));

        if($statement->rowCount() > 0){
            $ret = "";
            while($row = $statement->fetch()){
                $ret = $ret . $row['defaultgroup'] . ":" . $row['changenumber'].":".$row['count'] . ",";
            }
            //ret should contain
            //GROUP0:5,GROUP1:5,GROUP2:5,GROUP3:5
            return $ret;
        }else{
            $retnodata="GROUP0:GROUP1:0,GROUP0:GROUP2:0,GROUP0:GROUP3:0,";
            $retnodata.="GROUP1:GROUP0:0,GROUP1:GROUP2:0,GROUP1:GROUP3:0,";
            $retnodata.="GROUP2:GROUP0:0,GROUP2:GROUP1:0,GROUP2:GROUP3:0,";
            $retnodata.="GROUP3:GROUP0:0,GROUP3:GROUP1:0,GROUP3:GROUP2:0";
            return $retnodata;
        }

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
  
}
function getgroup($db,$dow){
    try{
        $sqlQuery = "SELECT *  FROM schedule WHERE dow=:dow";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('dow' => $dow));

        if($statement->rowCount() > 0){
            $ret = "";
            while($row = $statement->fetch()){
                $ret = $ret . "GROUP " . $row['shift1'] . ",GROUP " . $row['shift2'] . ",GROUP " . $row['shift3'];
            }
            //ret should contain
            //GROUP0,GROUP1,GROUP2
           
            return $ret;
        }

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
}

function highlightgroup($db,$id,$weeknumber){
    //get defaultgroup for id 
    try{
        $sqlQuery = "SELECT defaultgroup  FROM employee WHERE id=:id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('id' => $id));

        if($statement->rowCount() > 0){
            $row = $statement->fetch();
            $defaultgroup=$row['defaultgroup'];
        }else{
            $result = "<ul style='color: red;'>Error occurred:";
        }

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }

     $ret = "GROUP ".$defaultgroup;
    //get changegroup

    try{
        $sqlQuery = "SELECT changenumber  FROM  emp_schedule_change WHERE weeknumber =:dow and approved=1 and empid=:id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('id' => $id, 'dow' => $weeknumber));

        if($statement->rowCount() > 0){
            while($row = $statement->fetch()){
                $changegroup=$row['changenumber'];
                $ret = "GROUP ".$changegroup;
               }
        }
           

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }

     return $ret;
        
}

function highlightText($text)
{
    $text = trim($text);
    $text = highlight_string("<?php " . $text, true);  // highlight_string() requires opening PHP tag or otherwise it will not colorize the text
    $text = trim($text);
    $text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $text, 1);  // remove prefix
    $text = preg_replace("|\\</code\\>\$|", "", $text, 1);  // remove suffix 1
    $text = trim($text);  // remove line breaks
    $text = preg_replace("|\\</span\\>\$|", "", $text, 1);  // remove suffix 2
    $text = trim($text);  // remove line breaks
    $text = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

    return $text;
}

function getdefaultgroup($db,$id){
    //get defaultgroup for id 
    try{
        $sqlQuery = "SELECT defaultgroup  FROM employee WHERE id=:id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('id' => $id));

        if($statement->rowCount() > 0){
            $row = $statement->fetch();
            $defaultgroup=$row['defaultgroup'];
        }else{
            $defaultgroup="";
        }

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }
 
     return $defaultgroup;

}

function getchangegroup($db,$id,$weeknumber){

    try{
        $sqlQuery = "SELECT changenumber  FROM  emp_schedule_change WHERE weeknumber =:dow and approved=1 and empid=:id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array('id' => $id, 'dow' => $weeknumber));

        if($statement->rowCount() > 0){
            while($row = $statement->fetch()){
                $changegroup=$row['changenumber'];
                $ret = $changegroup;
        
               }
        }else{
            $ret = "";
        }
           

    }catch(PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage() . "</ul>";
    }

     return $ret;
}
?>