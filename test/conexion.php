<?php 

class conexion{


private $hostname = 'localhost';
private $serverName = "MV-SERVER2012"; 
private $database = 'ascm_sia';
private $username = 'sa';
private $password = '.sqlCota13.';


public function conecta($host,$server,$db,$user,$pass){

		$this->host=$host;
		$this->server=$server;
		$this->db=$db;
		$this->user=$user;
		$this->pass=$pass;


		try{
			$db = new PDO("sqlsrv:Server={$this->hostname}; Database={$this->db}", $this->user, $this->pass );
		}catch (PDOException $e) {
			print "ERROR: " . $e->getMessage() . "<br><br>HOSTNAME: " . $hostname . " BD:" . $database . " USR: " . $username . " PASS: " . $password . "<br><br>";
			die();
		}

	}





}





 ?>