<!--
    Name: createcust.php 
    Author: Kedar Ram
    Date: 2016-12-26

    Purpose: Create Customer Menu

-->
<!-- common header -->
<?php
include_once "common/database.php";
include_once "common/utilities.php";
include_once 'headerfooter/header.php';

    //update active session_status
    $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     $_SESSION['last_active'] = time();
     $_SESSION['fingerprint'] = $fingerprint;

// set default timezone to pick current date time 
//this is also set in the header (repeat)
date_default_timezone_set('America/New_York');
$currentdatetime=date("Y-m-d H:i");

if(isset($_POST['createCustBtn'])){
    //create array to store errors
    $form_errors=array();
    $required_fields = array('custfirstname','custlastname','custphone');

    //check for required fields
    foreach ($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
        }
    }

    //validate data 
    
    //firstname and lastname should contain only alphabets
        if(!preg_match("/^[a-zA-Z ]*$/",trim($_POST['custfirstname']))){
            $form_errors[] = "firstname should only be lower and upper case alphabets";
        }
         if(!preg_match("/^[a-zA-Z ]*$/",trim($_POST['custlastname']))){
            $form_errors[] = "lastname should only be lower and upper case alphabets";
        }

    //phone should have right format
    if(strlen(trim($_POST['custphone'])) < 12){
        $form_errors[] = "phone numer with hypens should be 12 characters long";
    }else{
        if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",trim($_POST['custphone']))){
            $form_errors[] = "phone numer should be in xxx-xxx-xxxx format";
        }
    }

    //collect values
    $custfirstname=strtoupper(trim($_POST['custfirstname']));
    $custlastname=strtoupper(trim($_POST['custlastname']));
    $custphone=trim($_POST['custphone']);
    $custgroupcount=trim($_POST['custgroupcount']);
    $custenter=trim($_POST['custenterdate']);
    $enter_empid=$_SESSION['id'];


    //check for duplicate username or email
    if(checkDuplicatePhone($custphone,$db)){
        $result = "<ul style='color: red;'>Phone is already in use</ul>";
    }elseif(empty($form_errors)){

        try{
        $sqlInsert = "INSERT INTO customer (custphone,custfirstname,custlastname,custgroupcount,custenter,enter_empid)
        VALUES(:custphone,:custfirstname,:custlastname,:custgroupcount,:custenter,:enter_empid)";
        $statement = $db->prepare($sqlInsert);
        $statement->execute(array(':custphone' => $custphone,':custfirstname' => $custfirstname,':custlastname' => $custlastname, ':custgroupcount' => $custgroupcount, ':custenter' => $custenter,  ':enter_empid' => $enter_empid));

        if($statement->rowCount() == 0){
            $result = "<ul style='color: red;'>Unable to create customer</ul>";
        }else{
                   //if login successful
                   $result = "<ul style='color: green;'>Created customer</ul>";
                   // header("location:index.php");
         }
        }catch(PDOException $ex){
            $result = "<ul style='color: red;'>Error occurred:" . $ex->getMessage(). "</ul>";
        }

    }else{
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
}

?>

<!-- Form begin -->
<h2> Create Customer</h2>
<?php if(isset($result)) echo $result; ?>
<div class="container">
<section class="col col-lg-7">

  <div class="clearfix"></div>

  <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">You are not authorized to view this page <a href="login.php">Login</a>
  <?php else: ?>
  <form method="post" action="">
  <div class="form-group">
  <div class="form-group">
  <label for="phoneField">Customer Phone</label>
  <input type="text" name="custphone" class="form-control" id="phoneField" value="<?php if(isset($custphone)) echo $custphone; ?>">
  </div>
   <div class="form-group">
  <label for="firstnameField">Customer First Name</label>
  <input type="text" name="custfirstname" class="form-control" id="firstnameField"  value="<?php if(isset($custfirstname)) echo $custfirstname; ?>">
  </div>
   <div class="form-group">
  <label for="lastnameField">Customer Last Name</label>
  <input type="text" name="custlastname" class="form-control" id="lastnameField" value="<?php if(isset($custlastname)) echo $custlastname; ?>">
  </div>
  <div class="form-group">
  <label for="lastnameField">Customer Group Count (Number of people in customer's party)</label>
  <input type="text" name="custgroupcount" class="form-control" id="custgroupcountField" value="<?php if(isset($custgroupcount)) echo $custgroupcount; ?>">
  </div>
   <div class="form-group">
  <label for="custenterField">Customer Enter DateTime</label>
  <input type="text" name="custenterdate" class="form-control" id="custenterField" readonly value="<?php if(isset($currentdatetime)) echo $currentdatetime; ?>">
  </div>
 <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
 <button type="submit" name="createCustBtn" class="btn btn-primary pull-right">Create Customer</button>
 </form>
  <?php endif ?>
  </section>
  <!-- Back -->
  <p><a href="customer.php">Back</a></p>
  </div>

  <!-- common header -->
<?php
include_once 'headerfooter/footer.php';
?>
