<!--
    Name: approveschedule.php 
    Author: Kedar Ram
    Date: 2016-12-28

    Purpose: Schedule approval by Manager

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Employee Manager";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';
?>
<br>
<br>
<div class="container">
<section class="col col-lg-15"> 
<h2 align="center">Unappoved Employee Group Change Request List</h2>
<p align="right"><a href="schedulemgr.php">Back</a></p>



<?php
//security
$fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
$_SESSION['last_active'] = time();
$_SESSION['fingerprint'] = $fingerprint;

//Bulk All Schedule change request approval
if(isset($_POST['AllApproveBtn'])){
    $id=$_SESSION['id'];
    //call utility to update table
    $ret=AllApprove($id,$db);
    if($ret){
         $result="<ul style='color: green;'> All change requests approved</ul>";
    }else{
         $result="<ul style='color: red;'> Error Approving all change requests, retry</ul>";
    }
    if(isset($result)) echo $result;
}else{

    //list all schedule change request

    $sqlQuery = "SELECT id, firstname, lastname, weeknumber, defaultgroup, changenumber from emp_schedule_change c, employee e WHERE approved is NULL and delete_indicator != 1 and e.id=c.empid order by weeknumber, id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array());

    $count = $statement->rowCount();

    //if pending requests
    if ($count > 0){
    ?>
    
<!-- Display header -->       
<h3 align="center"> Total Count for Appoval: <?php echo $count ?></h3>
 <form method="post" action="">
    <button type="submit" name="AllApproveBtn" class="btn btn-primary pull-right">Approve All</button>
        <table align="center" class="table table-bordered table-condensed">
            <tr><td><mark>EMPLOYEE ID</mark></td>
            <td><mark>EMPLOYEE NAME</mark></td>
            <td><mark>WEEK ID</mark></td>
            <td><mark>WEEK START DATE</mark></td>
            <td><mark>DEFAULT GROUP</mark></td>
            <td><mark>CHANGE REQUEST</mark></td>
            <td><mark>STATUS</mark></td>

<?php
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i=0; 
        while ($i < $count) {
            $empid=$row[$i]['id'];
            $firstname=strtoupper($row[$i]['firstname']);
            $lastname=strtoupper($row[$i]['lastname']);
            $weeknumber=$row[$i]['weeknumber'];
            $defaultgroup=$row[$i]['defaultgroup'];
            $changegroup=$row[$i]['changenumber'];

            //convert weeknumber of the year into display date  
            $year=date("Y");
            if($weeknumber < 10){
                $yweek=$year."W0".$weeknumber;
            }else{
                $yweek=$year."W".$weeknumber;
            }
           $weekdate=date('Y-m-d',strtotime($yweek));
          
                  
    ?>
    <!--display data one row at a time -->
           <tr><td><?php echo $empid ?></td>
           <td><?php echo $firstname . "," . $lastname ?></td>
           <td><?php echo $weeknumber ?></td>
           <td><?php echo $weekdate ?></td>
           <td><?php echo $defaultgroup ?></td>
           <td><?php echo $changegroup ?></td>
           <td><button type="submit" name="ApproveBtn" value="<?php echo $empid ."," .$weeknumber ?>" class="btn btn-primary pull-center">Approve</button></td></tr>

<?php
    $i++; 
    } //end of while loop 
        
    } //end of count greater than 0 loop
if(isset($result)) echo $result;
?>

</form>
</section>
</div>
<br>

<?php 
} // end of approve 
?>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>