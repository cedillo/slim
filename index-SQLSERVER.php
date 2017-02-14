<?php
	session_start();	
	require 'src/conexion.php';
	require 'Slim/Slim.php';
	
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();

	define("MAIN_ACCESS", true);
		
	$app->config(array('debug'=>true, 'templates.path'=>'./',));				

	
   try {
	  $db = new PDO("sqlsrv:server=" . $serverName . ";Database = " . $database , $username, $password); 
	  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
   }
   catch( PDOException $e ) {
	  die( "<b>Se generó un error:</b><br>" . $e->getMessage( ) . "<hr>Svr:(" . $serverName . ")<br>Database: (" . $database . ")Usr: (" . $username . ")<br>Pass: (" . $password  . ")"); 
	}	
	
	
	
	
	if(!isset($_SESSION["logueado"])) $_SESSION["logueado"]=0;
	
	//ACCESO AL SISTEMA

	//Acceso al sitio
	$app->get('/', function() use($app){
		if($_SESSION["logueado"]==1){
			$result= array('idUsuario' => $_SESSION["idUsuario"] , 'nombre' => $_SESSION["sUsuario"] );
			$app->render('dashboard2.php', $result);			
		}else{
			$app->render('login.html');		
		}
	});

	//Login
	$app->post('/login', function()  use($app, $db) {
		$request=$app->request;
		$cuenta = $request->post('txtUsuario');
		$pass = $request->post('txtPass');
		$latitud = $request->post('txtLatitud');
		$longitud = $request->post('txtLongitud');

		$params = array($cuenta, $pass);
		$sql = "SELECT idUsuario, CONCAT(nombre, ' ', paterno, ' ', materno) nombre FROM sia_usuarios Where usuario=? and pwd=?";
		$rs = $db->prepare($sql);
		$rs->execute($params);	
		
		$nCantidadRegs = count($rs);
		if($nCantidadRegs > 0){
			echo "Cantidad de Registros: " . $nCantidadRegs;
		
		}else{
			echo "no registros";
		}
		
		//$app->halt(404, "------------------------------Cantidad de Registros: " . $nCantidadRegs);			

		
		
		
				
	});
	
	
		

	$app->run();
