<!-- Name: reportcust.php 
    Author: Kedar Ram
    Date: 2017-01-07

    Purpose: Customer Report Input Form
    partialReportCust.php contains report generation code

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Customer Report";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';
include_once 'partials/partialReportCust.php';
?>


<br>
<br>
<h2>Customer Reporting</h2>
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> You are not authorized to view this page <a href="login.php">Login</a></p>
<?php else: ?>
<?php if(isset($result)) echo $result; ?>
<section class="col col-lg-7">
 <form action="" method="post">
  <div class="form-group has-feedback">
  <label for="datefieldField">Report Date</label>
  <input type="date" name="reportdate" class="form-control" id="datepicker" /> <span class="glyphicon glyphicon-calendar form-control-feedback "></span>
  </div>
  <div class="form-group has-feedback">
  <label for="starttimefieldField">Report Start Time (HH:MM AM/PM)</label>
  <input type="time" name="reportstarttime" class="form-control" id="StartTime" placeholder="HH:mm AM/PM" /> <span class="glyphicon-time glyphicon form-control-feedback "></span>
  </div>
  <div class="form-group has-feedback">
  <label for="starttimefieldField">Report End Time (HH:MM AM/PM)</label>
  <input type="time" name="reportendtime" class="form-control" id="EndTime" placeholder="HH:mm AM/PM"/> <span class="glyphicon-time glyphicon form-control-feedback"></span></input>
  </div>
  <div class="checkbox">
  <label><input type="checkbox" name="includeactive" value="">Include active customers</label>
</div>
 <button type="submit" name="reportBtn" class="btn btn-primary pull-right">Prepare Report</button>
 </form>
 </section>
  <?php endif ?>
<!-- common footer -->
<?php
include_once 'headerfooter/footer.php';
?>