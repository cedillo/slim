<?php
include("src/conexion.php");

 try{
		  $db = new PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
	 }catch (PDOException $e) {
	  	print "ERROR: " . $e->getMessage() . "<br><br>HOSTNAME: " . $hostname . " BD:" . $database . " USR: " . $username . " PASS: " . $password . "<br><br>";
	  	die();
	 }






?>