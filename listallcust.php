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

<!-- print javascript -->
<script>
function myFunction(){
    window.print();
}
</script>


<div class="container">
<div>
<br>
<br>

<h2>Customer List</h2>

 
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> You are not authorized to view this page <a href="login.php">Login</a></p>
<?php else: ?>
<section class="col col-md-15">
<p class="text-right"><a href="searchcust.php">Back</a></p>
<p> <button class="bttn btn-primary pull-right"  onclick="myFunction()"><span class="glyphicon glyphicon-print"></span>  Print this page </button></p>
<table class="table table-bordered table-condensed">
<tr><td><mark>CUSTOMER PHONE</mark></td><td><mark>FIRSTNAME</mark></td><td><mark>LASTNAME</mark></td><td>CUST GROUP COUNT</td><td><mark>ENTER DATETIME</mark></td><td><mark>ENTERED BY EMPLOYEE</mark></td></tr>
<?php
$sqlQuery = "SELECT * FROM customer WHERE custexit is NULL"; 
        $statement = $db->prepare($sqlQuery);
        $statement->execute();
         $count =$statement->rowCount();
         
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i=0; 
        while ($i < $count) {
            $custphone=$row[$i]['custphone'];
            $custfirstname=strtoupper($row[$i]['custfirstname']);
            $custlastname=strtoupper($row[$i]['custlastname']);
            $custgroupcount=$row[$i]['custgroupcount'];
            $custenter=$row[$i]['custenter'];
            $enter_empid=$row[$i]['enter_empid'];
            $custexit=$row[$i]['custexit'];
            $exit_empid=$row[$i]['exit_empid'];
            $id=$_SESSION['id'];
            ?>
<tr><td><?php echo $custphone?></td><td><?php echo $custfirstname?></td><td><?php echo $custlastname?></td><td><?php echo $custgroupcount ?></td><td><?php echo $custenter?></td><td><?php echo $enter_empid?></td></tr>

        <?php $i++; } ?>




</table>
</section>

<?php endif ?>
<p> <button class="bttn btn-primary pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print"></span>  Print this page </button></p>
</div>
</div>


<?php
include_once 'headerfooter/footer.php';
?>
</body>
</html>
