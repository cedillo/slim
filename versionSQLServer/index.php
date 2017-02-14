<?php
	session_start();	
	require 'cnx/conexion.php';
	require 'Slim/Slim.php';
	
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();

	define("MAIN_ACCESS", true);
		
	$app->config(array('debug'=>true, 'templates.path'=>'./',));				
	


	
	
	if(!isset($_SESSION["logueado"])) {
		//echo "Creando variables de sesion";
		$_SESSION["logueado"]=0;
		$_SESSION["idUsuario"] ="";		
		$_SESSION["sUsuario"] ="";				
	}	
	
	//Acceso al sitio
	$app->get('/', function() use($app){
		if($_SESSION["logueado"]==1){
			$result= array('idUsuario' => $_SESSION["idUsuario"] , 'nombre' => $_SESSION["sUsuario"] );
			$app->render('dashboard.php', $result);			
		}else{
			$app->render('login.html');		
		}
	});
	
	

	$app->get('/programas', function()  use ($app) {
		$app->render('programas.php');
	});
	
	
	
	//Login
	$app->post('/login', function()  use($app) {
		$request=$app->request;
		$cuenta = $request->post('txtUsuario');
		$pass = $request->post('txtPass');
		$latitud = $request->post('txtLatitud');
		$longitud = $request->post('txtLongitud');	
		
					$result= array('idUsuario' => $_SESSION["idUsuario"] , 'nombre' => $_SESSION["sUsuario"] );
			$app->render('dashboard.php', $result);			


		try {
			$sql = "SELECT idUsuario, CONCAT(nombre, ' ', paterno, ' ', materno) nombre FROM sia_usuarios";
			$db = getConnection();
			$stmt = $db->query($sql);  
			$clients = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo '{"client": ' . json_encode($clients) . '}';
			echo  $clients[0];
			
		} catch(PDOException $e) {
			
			echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		}

	});
	
	$app->get('/cerrar', function() use($app){
		$sql="UPDATE sia_accesos SET fEgreso=now(), estatus='INACTIVO' WHERE idUsuario=? AND estatus='ACTIVO';";
		$dbQuery = $db->prepare($sql);
		$params = array($_SESSION["idUsuario"]);
		$rs->execute($params);
		
		session_destroy();
		$app->render('login.html');		
	});
	
	
	$app->get('/dashboard', function()  use ($app) {
		$app->render('dashboard.php');
	});	
	
	
		

	$app->run();

	
	
function getConnection() {
	
	
$hostname = 'localhost';
$serverName = "MV-SERVER2012"; 
$database = 'ascm_sia';
$username = 'sa';
$password = '.sqlCota13.';
	
    //$dbh = new PDO ("ADODB.Connection");
    //$connStr = "PROVIDER=SQLOLEDB;SERVER=".$serverName.";UID=".$username.";PWD=".$password.";DATABASE=".$username;  
	//$connStr = "sqlsrv:server=" . $serverName . ";Database = " . $database . ", '" . $username  . "', '" .  $password . "'";
    //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    //$dbh->open($connStr); //Open the connection to the database  
	
	
	$dbh = new PDO("sqlsrv:server=" . $serverName . ";Database = " . $database , $username, $password); 
	
    return $dbh;
}


	
	
	