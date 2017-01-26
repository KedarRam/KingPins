<!-- Name: partialReportCust.php 
    Author: Kedar Ram
    Date: 2017-01-07

    Purpose: Customer Report

-->
<!-- common header -->
<?php
$page_title="Welcome to KingPins FEC - Customer Report";
include_once 'headerfooter/header.php';
include_once 'common/database.php';
include_once 'common/utilities.php';

//process begin
if(isset($_POST['reportBtn'])){

        //security
        $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;

        //check for input values - required fields, atleast one
        if((strlen($_POST['reportdate'])==0) && (strlen($_POST['reportstarttime'])==0) && (strlen($_POST['reportendtime'])==0)) {
            $result ="<ul style='color: red;'>Please provide input values.</ul>";
        }else{


          //if date picked 
        if (strlen($_POST['reportdate'])>0){
            //collect data 
            $reportdate=$_POST['reportdate'];
            date_default_timezone_set('America/New_York');
            $currentdate=date("Y-m-d");
            //check if current date
            if($reportdate == $currentdate) {
                //check for include customers in facility
                if(isset($_POST['includeactive'])){
                    $includeactivetoday = 1;
                }else{
                    $includeactivetoday = 0;
                }
            }else{ $includeactivetoday = 0;}
            //check if start time entered, else default
            if(strlen($_POST['reportstarttime'])>0){
                 $starttime=$_POST['reportstarttime'];
            }else{
                $starttime="00:00";
            }
            //check if end time entered, else default
            if(strlen($_POST['reportendtime'])>0){
                 $endtime=$_POST['reportendtime'];
            }else{
                $endtime="23:59";
            }

        }else{
            //pick today
            date_default_timezone_set('America/New_York');
            $reportdate=date("Y-m-d");
         
            //check if includeactive
            if(isset($_POST['includeactive'])){
                    $includeactivetoday = 1;
            }else{
                    $includeactivetoday = 0;
            }
            //check if start time entered, else default
            if(strlen($_POST['reportstarttime'])>0){
                 $starttime=$_POST['reportstarttime'];
            }else{
                $starttime="00:00";
            }
            //check if end time entered, else default
            if(strlen($_POST['reportendtime'])>0){
                 $endtime=$_POST['reportendtime'];
            }else{
                $endtime="23:59";
            }
        }
        //combine date and time - data in db is datetime
        $startdatetime=$reportdate." ".$starttime;
        $enddatetime=$reportdate." ".$endtime;

        //setup report output file write header
        $reportfile = fopen("reportfile.html", "w") or die("Unable to open file!");
            $txt = "<html>\n";
             fwrite($reportfile, $txt);
             $txt = "<head>\n";
             fwrite($reportfile, $txt);
             $txt="<h2 align=\"center\"> Customer Report for Date:  $reportdate Start Time  $starttime End Time  $endtime</h2>";
               fwrite($reportfile, $txt);
               $txt = "</head>\n";
             fwrite($reportfile, $txt);
             
             $txt = "<body>\n";
             fwrite($reportfile, $txt);
             $txt = "<table align=\"center\" class=\"table table-bordered table-condensed\">";
            fwrite($reportfile, $txt);
            $txt = "<tr><td><mark>CUSTOMER PHONE</mark></td>";
            fwrite($reportfile, $txt);
            $txt ="<td><mark>FIRSTNAME</mark></td>";
             fwrite($reportfile, $txt);
            $txt = "<td><mark>LASTNAME</mark></td>";
            fwrite($reportfile, $txt);
            $txt = "<td><mark>GROUP COUNT</mark></td>";
             fwrite($reportfile, $txt);
            $txt = "<td><mark>ENTER DATETIME</mark></td>";
             fwrite($reportfile, $txt);
            $txt = "<td><mark>ENTERED BY EMPLOYEE</mark></td>";
             fwrite($reportfile, $txt);
            $txt = "<td><mark>EXIT DATETIME</mark></td>";
             fwrite($reportfile, $txt);
            $txt = "<td><mark>EXIT BY EMPLOYEE</mark></td></tr>";
             fwrite($reportfile, $txt);

        //Query db based on input
        $sqlQuery = "SELECT * from customer WHERE custenter >=:custenter and custexit <=:custexit order by custenter";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':custenter' => $startdatetime, ':custexit' => $enddatetime));

         $count =$statement->rowCount();
            //Write 

             $txt="<style>table, th, td {    border: 1px solid black;    border-collapse: collapse;}</style>";
             fwrite($reportfile, $txt);
             $txt = "</head>\n";
             $totalcustgroup1 = $totalcustgroup2 = $totalcount = 0;
         if ($count > 0){ 

   
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i=0; 
        $cg=0;
        while ($i < $count) {
            $custphone=$row[$i]['custphone'];
            $custfirstname=strtoupper($row[$i]['custfirstname']);
            $custlastname=strtoupper($row[$i]['custlastname']);
            $custgroupcount=$row[$i]['custgroupcount'];
            $custenter=$row[$i]['custenter'];
            $enter_empid=$row[$i]['enter_empid'];
            $custexit=$row[$i]['custexit'];
            $exit_empid=$row[$i]['exit_empid'];
           
            $txt = "<tr><td>$custphone</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custfirstname</td>";
             fwrite($reportfile, $txt);
            $txt ="<td> $custlastname</td>";
             fwrite($reportfile, $txt);
              $txt ="<td> $custgroupcount</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custenter</td>";
             fwrite($reportfile, $txt);
            $txt ="<td> $enter_empid</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custexit</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$exit_empid</td></tr>";
             fwrite($reportfile, $txt);
      
   $cg = $cg + $custgroupcount;
        $i++; } 
        $totalcustgroup1=$cg;
        $txt="<h3 align=\"center\"> Total Exit Customers:  $totalcustgroup1 </h3>";
        fwrite($reportfile, $txt);
         }
        // include active customers in exist
        if($includeactivetoday == 1){
           
      
                $sqlQuery = "SELECT * from customer WHERE custenter >=:custenter and custexit is NULL order by custenter";
                 $statement = $db->prepare($sqlQuery);
                $statement->execute(array(':custenter' => $startdatetime));
                 $count2 =$statement->rowCount();
                 $row = $statement->fetchAll(PDO::FETCH_ASSOC);
                
        $i=0; 
        $cg=0;
        while ($i < $count2) {
            $custphone=$row[$i]['custphone'];
            $custfirstname=strtoupper($row[$i]['custfirstname']);
            $custlastname=strtoupper($row[$i]['custlastname']);
            $custgroupcount=$row[$i]['custgroupcount'];
            $custenter=$row[$i]['custenter'];
            $enter_empid=$row[$i]['enter_empid'];
            $custexit="";
            $exit_empid="";
           
            $txt = "<tr><td>$custphone</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custfirstname</td>";
             fwrite($reportfile, $txt);
            $txt ="<td> $custlastname</td>";
             fwrite($reportfile, $txt);
             $txt ="<td> $custgroupcount</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custenter</td>";
             fwrite($reportfile, $txt);
            $txt ="<td> $enter_empid</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$custexit</td>";
             fwrite($reportfile, $txt);
            $txt ="<td>$exit_empid</td></tr>";
             fwrite($reportfile, $txt);
      
             $cg = $cg + intval($custgroupcount);
            
        $i++; } 
            $totalcustgroup2=$cg;
            
            $txt="<h3 align=\"center\"> Total Active Customers:  $totalcustgroup2 </h3>";
            fwrite($reportfile, $txt);
            $totalcount=$totalcustgroup1 + $totalcustgroup2;
            $txt="<h3 align=\"center\"> Total  Customers:  $totalcount </h3>";
            fwrite($reportfile, $txt);
            }

        $txt="</table>";
         fwrite($reportfile, $txt);
                 $txt="</body>";
         fwrite($reportfile, $txt);
  
         }     
        $txt="</html>";
         fwrite($reportfile, $txt);

fclose($reportfile); 
//header("Refresh:0, url=reportfile.html");
?>

     <script> location.replace("reportfile.html"); </script>
<?php
}
?>