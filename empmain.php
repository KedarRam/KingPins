<!--
    Name: empmain.php 
    Author: Kedar Ram
    Date: 2016-12-02

    Purpose: Employee Home page

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Home Page";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

ini_set("highlight.string", "#FFFF00");

    //security
    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;

    //current SUNDAY
date_default_timezone_set('America/New_York');   
$dow=date("w"); 
if ($dow != 0){ 
 $csunday=date('Y-m-d',strtotime('last sunday'));
}else{
 $csunday=date("Y-m-d");
} 


$cMonday=date("Y-m-d",strtotime('+1 day', strtotime($csunday)));
$cTuesday=date("Y-m-d",strtotime('+2 day', strtotime($csunday)));
$cWednesday=date("Y-m-d",strtotime('+3 day', strtotime($csunday)));
$cThursday=date("Y-m-d",strtotime('+4 day', strtotime($csunday)));
$cFriday=date("Y-m-d",strtotime('+5 day', strtotime($csunday)));
$cSaturday=date("Y-m-d",strtotime('+6 day', strtotime($csunday)));

$sgroup=getgroup($db,"Sunday");
$groups=explode(',',$sgroup);
$mgroup=getgroup($db,"Monday");
$groupm=explode(',',$mgroup);
$tgroup=getgroup($db,"Tuesday");
$groupt=explode(',',$tgroup);
$wgroup=getgroup($db,"Wednesday");
$groupw=explode(',',$wgroup);
$thgroup=getgroup($db,"Thursday");
$groupth=explode(',',$thgroup);
$fgroup=getgroup($db,"Friday");
$groupf=explode(',',$fgroup);
$sagroup=getgroup($db,"Saturday");
$groupsa=explode(',',$sagroup);



$weeknumber=intval(date("W"));
$nextweeknumber=$weeknumber + 1;
$hgroup=highlightgroup($db,$_SESSION['id'],$weeknumber);

$defaultgroup=getdefaultgroup($db,$_SESSION['id']);
$changegroup=getchangegroup($db,$_SESSION['id'],$weeknumber);

if ($changegroup == ""){
    $changegroup="No request";
}

$cdefaultgroup=getdefaultgroup($db,$_SESSION['id']);
$cchangegroup=getchangegroup($db,$_SESSION['id'],$nextweeknumber);

if ($cchangegroup == ""){
    $cchangegroup="No request";
}



?>

<div class="container">
<div class="flag">
<h1>Welcome to KingPins FEC</h1>
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> Please <a href="login.php">Login</a></p>
<?php else: ?>
<!-- <p class="lead">You are logged in as <b> <?php if(isset($_SESSION['username'])) echo $_SESSION['username'] ?> </b> <a href="logout.php"</a></p>
-->
<!-- display the users schedule for current and next week -->
<h2> Schedule </h2>
<h3><font color ="blue"> Current Week Default Group: <?php echo $defaultgroup ?>  Change Group: <?php echo $changegroup?></font></h3>
<h3> Next Week Default Group: <?php echo $cdefaultgroup ?>  Change Group: <?php echo $cchangegroup?></h3>
<div class="container-schedule">
    <table class="table table-bordered table-condensed">
    <tr>
        <td><strong>GROUP</td>
        <td>SUNDAY<br><?php echo $csunday ?></td>
        <td>MONDAY<br><?php echo $cMonday ?></td>
        <td>TUESDAY<br><?php echo $cTuesday ?></td>
        <td>WEDNESDAY<br><?php echo $cWednesday ?></td>
        <td>THURSDAY<br><?php echo $cThursday ?></td>
        <td>FRIDAY<br><?php echo $cFriday ?></td>
        <td>SATURDAY<br><?php echo $cSaturday ?></td></strong>
    </tr>
    <tr>
        <td><strong>SHIFT1<br>11AM-3PM</strong></td>
        <td><?php if($hgroup == $groups[0]){ $htext=highlightText($groups[0]);echo $htext;}else{ echo $groups[0];} ?></td>
        <td><?php if($hgroup == $groupm[0]){ $htext=highlightText($groupm[0]);echo $htext;}else{ echo $groupm[0];} ?></td>
        <td><?php if($hgroup == $groupt[0]){ $htext=highlightText($groupt[0]);echo $htext;}else{ echo $groupt[0];} ?></td>
        <td><?php if($hgroup == $groupw[0]){ $htext=highlightText($groupw[0]);echo $htext;}else{ echo $groupw[0];} ?></td>
        <td><?php if($hgroup == $groupth[0]){ $htext=highlightText($groupth[0]);echo $htext;}else{ echo $groupth[0];} ?></td>
        <td><?php if($hgroup == $groupf[0]){ $htext=highlightText($groupf[0]);echo $htext;}else{ echo $groupf[0];} ?></td>
        <td><?php if($hgroup == $groupsa[0]){$htext=highlightText($groupsa[0]);echo $htext;}else{ echo $groupsa[0];} ?></td>
    </tr>
    <tr>
        <td><strong>SHIFT2<br>3PM-7PM</strong></td>
        <td><?php if($hgroup == $groups[1]){ $htext=highlightText($groups[1]);echo $htext;}else{ echo $groups[1];} ?></td>
        <td><?php if($hgroup == $groupm[1]){ $htext=highlightText($groupm[1]);echo $htext;}else{ echo $groupm[1];} ?></td>
        <td><?php if($hgroup == $groupt[1]){ $htext=highlightText($groupt[1]);echo $htext;}else{ echo $groupt[1];} ?></td>
        <td><?php if($hgroup == $groupw[1]){ $htext=highlightText($groupw[1]);echo $htext;}else{ echo $groupw[1];} ?></td>
        <td><?php if($hgroup == $groupth[1]){ $htext=highlightText($groupth[1]);echo $htext;}else{ echo $groupth[1];} ?></td>
        <td><?php if($hgroup == $groupf[1]){ $htext=highlightText($groupf[1]);echo $htext;}else{ echo $groupf[1];} ?></td>
        <td><?php if($hgroup == $groupsa[1]){ $htext=highlightText($groupsa[1]);echo $htext;}else{ echo $groupsa[1];} ?></td>
        </tr>
    <tr>
        <td><strong>SHIFT3<br>7PM-11PM</strong></td>
       <td><?php if($hgroup == $groups[2]){ $htext=highlightText($groups[2]);echo $htext;}else{ echo $groups[2];} ?></td>
        <td><?php if($hgroup == $groupm[2]){ $htext=highlightText($groupm[2]);echo $htext;}else{ echo $groupm[2];} ?></td>
        <td><?php if($hgroup == $groupt[2]){ $htext=highlightText($groupt[2]);echo $htext;}else{ echo $groupt[2];} ?></td>
        <td><?php if($hgroup == $groupw[2]){ $htext=highlightText($groupw[2]);echo $htext;}else{ echo $groupw[2];} ?></td>
        <td><?php if($hgroup == $groupth[2]){ $htext=highlightText($groupth[2]);echo $htext;}else{ echo $groupth[2];} ?></td>
        <td><?php if($hgroup == $groupf[2]){ $htext=highlightText($groupf[2]);echo $htext;}else{ echo $groupf[2];} ?></td>
        <td><?php if($hgroup == $groupsa[2]){ $htext=highlightText($groupsa[2]);echo $htext;}else{ echo $groupsa[2];} ?></td>
    
    </tr>
    </table>
</div><br>

<?php endif ?>
</div>
</div>

<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>