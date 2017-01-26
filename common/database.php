<!--
  Name: database.php 
  Author: Kedar Ram
  2016-12-02
 
  Purpose: Database connection 
-->

<?php
$username = "root";
$dsn = 'mysql:host=localhost; dbname=kingpinsdb2';
$password = "";
try
{
	$db = new PDO($dsn, $username, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $ex)
{
	echo "Database connection failed " . $ex->getMessage();
}

?>
