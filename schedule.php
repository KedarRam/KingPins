<?php
$page_title = "Welcome to KingPins FEC - Scheduling";
include_once 'headerfooter/header.php';
include_once 'common/Database.php';
include_once 'common/utilities.php';

// List all employees with schedule change request for approval

//upcoming week schedule
$dow=date("w"); 
if ($dow != 0){ //find previous Sunday of the week}
 $sunday=date("Y-m-d",strtotime('last Sunday'));
 }else{
 $sunday=date("Y-m-d");    
}
$nextsunday=date('Y-m-d', strtotime($sunday .' +7 day'));  


//config table could hold min emp and mgr - but for this code it is defaulted to 5

//schedule table in db holds default schedule

//emp default count
//GROUP0:5,GROUP1:5,GROUP2:5,GROUP3:5
$default_emp_by_group=default_count_by_group($db,'emp');

$groupempcount=explode(',',$default_emp_by_group);
$groupemp0count = $groupemp1count = $groupemp2count = $groupemp3count = 0;
foreach($groupempcount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ $groupemp0count=$temparray[1];}
    elseif($temparray[0] == "GROUP1"){ $groupemp1count=$temparray[1];}
    elseif($temparray[0] == "GROUP2"){ $groupemp2count=$temparray[1];}
    elseif($temparray[0] == "GROUP3"){ $groupemp3count=$temparray[1];}
    
}
//mgr default count
//GROUP0:5,GROUP1:5,GROUP2:5,GROUP3:5
$default_mgr_by_group=default_count_by_group($db,'manager');

$groupmgrcount=explode(',',$default_mgr_by_group);

foreach($groupmgrcount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ $groupmgr0count=$temparray[1];}
    elseif($temparray[0] == "GROUP1"){ $groupmgr1count=$temparray[1];}
    elseif($temparray[0] == "GROUP2"){ $groupmgr2count=$temparray[1];}
    elseif($temparray[0] == "GROUP3"){ $groupmgr3count=$temparray[1];}
    
}

//unapproved schedule change requested count employee
//GROUP0:GROUP1:5,GROUP0:GROUP2:5,GROUP0:GROUP3:5,GROUP1:GROUP0:5 etc
$change_emp_by_group=change_unapproved_count_by_group($db,'emp',$nextsunday);
$groupempchangecount=explode(',',$change_emp_by_group);
$groupemp0to1count = $groupemp0to2count = $groupemp0to3count = 0;
$groupemp1to0count = $groupemp1to2count = $groupemp1to3count = 0;
$groupemp2to0count = $groupemp2to1count = $groupemp2to3count = 0;
$groupemp3to0count = $groupemp3to1count = $groupemp3to2count = 0;

foreach($groupempchangecount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ 
        if($temparray[1] == "GROUP1"){
            $groupemp0to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupemp0to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupemp0to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP1"){ 
        if($temparray[1] == "GROUP0"){
        $groupemp1to0count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupemp1to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupemp1to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP2"){
        if($temparray[1] == "GROUP0"){
         $groupemp2to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupemp2to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupemp2to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP3"){
          if($temparray[1] == "GROUP0"){
         $groupemp3to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupemp3to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupemp3to2count=$temparray[2];
        }
         
    }
}
$totalgroupemp0 = ( ($groupemp1to0count + $groupemp2to0count + $groupemp3to0count) - ($groupemp0to1count + $groupemp0to2count + $groupemp0to3count ));
$totalgroupemp1 = (($groupemp0to1count + $groupemp2to1count + $groupemp3to1count) - ($groupemp1to0count + $groupemp1to2count + $groupemp1to3count ));
$totalgroupemp2 = ( ($groupemp0to2count + $groupemp1to2count + $groupemp3to2count) - ($groupemp2to0count + $groupemp2to1count + $groupemp2to3count ));
$totalgroupemp3 = ( ($groupemp0to3count + $groupemp1to3count + $groupemp2to3count) - ($groupemp3to0count + $groupemp3to1count + $groupemp3to2count ));

//unapproved schedule change requested count manager
$change_umgr_by_group=change_unapproved_count_by_group($db,'manager',$nextsunday);
$groupumgrchangecount=explode(',',$change_umgr_by_group);
$groupumgr0to1count = $groupumgr0to2count = $groupumgr0to3count = 0;
$groupumgr1to0count = $groupumgr1to2count = $groupumgr1to3count = 0;
$groupumgr2to0count = $groupumgr2to1count = $groupumgr2to3count = 0;
$groupumgr3to0count = $groupumgr3to1count = $groupumgr3to2count = 0;

foreach($groupumgrchangecount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ 
        if($temparray[1] == "GROUP1"){
            $groupumgr0to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupumgr0to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupumgr0to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP1"){ 
        if($temparray[1] == "GROUP0"){
        $groupumgr1to0count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupumgr1to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupumgr1to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP2"){
        if($temparray[1] == "GROUP0"){
         $groupumgr2to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupumgr2to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupumgr2to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP3"){
          if($temparray[1] == "GROUP0"){
         $groupumgr3to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupumgr3to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupumgr3to2count=$temparray[2];
        }
         
    }
}
$totalgroupumgr0 = ( ($groupumgr1to0count + $groupumgr2to0count + $groupumgr3to0count) - ($groupumgr0to1count + $groupumgr0to2count + $groupumgr0to3count ));
$totalgroupumgr1 = ( ($groupumgr0to1count + $groupumgr2to1count + $groupumgr3to1count) - ($groupumgr1to0count + $groupumgr1to2count + $groupumgr1to3count ));
$totalgroupumgr2 = (($groupumgr0to2count + $groupumgr1to2count + $groupumgr3to2count) - ($groupumgr2to0count + $groupumgr2to1count + $groupumgr2to3count ));
$totalgroupumgr3 = ( ($groupumgr0to3count + $groupumgr1to3count + $groupumgr2to3count) - ($groupumgr3to0count + $groupumgr3to1count + $groupumgr3to2count ));

//approved schedule change requested count employee
$change_aemp_by_group=change_approved_count_by_group($db,'emp',$nextsunday);
$groupaempchangecount=explode(',',$change_aemp_by_group);
$groupaemp0to1count = $groupaemp0to2count = $groupaemp0to3count = 0;
$groupaemp1to0count = $groupaemp1to2count = $groupaemp1to3count = 0;
$groupaemp2to0count = $groupaemp2to1count = $groupaemp2to3count = 0;
$groupaemp3to0count = $groupaemp3to1count = $groupaemp3to2count = 0;

foreach($groupaempchangecount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ 
        if($temparray[1] == "GROUP1"){
            $groupaemp0to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupaemp0to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupaemp0to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP1"){ 
        if($temparray[1] == "GROUP0"){
        $groupaemp1to0count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupaemp1to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupaemp1to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP2"){
        if($temparray[1] == "GROUP0"){
         $groupaemp2to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupaemp2to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupaemp2to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP3"){
          if($temparray[1] == "GROUP0"){
         $groupaemp3to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupaemp3to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupaemp3to2count=$temparray[2];
        }
         
    }
}
$totalgroupaemp0 = ( ($groupaemp1to0count + $groupaemp2to0count + $groupaemp3to0count) - ($groupaemp0to1count + $groupaemp0to2count + $groupaemp0to3count ));
$totalgroupaemp1 = ( ($groupaemp0to1count + $groupaemp2to1count + $groupaemp3to1count) - ($groupaemp1to0count + $groupaemp1to2count + $groupaemp1to3count ));
$totalgroupaemp2 = ( ($groupaemp0to2count + $groupaemp1to2count + $groupaemp3to2count) - ($groupaemp2to0count + $groupaemp2to1count + $groupaemp2to3count ));
$totalgroupaemp3 = ( ($groupaemp0to3count + $groupaemp1to3count + $groupaemp2to3count) - ($groupaemp3to0count + $groupaemp3to1count + $groupaemp3to2count ));


//approved schedule change requested count manager
$change_amgr_by_group=change_approved_count_by_group($db,'manager',$nextsunday);
$groupamgrchangecount=explode(',',$change_amgr_by_group);
$groupamgr0to1count = $groupamgr0to2count = $groupamgr0to3count = 0;
$groupamgr1to0count = $groupamgr1to2count = $groupamgr1to3count = 0;
$groupamgr2to0count = $groupamgr2to1count = $groupamgr2to3count = 0;
$groupamgr3to0count = $groupamgr3to1count = $groupamgr3to2count = 0;

foreach($groupamgrchangecount as $myarray){
    $temparray=explode(':',$myarray);
    if($temparray[0] == "GROUP0"){ 
        if($temparray[1] == "GROUP1"){
            $groupamgr0to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupamgr0to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupamgr0to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP1"){ 
        if($temparray[1] == "GROUP0"){
        $groupamgr1to0count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupamgr1to2count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupamgr1to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP2"){
        if($temparray[1] == "GROUP0"){
         $groupamgr2to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupamgr2to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP3"){
            $groupamgr2to3count=$temparray[2];
        }
    }
    elseif($temparray[0] == "GROUP3"){
          if($temparray[1] == "GROUP0"){
         $groupamgr3to0count=$temparray[2];
         }elseif($temparray[1] == "GROUP1"){
            $groupamgr3to1count=$temparray[2];
        }elseif($temparray[1] == "GROUP2"){
            $groupamgr3to2count=$temparray[2];
        }
         
    }
}
$totalgroupamgr0 = ($groupemp0count + ($groupamgr1to0count + $groupamgr2to0count + $groupamgr3to0count) - ($groupamgr0to1count + $groupamgr0to2count + $groupamgr0to3count ));
$totalgroupamgr1 = ($groupemp0count + ($groupamgr0to1count + $groupamgr2to1count + $groupamgr3to1count) - ($groupamgr1to0count + $groupamgr1to2count + $groupamgr1to3count ));
$totalgroupamgr2 = ($groupemp0count + ($groupamgr0to2count + $groupamgr1to2count + $groupamgr3to2count) - ($groupamgr2to0count + $groupamgr2to1count + $groupamgr2to3count ));
$totalgroupamgr3 = ($groupemp0count + ($groupamgr0to3count + $groupamgr1to3count + $groupamgr2to3count) - ($groupamgr3to0count + $groupamgr3to1count + $groupamgr3to2count ));



?>
<br>
    <h1><center> Schedule for week of <?php echo $nextsunday ?></center></h1>
    <p align="center"><a href="schedulemgr.php">Back</a></p> 
 <div class="container">
      <div class="flag"
        
    <?php if(!isset($_SESSION['username'])): ?>
  <p class="lead">Please Sign in <a href="login.php">Login</a>.  Not yet registerd employee?<a href="signup.php"> Register </a></p>
  <?php else: ?>
   <div class="container-schedule">
    <table class="table table-bordered table-condensed">
    <tr>
        <td><strong>GROUP</td><td>MIN EMP</td><td>MIN MGR</td><td>DEFAULT EMP</td><td>DEFAULT MGR</td><td>UNAPROVED EMP</td><td>UNAPPROVED MGR</td><td>APROVED EMP</td><td>APPROVED MGR</td></strong>
    </tr>
        <tr>
        <td><strong>GROUP 0<br>Vacation/Admin</strong></td>
            <td>0</td> <!--min emp -->
            <td>0</td> <!-- min mgr -->
            <!-- default emp -->
            <td><?php if(isset($groupemp0count)) {echo $groupemp0count;} else{ echo "0";}?></td>
            <!-- default mgr -->
            <td><?php if(isset($groupmgr0count)) {echo $groupmgr0count;} else{ echo "0";}?></td>
            <!--change request emp -->
             <td><font color="blue"><?php if(isset($groupemp0to1count)){echo "group 0 to 1:". $groupemp0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupemp0to2count)){echo "group 0 to 2:". $groupemp0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupemp0to3count)){echo "group 0 to 3:". $groupemp0to3count;} else{echo "group 0 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupemp1to0count)){echo "group 1 to 0:". $groupemp1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupemp2to0count)){echo "group 2 to 0:". $groupemp2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupemp3to0count)){echo "group 3 to 0:". $groupemp3to0count;} else{echo "group 3 to 0:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupemp0; ?>
             </td>
             <!--change requestmgr -->
            <td><font color="blue"><?php if(isset($groupumgr0to1count)){echo "group 0 to 1:". $groupumgr0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupumgr0to2count)){echo "group 0 to 2:". $groupumgr0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupumgr0to3count)){echo "group 0 to 3:". $groupumgr0to3count;} else{echo "group 0 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupumgr1to0count)){echo "group 1 to 0:". $groupumgr1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupumgr2to0count)){echo "group 2 to 0:". $groupumgr2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupumgr3to0count)){echo "group 3 to 0:". $groupumgr3to0count;} else{echo "group 3 to 0:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupumgr1; ?>
             </td>
             <!-- change request emp approved -->
             <td><font color="blue"><?php if(isset($groupaemp0to1count)){echo "group 0 to 1:". $groupaemp0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupaemp0to2count)){echo "group 0 to 2:". $groupaemp0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupaemp0to3count)){echo "group 0 to 3:". $groupaemp0to3count;} else{echo "group 0 to 3:0";}?></font><br>
              <font color="purple"><?php if(isset($groupaemp1to0count)){echo "group 1 to 0:". $groupaemp1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupaemp2to0count)){echo "group 2 to 0:". $groupaemp2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupaemp3to0count)){echo "group 3 to 0:". $groupaemp3to0count;} else{echo "group 3 to 0:0";}?></font><br><?php echo "TOTAL: " . $totalgroupaemp0; ?>
             </td>
             <!-- change request mgr approved -->
             <td><font color="blue"><?php if(isset($groupamgr0to1count)){echo "group 0 to 1:". $groupamgr0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupamgr0to2count)){echo "group 0 to 2:". $groupamgr0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupamgr0to3count)){echo "group 0 to 3:". $groupamgr0to3count;} else{echo "group 0 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupamgr1to0count)){echo "group 1 to 0:". $groupamgr1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupamgr2to0count)){echo "group 2 to 0:". $groupamgr2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupamgr3to0count)){echo "group 3 to 0:". $groupamgr3to0count;} else{echo "group 3 to 0:0";}?></font><br>
              <?php echo "TOTAL: " . $totalgroupamgr0; ?>
             </td>
    </tr>  
    <tr>
        <td><strong>GROUP 1<br>Tue and Fri off<br> Sat-Sun 11AM-3PM<br> Mon,Wed,Thur 7PM-11PM</strong></td>
            <td>5</td>
            <td>1</td>
            <td><?php if(isset($groupemp1count)){echo $groupemp1count;} else{ echo "0";}?></td>
            <td><?php if(isset($groupmgr1count)) {echo $groupmgr1count;} else{ echo "0";}?></td>
            <td><font color="blue"><?php if(isset($groupemp1to0count)){echo "group 1 to 0:". $groupemp1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupemp1to2count)){echo "group 1 to 2:". $groupemp1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupemp1to3count)){echo "group 1 to 3:". $groupemp1to3count;} else{echo "group 1 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupemp0to1count)){echo "group 0 to 1:". $groupemp0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupemp2to1count)){echo "group 2 to 1:". $groupemp2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupemp3to1count)){echo "group 3 to 1:". $groupemp3to1count;} else{echo "group 3 to 1:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupemp1; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupumgr1to0count)){echo "group 1 to 0:". $groupumgr1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupumgrp1to2count)){echo "group 1 to 2:". $groupumgr1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupumgr1to3count)){echo "group 1 to 3:". $groupumgr1to3count;} else{echo "group 1 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupumgr0to1count)){echo "group 1 to 0:". $groupumgr1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupumgrp2to1count)){echo "group 2 to 1:". $groupumgr2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupumgr3to1count)){echo "group 3 to 1:". $groupumgr3to1count;} else{echo "group 3 to 1:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupumgr1; ?>
             </td>
             <td><font color="blue"><?php if(isset($groupaemp1to0count)){echo "group 1 to 0:". $groupaemp1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupaemp1to2count)){echo "group 1 to 2:". $groupaemp1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupaemp1to3count)){echo "group 1 to 3:". $groupaemp1to3count;} else{echo "group 1 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupaemp0to1count)){echo "group 0 to 1:". $groupaemp1to0count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupaemp2to1count)){echo "group 2 to 1:". $groupaemp2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupaemp3to1count)){echo "group 3 to 1:". $groupaemp3to1count;} else{echo "group 3 to 1:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupaemp1; ?>
             </td>
             <td><font color="blue"><?php if(isset($groupamgr1to0count)){echo "group 1 to 0:". $groupamgr1to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupamgrp1to2count)){echo "group 1 to 2:". $groupamgr1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupamgr1to3count)){echo "group 1 to 3:". $groupamgr1to3count;} else{echo "group 1 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupamgr0to1count)){echo "group 0 to 1:". $groupamgr0to1count;} else{echo "group 0 to 1:0";}?><br>
             <?php if(isset($groupamgrp2to1count)){echo "group 2 to 1:". $groupamgr2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupamgr3to1count)){echo "group 3 to 1:". $groupamgr3to1count;} else{echo "group 3 to 1:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupamgr1; ?></td>

    </tr>   
        <tr>
        <td><strong>GROUP 2<br>Mon and Thu off<br> Tue-Wed,Fri-Sun 3PM-7PM</strong></td>
            <td>5</td>
            <td>1</td>
            <td><?php if(isset($groupemp2count)){echo $groupemp2count;}else{ echo "0";} ?></td>
            <td><?php if(isset($groupmgr2count)) {echo $groupmgr2count;} else{ echo "0";}?></td>
            <td><font color="blue"><?php if(isset($groupemp2to0count)){echo "group 2 to 0:". $groupemp2to0count;} else{echo "group 1 to 0:0";}?><br>
             <?php if(isset($groupemp2to1count)){echo "group 2 to 1:". $groupemp2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupemp2to3count)){echo "group 2 to 3:". $groupemp2to3count;} else{echo "group 2 to 3:0";}?><br>
             <font color="purple"><?php if(isset($groupemp0to2count)){echo "group 2 to 0:". $groupemp0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupemp1to2count)){echo "group 1 to 2:". $groupemp1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupemp3to2count)){echo "group 3 to 2:". $groupemp3to2count;} else{echo "group 3 to 2:0";}?><br>
             <?php echo "TOTAL: " . $totalgroupemp2; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupumgr2to0count)){echo "group 2 to 0:". $groupumgr2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupumgr2to1count)){echo "group 2 to 1:". $groupumgr2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupumgr2to3count)){echo "group 2 to 3:". $groupumgr2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupumgr0to2count)){echo "group 0 to 2:". $groupumgr0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupumgr1to2count)){echo "group 1 to 2:". $groupumgr1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupumgr3to2count)){echo "group 3 to 2:". $groupumgr3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupumgr2; ?></td>
            <td><font color="blue"><?php if(isset($groupaemp2to0count)){echo "group 2 to 0:". $groupaemp2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupaemp2to1count)){echo "group 2 to 1:". $groupaemp2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupaemp2to3count)){echo "group 2 to 3:". $groupaemp2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupaemp0to2count)){echo "group 0 to 2:". $groupaemp0to2count;} else{echo "group 0 to 2:0";}?><br>
             <?php if(isset($groupaemp1to2count)){echo "group 1 to 2:". $groupaemp1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupaemp3to2count)){echo "group 3 to 2:". $groupaemp3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupaemp2; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupamgr2to0count)){echo "group 2 to 0:". $groupamgr2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupamgr2to1count)){echo "group 2 to 1:". $groupamgr2to1count;} else{echo "group 2 to 1:0";}?><br>
             <?php if(isset($groupamgr2to3count)){echo "group 2 to 3:". $groupamgr2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <font color="purple"><?php if(isset($groupamgr0to2count)){echo "group 0 to 2:". $groupamgr2to0count;} else{echo "group 2 to 0:0";}?><br>
             <?php if(isset($groupamgr1to2count)){echo "group 1 to 2:". $groupamgr1to2count;} else{echo "group 1 to 2:0";}?><br>
             <?php if(isset($groupamgr3to2count)){echo "group 3 to 2:". $groupamgr3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupamgr2; ?></td>
    </tr> 
        <tr>
        <td><strong>GROUP 3<br>Sun and Wed off<br> Mon and Thu 3PM-7PM<br> Tue and Fri-Sat 7PM-11PM</strong></td>
            <td>5</td>
            <td>1</td>
            <td><?php if(isset($groupemp3count)){echo $groupemp3count;}else{ echo "0";} ?></td>
            <td><?php if(isset($groupmgr3count)) {echo $groupmgr3count;}else{ echo "0";}?></td>
            <td><font color="blue"><?php if(isset($groupemp3to0count)){echo "group 3 to 0:". $groupemp3to0count;} else{echo "group 3 to 0:0";}?><br>
             <?php if(isset($groupemp3to1count)){echo "group 3 to 1:". $groupemp3to1count;} else{echo "group 3 to 1:0";}?><br>
             <?php if(isset($groupemp3to2count)){echo "group 3 to 2:". $groupemp3to2count;} else{echo "group 3 to 2:0";}?><br>
             <font color="purple"><?php if(isset($groupemp0to3count)){echo "group 0 to 3:". $groupemp0to3count;} else{echo "group 0 to 3:0";}?><br>
             <?php if(isset($groupemp1to3count)){echo "group 1 to 3:". $groupemp1to3count;} else{echo "group 1 to 3:0";}?><br>
             <?php if(isset($groupemp2to3count)){echo "group 2 to 3:". $groupemp2to3count;} else{echo "group 2 to 3:0";}?><br>
             <?php echo "TOTAL: " . $totalgroupemp3; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupumgr3to0count)){echo "group 3 to 0:". $groupumgr3to0count;} else{echo "group 3 to 0:0";}?><br>
             <?php if(isset($groupumgr3to1count)){echo "group 3 to 1:". $groupumgr3to1count;} else{echo "group 3 to 1:0";}?><br>
             <?php if(isset($groupumgr3to2count)){echo "group 3 to 2:". $groupumgr3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <font color="purple"><?php if(isset($groupumgr0to3count)){echo "group 0 to 3:". $groupumgr0to3count;} else{echo "group 0 to 3:0";}?><br>
             <?php if(isset($groupumgr1to3count)){echo "group 1 to 3:". $groupumgr1to3count;} else{echo "group 1 to 3:0";}?><br>
             <?php if(isset($groupumgr2to3count)){echo "group 2 to 3:". $groupumgr2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupumgr3; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupaemp3to0count)){echo "group 3 to 0:". $groupaemp3to0count;} else{echo "group 3 to 0:0";}?><br>
             <?php if(isset($groupaemp3to1count)){echo "group 3 to 1:". $groupaemp3to1count;} else{echo "group 3 to 1:0";}?><br>
             <?php if(isset($groupaemp3to2count)){echo "group 3 to 2:". $groupaemp3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <font color="purple"><?php if(isset($groupaemp0to3count)){echo "group 0 to 3:". $groupaemp0to3count;} else{echo "group 0 to 3:0";}?><br>
             <?php if(isset($groupaemp1to3count)){echo "group 1 to 3:". $groupaemp1to3count;} else{echo "group 1 to 3:0";}?><br>
             <?php if(isset($groupaemp2to3count)){echo "group 2 to 3:". $groupaemp2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupaemp3; ?>
             </td>
            <td><font color="blue"><?php if(isset($groupamgr3to0count)){echo "group 3 to 0:". $groupamgr3to0count;} else{echo "group 3 to 0:0";}?><br>
             <?php if(isset($groupamgr3to1count)){echo "group 3 to 1:". $groupamgr3to1count;} else{echo "group 3 to 1:0";}?><br>
             <?php if(isset($groupamgr3to2count)){echo "group 3 to 2:". $groupamgr3to2count;} else{echo "group 3 to 2:0";}?></font><br>
             <font color="purple"><?php if(isset($groupamgr0to3count)){echo "group 0 to 3:". $groupamgr0to3count;} else{echo "group 0 to 3:0";}?><br>
             <?php if(isset($groupamgr1to3count)){echo "group 1 to 3:". $groupamgr1to3count;} else{echo "group 1 to 3:0";}?><br>
             <?php if(isset($groupamgr2to3count)){echo "group 2 to 3:". $groupamgr2to3count;} else{echo "group 2 to 3:0";}?></font><br>
             <?php echo "TOTAL: " . $totalgroupamgr3; ?>
             </td>
    </tr>      
    </table>
    </div>
   <?php endif ?>
 
   </div><!-- /.container -->
<?php
    include_once 'headerfooter/footer.php';
?>