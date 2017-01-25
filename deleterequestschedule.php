<!--
    Name: deleterequestschedule.php 
    Author: Kedar Ram
    Date: 2017-01-10

    Purpose: If an employee or manager wants to delete an approved or unapproved schedule change request

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

//code starts here to collect data for display
if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['DeleteChangeRequestBtn'])){
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
}elseif(isset($_POST['DeleteChangeRequestBtn'])){
 
 
//collect data to update database
 $empid=$_SESSION['id'];
 $username=$_POST['username'];
 $firstname=$_POST['firstname'];
 $lastname=$_POST['lastname'];
 $defaultgroup=$_POST['defaultgroup'];
 $weeknumber=intval(trim($_POST['startweek']));
 
 if(checkDuplicateempidforweeknumber($empid,$weeknumber,$db)){

       try{
       $sqlUpdate = "UPDATE emp_schedule_change SET delete_indicator=1 WHERE empid =:empid and weeknumber =:weeknumber";
                                        
        $statement = $db->prepare($sqlUpdate);
        $statement->execute(array(':empid' => $empid,':weeknumber' => $weeknumber));

        if($statement->rowCount() == 1){
            $result = "<ul style='color: green;'>Marked Change Request as Deleted</ul>";
        }else{
            $result = "<ul style='color: red;'>Unable to delete requested schedule change</ul>";
        }
       }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
        }

 
}else{
    $result = "<ul style='color: red;'>No schedule change request to delete</ul>";
}
}
?>


<!--Form begins here -->
<div class="container">
<section class="col col-lg-7">
<h2>Delete Submitted Schedule Change Request</h2><hr>
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
  
 <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
 <button type="submit" name="DeleteChangeRequestBtn" class="btn btn-primary pull-right">Delete Change Request</button>
 </form>
  <?php endif ?>
  </section>
<p></p>
<p></p>
<p></p>
<br>
<!--if manager go to mgrmain else empmain -->
  <?php if ($_SESSION['manager'] == 1): ?>
  <p><a href="schedulemgr.php">Back</a></p>
  <?php else: ?>
  <p><a href="empschedulemgr.php">Back</a></p>
  <?php endif ?>
</div>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>