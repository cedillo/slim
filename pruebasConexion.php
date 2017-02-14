<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
	<title>SIA: Pruebas de Conexión</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="estilos.css" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="alternate" title="Pozolería RSS" type="application/rss+xml" href="/feed.rss" />
</head>
 
<body>
    <header>
       <h1>Pruebas de Conexión</h1>
       <p>PHP -> sql Server 2012</p>
    </header>
    <section>
       <article>
           <h2>RESULTADOS<h2>
           <p> <?php
				$serverName = "MV-SERVER2012"; //serverName\instanceName
				
				//$connectionInfo = array( "Database"=>"ascm_sia", "UID"=>"usrAudi", "PWD"=>".usraudiCota13.");
				//$connectionInfo = array( "Database"=>"ascm_sia", "UID"=>"sa", "PWD"=>".sqlCota13.");
				//$conn = sqlsrv_connect( $serverName, $connectionInfo);

				//if( $conn ) {
				//	 echo "<h1>Conexión establecida.</h1>";
				//}else{
				//	 echo "<h1>Conexión no se pudo establecer.</h1>";
				//	 die( print_r( sqlsrv_errors(), true));
				//}
				$connectionInfo = array( "Database"=>"ascm_sia","UID"=>"usrAudi","PWD"=>".usraudiCota13.");       
				$conn = sqlsrv_connect( $serverName, $connectionInfo); 
				if($conn){ 
					echo('<h1>CONEXIÓN EXITOSA</h1>'); 
				}else{ 
					echo('No se pudo conectar<br/>'); 
					die( print_r( sqlsrv_errors(), true)); 
				}  
			   $sql = "SELECT idCampana, nombre FROM sia_campanas ";
				$stmt = sqlsrv_query( $conn, $sql );
				if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}

				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					  echo $row['idCampana'].", ".$row['nombre']."<br />";
				}

				sqlsrv_free_stmt( $stmt);
				
				
				
				
				?>
 </p>
       </article>
    </section>
    <aside>
       <h3>Titulo de contenido</h3>
           <p>contenido</p>
    </aside>
    <footer>
        &copy; Jose Cota 2016
    </footer>
</body>
</html>



