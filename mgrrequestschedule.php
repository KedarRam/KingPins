<!--
    Name: mgrrequestschedule.php 
    Author: Kedar Ram
    Date: 2017-01-10

    Purpose: manager makes a schedule change request for employee 

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Employee Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

//Get next three week starting Sundays
$dow=date("w"); 
if ($dow != 0){ 
 $nextsunday=date("Y-m-d",strtotime('next Sunday'));
}else{
 $nextsunday=date("Y-m-d");
} 
$secondsunday=date("Y-m-d",strtotime('next Sunday',strtotime($nextsunday)));
$thirdsunday=date("Y-m-d",strtotime('next Sunday',strtotime($secondsunday))); 

//dropdown list

try{
$sqlFNQuery = "SELECT firstname, lastname, id, defaultgroup FROM employee";
$statement = $db->prepare($sqlFNQuery);
$statement->execute(array());
}catch (PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
    }

//insert into emp_schedule_change

if(isset($_POST['empid'])){
    //split by comma and get first value for empid
 
    $emparray=explode('.',$_POST['empid']);
    $empid=trim($emparray[1]);
    $id=$_SESSION['id'];
    $weeknumber=$_POST['startweek'];
    $changenumber=$_POST['changegroup'];
    $defaultgroup=trim($emparray[5]);

 if ($defaultgroup == $changenumber){
     $result = "<ul style='color: red;'> No change in default group for week: $weeknumber</ul>";
 }else{
if(checkDuplicateempidforweeknumber($empid,$weeknumber,$db)){
     //update
       
            $result = "<ul style='color: red;'>Employee Request for $weeknumber already exists</ul>";
        

 }else{
try{
 $sqlInsert = "INSERT INTO emp_schedule_change (empid,weeknumber,changenumber,updatedate,approved, approvedby, manager_initiated)
        VALUES(:empid,:weeknumber,:changenumber,now(),1,:id,1)";
        $statement = $db->prepare($sqlInsert);
        $statement->execute(array(':empid' => $empid,':weeknumber' => $weeknumber, ':changenumber' => $changenumber, ':id' => $id));
       
        if($statement->rowCount() == 0){
            $result = "<ul style='color: red;'>Unable to create emp_schedule_change</ul>";
        }else{
                   //if login successful
                   $result = "<ul style='color: green;'>Added approved emp_schedule_change</ul>";
                    header("Refresh:1");
                    }
}catch (PDOException $ex){
        $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
    }
}
}
}

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
  <label for="nameField">Employee Info (EMPID, FirstName, LastName, DefaultGroup)</label>
  <select id="empid" name="empid">
  <?php while($row = $statement->fetch()){ 
      $name = $row['firstname'] . "," . $row['lastname'];
      $id = $row['id'];
      $defaultgroup = $row['defaultgroup'];
      $val = $id . "," . strtoupper($name) . "," . $defaultgroup;
      echo "<option value=' . $id . " , " . $name . " , " . $defaultgroup .'>" . $val ."</option>";
  }
  ?>
  </select>
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
 <button type="submit" name="SubmitMgrChangeRequestBtn" class="btn btn-primary pull-right">Submit Change Request</button>
 </form>
  <?php endif ?>
  </section>
<p></p>
<p></p>
<p></p>
<br>
<p><a href="schedulemgr.php">Back</a></p>
</div>

<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>