<?php
$page_title="Welcome to KingPins FEC - Home Page";
include_once 'headerfooter/header.php';
?>

<?php
include_once "common/database.php";
include_once "phpmailer/class.phpmailer.php";
include_once "phpmailer/class.smtp.php";


if(isset($_POST['EmailPasswordBtn'])){
    //create array to store errors
    $form_errors=array();
    $required_fields = array('username','email');

    foreach ($required_fields as $field){
        if((!isset($_POST[$field])) || ($_POST[$field] == NULL)){
            $form_errors[] = $field . " is a required field";
        }
    }

    //validate data 
    //username should be atleast 4 characters and not over 30 long and use upper and lowercase alphabets
    if(strlen(trim($_POST['username'])) < 4 || (strlen(trim($_POST['username'])) > 31)){
        $form_errors[] = "Username should be atleast 4 characters long and under 30 characters";
        if(!preg_match("/^[a-zA-Z0-9]*$/",trim($_POST['username']))){
            $form_errors[] = "username should only be lower and upper case alphabets and can contain number 0-9";
        }
    }

    //email should have right format
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $form_errors[] = "Email is not in valid format";
    }

    if(empty($form_errors)){

        //collect values
        $username=trim($_POST['username']);
        $email=trim($_POST['email']);
        try
        {
        $sqlQuery = "SELECT username,password FROM employee WHERE username =:username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $username));

        if($statement->rowCount() == 0){
            $result = "<ul style='color: red;'>Invalid username</ul>";
        }else{
            while($row = $statement->fetch()){
                
                $default_password="kingpins";

                //encrypt password 
                $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);      
                try{
                $sqlUpdate = "UPDATE employee SET password =:password, updatedate = now() WHERE username =:username";
                $statementU = $db->prepare($sqlUpdate);
                $statementU->execute(array(':password' => $hashed_password, ':username' => $username));

                if($statement->rowCount() == 1){
                   
                $mail = new PHPMailer();

                $mail->Username = "kingpins.adm@gmail.com";
                $mail->Password = "kingpins123";
                
                $mail->addAddress($email,$username);
                $mail->FromName = "admin@kingpins.com";
                
                $mail->Subject = "Request info from KingpinsFEC";
                $mail->Body = "Your password is set to the default password, please login and change password.";

                $mail->Host = "ssl://smtp.gmail.com";
                $mail->Port = 465;
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->From = $mail->Username;
                $mail->setFrom("kingpins.adm@gmail.com","admin@kingpins.com");
                if(!$mail->Send()){
                    $result = "<ul style='color: red;'>Mail send error: " . $mail->ErrorInfo</ul>;
                }else{
                        $result = "<ul style='color: green;'>Password reset mail send to $email</ul>";
                }
                }
                
            }catch(PDOException $ex){
                echo "Database error occured " . $ex->getMessage();

            }   
            }
        }
        }catch(PDOException $ex){
            echo "Database error occured " . $ex->getMessage();

        }
    
}else{
    if(count($form_errors) > 0 ){
        $result = flashMessage("There are " . count($form_errors) . " errors in the form.");
        $result .= "<ul style='color: red;'>";
        foreach($form_errors as $error){
            $result .= "<li> {$error}</li>";
        }
        $result .= "</ul></p>";
    }
}

}
?>

<div class="container">
<div class="flag">
<h3>Welcome to KingPins FEC  - Password reset page</h1>

<p class="lead">Please type your email address, you will receive an email with your password</p>
<?php if(isset($result)) echo $result; ?>
<form action="" method="post">
 Username:<br>
  <input type="text" name="username" value=""><br>
 Email Address:<br>
  <input type="text" name="email" value=""><br><br>
 <button type="submit" name="EmailPasswordBtn" >Password Reset</button> 
</div>
<div style="text-align:center"><a href="login.php">Back</a></div>
</div>

<?php
include_once 'headerfooter/footer.php';
?>