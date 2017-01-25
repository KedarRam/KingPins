<!--
    Name: empschedule.php 
    Author: Kedar Ram
    Date: 2017-01-10

    Purpose: schedule change request

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Employee Schedule Change Profile";
include_once "common/database.php";
include_once "common/utilities.php";
include_once 'headerfooter/header.php';

//Get next three week starting Sundays
$dow=date("w"); 
if ($dow != 0){ 
 $nextsunday=date("Y-m-d",strtotime('next Sunday'));
}else{
 $nextsunday=date("Y-m-d");
} 
$secondsunday=date("Y-m-d",strtotime('next Sunday',strtotime($nextsunday)));
$thirdsunday=date("Y-m-d",strtotime('next Sunday',strtotime($secondsunday))); 

//code starts here
if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['SubmitChangeRequestBtn'])){
    if(isset($_GET['user_identity'])){
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

    //gather data for employee 

    try{
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
        $date_joined = strftime("%b %d, %Y", strtotime($rs['joindate']));
        }
    }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
    }
    $encode_id = base64_encode("encodeuserid{$id}");
}elseif(isset($_POST['SubmitChangeRequestBtn'])){
 
 $form_errors=array();

    //data quality
    if(!preg_match("/^[0-3]$/",trim($_POST['changegroup']))){
             $form_errors[] = "Need group number for change group request";
    }

   if(!preg_match("/^[1-5]*$/",intval(trim($_POST['startweek'])))){
             $form_errors[] = "Need to input week for change group request";
   }
   if(!empty($form_errors)){
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
   else{
//collect data
 $empid=$_SESSION['id'];
 $username=$_POST['username'];
 $firstname=$_POST['firstname'];
 $lastname=$_POST['lastname'];
 $defaultgroup=$_POST['defaultgroup'];
 $weeknumber=intval(trim($_POST['startweek']));
 $changenumber=trim($_POST['changegroup']);
 
 //check if default and change are the same group if so do not insert or update
 if ($defaultgroup == $changenumber){
     $result = "<ul style='color: red;'> No change in default group for week: $weeknumber</ul>";
 }else{
//check if empid for weeknumber already exist
//if exist - update else the rest below/insert
//insert table
 if(checkDuplicateempidforweeknumber($empid,$weeknumber,$db)){
     //update

       try{
       $sqlUpdate = "UPDATE emp_schedule_change SET changenumber =:changenumber, updatedate=now(), approved=0, approvedby=NULL WHERE empid =:empid and weeknumber =:weeknumber";
                                        
        $statement = $db->prepare($sqlUpdate);
        $statement->execute(array(':changenumber' => $changenumber,':empid' => $empid,':weeknumber' => $weeknumber));

        if($statement->rowCount() == 1){
            $result = "<ul style='color: green;'>Updated emp_schedule_change, please wait for manager approval</ul>";
        }else{
            $result = "<ul style='color: red;'>Unable to update emp_schedule_change</ul>";
        }
       }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
        }

 }else{
     //insert
     try{
        $sqlInsert = "INSERT INTO emp_schedule_change (empid,weeknumber,changenumber,updatedate)
        VALUES(:empid,:weeknumber,:changenumber,now())";
        $statement = $db->prepare($sqlInsert);
        $statement->execute(array(':empid' => $empid,':weeknumber' => $weeknumber, ':changenumber' => $changenumber));

        if($statement->rowCount() == 0){
            $result = "<ul style='color: red;'>Unable to create emp_schedule_change</ul>";
        }else{
            //if login successful
             $result = "<ul style='color: green;'>Added emp_schedule_change, please wait for manager approval</ul>";
            
        }
     }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
        }
}
} //end of update vs insert
} 
} //end of request button
?>



<div class="container">
<section class="col col-lg-7">
<h2>Schedule Change Request Form</h2><hr>
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
  <input type="text" name="username" class="form-control" id="usernameField" readonly value="<?php if(isset($username)) echo $username; ?>">
  </div>
   <div class="form-group">
  <label for="firstnameField">First Name</label>
  <input type="text" name="firstname" class="form-control" id="firstnameField" readonly value="<?php if(isset($firstname)) echo $firstname; ?>">
  </div>
   <div class="form-group">
  <label for="lastnameField">Last Name</label>
  <input type="text" name="lastname" class="form-control" id="lastnameField" readonly value="<?php if(isset($lastname)) echo $lastname; ?>">
  </div>
  <div class="form-group">
  <label for="defaultGroupField">Default Group</label>
  <input type="text" name="defaultgroup" class="form-control" id="defaultgroupField" readonly value="<?php if(isset($defaultgroup)) echo $defaultgroup; ?>">
  </div>
  <div class="form-group">
  <label for="weekField">Week Starting</label>
  <div class="selectContainer">
      <select class="form-control" name="startweek">
          <option value="">Choose a week</option>
          <option value="<?php $dateval=date("W",strtotime($nextsunday)); echo $dateval; ?>">
          <?php echo "Week starting: ". $nextsunday ?></option>
          <option value="<?php echo date("W",strtotime($secondsunday)) ?>">
          <?php echo "Week starting: ". $secondsunday ?></option>
          <option value="<?php echo date("W",strtotime($thirdsunday)) ?>">
          <?php echo "Week starting: ". $thirdsunday ?>
          </option>
     </select>
  </div>
  </div>
  <div class="form-group">
  <label for="changeGroupField">Requested Group Change</label>
  <div class="selectContainer">
      <select class="form-control" name="changegroup">
          <option value="">Choose a group</option>
          <option value="0">Vacation</option>
          <option value="1">Group 1 - Tue and Fri off; Sat-Sun 11AM-3PM, Mon,Wed,Thur 7PM-11PM</option>
          <option value="2">Group 2 - Mon and Thu off; Tue-Wed,Fri-Sun 3PM-7PM</option>
          <option value="3">Group 3 - Sun and Wed off; Mon and Thu 3PM-7PM, Tue and Fri-Sat 7PM-11PM</option>
      </select>
  </div>
  </div>

 <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
 <button type="submit" name="SubmitChangeRequestBtn" class="btn btn-primary pull-right">Submit Change Request</button>
 </form>
  <?php endif ?>
  </section>
<p></p>
<p></p>
<p></p>
<br>
 <?php if($_SESSION['manager'] == 1): ?>
  <p><a href="schedulemgr.php">Back</a></p>
  <?php else: ?>
  <p><a href="empschedulemgr.php">Back</a></p>
  <?php endif ?>

</div>

<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>


