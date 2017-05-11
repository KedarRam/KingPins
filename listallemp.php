<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>List Employee Profile</title>
</head>
<body>
<?php
$page_title = "Welcome to KingPins FEC - List of Employees";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';
?>

<div class="container">
<div>
<br>
<br>

<h2>Employee List</h2>

 
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> You are not authorized to view this page <a href="login.php">Login</a></p>
<?php else: ?>
<section class="col col-md-15">
<p class="text-right"><a href="searchemp.php">Back</a></p>
<p> <button class="btn btn-primary pull-right"  onclick="myFunction()"><span class="glyphicon glyphicon-print"></span>  Print this page </button></p>
<table class="table table-bordered table-condensed">
<tr><td><mark>EMP ID</mark></td><td><mark>USERNAME</mark></td><td><mark>FIRSTNAME</mark></td><td><mark>LASTNAME</mark></td><td><mark>E-MAIL</mark></td><td><mark>PRIMARY PHONE</mark></td><td><mark>DEFAULT GROUP</mark></td><td><mark>STATUS</mark></td><td><mark>TITLE</mark></td><td><mark>JOIN DATE</mark></td><td><mark>LAST PROFILE UPDATE DATE</mark></td></tr>
<?php
$sqlQuery = "SELECT * FROM employee";
        $statement = $db->prepare($sqlQuery);
        $statement->execute();
         $count =$statement->rowCount();
         
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i=0; 
        while ($i < $count) {
            $id=$row[$i]['id'];
            $username=$row[$i]['username'];
            $firstname=strtoupper($row[$i]['firstname']);
            $lastname=strtoupper($row[$i]['lastname']);
            $email=$row[$i]['email'];
            $phone=$row[$i]['phone'];
	    $defaultgroup=$row[$i]['defaultgroup'];
            if($row[$i]['isActive'] == 1){$active="1 - ACTIVE";}else{$active="0 - NOT ACTIVE";};
            if($row[$i]['isManager'] == 1){$manager="MGR";}else{$manager="EMP";};
            $joindate=strftime("%b %d, %Y %H:%M", strtotime($row[$i]['joindate']));
            $updatedate=strftime("%b %d, %Y %H:%M", strtotime($row[$i]['updatedate']));
            ?>
<tr><td><?php echo $id?></td><td><?php echo $username?></td><td><?php echo $firstname?></td><td><?php echo $lastname?></td><td><?php echo $email?></td><td><?php echo $phone?></td><td><?php echo $defaultgroup ?></td><td><?php echo $active?></td><td><?php echo $manager ?></td><td><?php echo $joindate?></td><td><?php echo $updatedate?></td></tr>
        <?php $i++; } ?>


<script>
function myFunction(){
    window.print();
}
</script>
</table>
</section>

<?php endif ?>
<p> <button class="btn btn-primary pull-right"  onclick="myFunction()"><span class="glyphicon glyphicon-print"></span>  Print this page </button></p>
</div>
</div>


<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>
