<?php

	try{
		$db = new PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
	}catch (PDOException $e) {
		print "ERROR: " . $e->getMessage() . "<br><br>HOSTNAME: " . $hostname . " BD:" . $database . " USR: " . $username . " PASS: " . $password . "<br><br>";
		die();
	}
	
	
	$app->get('/cargarArchivo/Universo/:nombre/:cuenta', function($nombre, $cuenta)    use($app, $db) {	
		try{
			$usrActual = $_SESSION["idUsuario"];
			$archivo ='uploads/' . $nombre;

			//Elimina cuenta pública
			$sql="DELETE FROM sia_cuentasingresos WHERE idCuenta= :cuenta ;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));

			//Abrir archivo de XLS
			$xlsx = new SimpleXLSX($archivo);
			list($num_cols, $num_rows) = $xlsx->dimension();			

			//insertar 
			$sql="INSERT INTO sia_cuentasuniversos (idCuenta, origen, tipo, clave, nivel, nombre,  original, recaudado, usrAlta, fAlta, estatus) " .
			"values(:cuenta,:origen, :tipo, :clave, :nivel, :nombre, :original, :recaudado, :usrActual, getdate(), 'ACTIVO')";

			$dbQuery = $db->prepare($sql);
			$nRegistros=0;
			
			error_reporting(E_ALL ^ E_NOTICE);
			
			foreach( $xlsx->rows() as $row ) {				
				$origen = $row[0];
				$tipo =  "" . $row[1];
				$clave =  "" . $row[2];
				$nivel =  "" . $row[3];
				$nombre =  "" . $row[4];
				$original =  "" . $row[5];
				$recaudado =  "" . $row[6];
								
				if ($nRegistros>0){
					//$dbQuery->execute(array(':cuenta' => $cuenta, ':origen' => $origen, ':tipo' => $tipo,':clave' => $clave, ':nivel' => $nivel, ':nombre' => $nombre, 
					//':original' => $original, ':recaudado' => $recaudado,':usrActual' => $usrActual));								
				}				
				$nRegistros++;	
			}
			echo "Se cargaron " . $nRegistros . " registro(s).";
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});	

	
	
	
	
	
	
	$app->get('/cargarArchivo/Ingreso/:nombre/:cuenta', function($nombre, $cuenta)    use($app, $db) {	
		try{
			$usrActual = $_SESSION["idUsuario"];
			$archivo ='uploads/' . $nombre;

			//Elimina cuenta pública
			$sql="DELETE FROM sia_cuentasingresos WHERE idCuenta= :cuenta ;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));

			//Abrir archivo de XLS
			$xlsx = new SimpleXLSX($archivo);
			list($num_cols, $num_rows) = $xlsx->dimension();			

			//insertar 
			$sql="INSERT INTO sia_cuentasingresos (idCuenta, origen, tipo, clave, nivel, nombre,  original, recaudado, usrAlta, fAlta, estatus) " .
			"values(:cuenta,:origen, :tipo, :clave, :nivel, :nombre, :original, :recaudado, :usrActual, getdate(), 'ACTIVO')";

			$dbQuery = $db->prepare($sql);
			$nRegistros=0;
			
			error_reporting(E_ALL ^ E_NOTICE);
			
			foreach( $xlsx->rows() as $row ) {				
				$origen = $row[0];
				$tipo =  "" . $row[1];
				$clave =  "" . $row[2];
				$nivel =  "" . $row[3];
				$nombre =  "" . $row[4];
				$original =  "" . $row[5];
				$recaudado =  "" . $row[6];
								
				if ($nRegistros>0){
					$dbQuery->execute(array(':cuenta' => $cuenta, ':origen' => $origen, ':tipo' => $tipo,':clave' => $clave, ':nivel' => $nivel, ':nombre' => $nombre, 
					':original' => $original, ':recaudado' => $recaudado,':usrActual' => $usrActual));								
				}				
				$nRegistros++;	
			}
			echo "Se cargaron " . $nRegistros . " registro(s).";
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});

	$app->get('/cargarArchivo/Egreso/:nombre/:cuenta', function($nombre, $cuenta)    use($app, $db) {	
		try{
			$usrActual = $_SESSION["idUsuario"];
			$archivo ='uploads/' . $nombre;

			//Elimina cuenta pública
			$sql="DELETE FROM sia_cuentasdetalles WHERE idCuenta= :cuenta ;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));

			//Abrir archivo de XLS
			$xlsx = new SimpleXLSX($archivo);
			list($num_cols, $num_rows) = $xlsx->dimension();			

			//insertar 
			$sql="INSERT INTO sia_cuentasdetalles " .
			"(idCuenta, sector, subsector, unidad, funcion, subfuncion, actividad, capitulo, partida, finalidad, progPres, fuenteFinanciamiento, fuenteGenerica, fuenteEspecifica, " .
			"origenRecurso, tipoGasto, digito, proyecto, destinoGasto, original, modificado, ejercido, pagado, pendiente, usrAlta, fAlta, estatus) " .
			"values(:cuenta,:sector, :subsector, :unidad, :funcion, :subfuncion, :actividad, :capitulo, :partida, :finalidad, :progPres, :fuenteFinanciamiento, :fuenteGenerica, :fuenteEspecifica, " .
			":origenRecurso, :tipoGasto, :digito, :proyecto, :destinoGasto, :original, :modificado, :ejercido, :pagado, :pendiente, :usrActual, getdate(), 'ACTIVO');";

			$dbQuery = $db->prepare($sql);
			$nRegistros=0;
			
			error_reporting(E_ALL ^ E_NOTICE);
			
			foreach( $xlsx->rows() as $row ) {				
				$sector = $row[0];
				$subsector =  "" . $row[1];
				$unidad =  "" . $row[2];
				$funcion =  "" . $row[3];
				$subfuncion =  "" . $row[4];
				$actividad =  "" . $row[5];
				$capitulo =  "" . $row[6];
				$partida =  "" . $row[7];
				$finalidad =  "" . $row[8];
				$progPres =  "" . $row[9];
				$fuenteFinanciamiento =  "" . $row[10];
				$fuenteGenerica =  "" . $row[11];
				$fuenteEspecifica =  "" . $row[12];
				$origenRecurso =  "" . $row[13];
				$tipoGasto =  "" . $row[14];
				$digito =  "" . $row[15];
				$proyecto =  "" . $row[16];
				$destinoGasto =  "" . $row[17];
				$original =  "" . $row[18];
				$modificado =  "" . $row[19];
				$ejercido =  "" . $row[20];
				$pagado =  "" . $row[21];
				$pendiente =  "" . $row[22];	
								
				if ($nRegistros>0){
					$dbQuery->execute(array(':cuenta' => $cuenta, ':sector' => $sector, ':subsector' => $subsector,':unidad' => $unidad, ':funcion' => $funcion, ':subfuncion' => $subfuncion, ':actividad' => $actividad,
					':capitulo' => $capitulo, ':partida' => $partida, ':finalidad' => $finalidad, ':progPres' => $progPres, ':fuenteFinanciamiento' => $fuenteFinanciamiento, ':fuenteGenerica' => $fuenteGenerica,
					':fuenteEspecifica' => $fuenteEspecifica, ':origenRecurso' => $origenRecurso, ':tipoGasto' => $tipoGasto, ':digito' => $digito, ':proyecto' => $proyecto, ':destinoGasto' => $destinoGasto,
					':original' => $original, ':modificado' => $modificado, ':ejercido' => $ejercido, ':pagado' => $pagado, ':pendiente' => $pendiente, ':usrActual' => $usrActual));								
				}				
				$nRegistros++;	
			}
			echo "Se cargaron " . $nRegistros . " registro(s).";
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});
	
	
	$app->get('/normatividades', function()  use ($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$sql="SELECT idNormatividad id, nombre, tipo, acceso, concat(fInicio, ' al ', fFin) vigencia, estatus   FROM sia_normatividades   WHERE idCuenta=:cuenta ORDER BY tipo, nombre ";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS.");
		}else{
			$app->render('normatividad.php', $result);
		}
	})->name('listaNormatividad');


	//Guarda un papel
	$app->post('/guardar/normatividad', function()  use($app, $db) {
	
		try{
			$usrActual = $_SESSION["idUsuario"];
			$cuenta = $_SESSION["idCuentaActual"];
			
			$request=$app->request;
			$oper = $request->post('txtOperacion');
			$id = $request->post('txtID');
			$nombre = strtoupper($request->post('txtNombre'));
			$tipo = $request->post('txtTipo');
			$acceso = $request->post('txtAcceso');
			
			$inicio = date_create($request->post('txtFechaInicial'));
			$inicio = $inicio->format('Y-m-d');
			
			$fin = date_create($request->post('txtFechaFinal'));
			$fin = $fin->format('Y-m-d');
			
			$estatus = $request->post('txtEstatus');

			if($oper=='INS'){
				$sql="INSERT INTO sia_normatividades (idCuenta, nombre, tipo, acceso, fInicio, fFin, usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :nombre, :tipo, :acceso, :inicio, :fin, :usrActual, getdate(), :estatus);";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':nombre' => $nombre, ':tipo' => $tipo, ':acceso' => $acceso, ':inicio' => $inicio, ':fin' => $fin, ':usrActual' => $usrActual, ':estatus' => $estatus ));
				
				//echo "<br>$sql <hr>INS 100%";
			}else{
				$sql="UPDATE sia_normatividades SET " .
				"idCuenta=:cuenta, nombre=:nombre, tipo=:tipo, acceso=:acceso, fInicio=:inicio, fFin=:fin, usrModificacion=:usrActual, fModificacion=getdate(), estatus=:estatus " .
				"WHERE idNormatividad=:id";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':nombre' => $nombre, ':tipo' => $tipo, ':acceso' => $acceso, ':inicio' => $inicio, ':fin' => $fin, ':usrActual' => $usrActual, ':estatus' => $estatus, ':id' => $id ));
				//echo "UPD 100%";
			}
			$app->redirect($app->urlFor('listaNormatividad'));
			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});


	$app->get('/normatividad/:id', function($id)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$sql="SELECT idNormatividad id, tipo, acceso, nombre, fInicio, fFin,  estatus FROM sia_normatividades  Where idCuenta=:cuenta and idNormatividad=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

$app->get('/rangos', function()  use ($app, $db) {
		$sql="SELECT idRango id, descripcion, anio, token, siglas, concat(inicio, ' al ', fin) rango, siguiente, disponible, minimo, estatus   FROM sia_rangos ORDER BY descripcion ";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS.");
		}else{
			$app->render('rangos.php', $result);
		}
	})->name('listaRangos');


	//Guarda un rango
	$app->post('/guardar/rango', function()  use($app, $db) {
	
		try{
			$usrActual = $_SESSION["idUsuario"];
			$cuenta = $_SESSION["idCuentaActual"];
			
			$request=$app->request;
			$oper = $request->post('txtOperacion');
			$id = $request->post('txtID');
			$descripcion = strtoupper($request->post('txtDescripcion'));			
			$anio = $request->post('txtAnio');
			$token = strtoupper($request->post('txtToken'));
			$siglas = strtoupper($request->post('txtSiglas'));
			$inicio = $request->post('txtInicio');
			$siguiente = $request->post('txtSiguiente');
			$fin = $request->post('txtFin');
			$minimo = $request->post('txtMinimo');
			$estatus = $request->post('txtEstatus');
			

			if($oper=='INS'){
				$disponible = $fin - $inicio +  1;
				$siguiente = $inicio;				
				$sql="INSERT INTO sia_rangos (descripcion, anio, token, siglas, inicio, fin, siguiente, disponible, minimo, usrAlta, fAlta, estatus) " .
				"VALUES(:descripcion, :anio, :token, :siglas, :inicio, :fin, :siguiente, :disponible, :minimo, :usrActual, getdate(), :estatus);";

				$dbQuery = $db->prepare($sql);
				
				$dbQuery->execute(array(':descripcion' => $descripcion, ':anio' => $anio, ':token' => $token, ':siglas' => $siglas, ':inicio' => $inicio, ':fin' => $fin, 
				 ':siguiente' => $siguiente,':disponible' => $disponible, ':minimo' => $minimo, ':usrActual' => $usrActual, ':estatus' => $estatus ));	


				//echo "SQL:<hr>" . $sql . "<hr> Token: " . $token;
				 
			}else{
				
				$disponible = $fin - $siguiente +  1;
				
				$sql="UPDATE sia_rangos SET " .
				"descripcion=:descripcion, anio=:anio, token=:token, siglas=:siglas, inicio=:inicio, fin=:fin, disponible=:disponible, minimo=:minimo, " . 
				"usrModificacion=:usrActual, fModificacion=getdate(), estatus=:estatus " .
				"WHERE idRango=:id";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':descripcion' => $descripcion, ':anio' => $anio, ':token' => $token, ':siglas' => $siglas, ':inicio' => $inicio, ':fin' => $fin, 
				':disponible' => $disponible, ':minimo' => $minimo, ':usrActual' => $usrActual, ':estatus' => $estatus, ':id' => $id ));
			}
			$app->redirect($app->urlFor('listaRangos'));
			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});

	$app->get('/rango/:id', function($id)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$sql="SELECT idRango id, descripcion, anio, token, siglas, inicio, fin, siguiente, disponible, minimo, estatus FROM sia_rangos  Where idRango=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});
	
 $app->get('/catUsuarios', function()   use($app, $db) {
  $dbQuery = $db->prepare("SELECT idUsuario id, isnull(idEmpleado,'') idEmpleado, concat(saludo, ' ', nombre, ' ', paterno, ' ', " ."materno) usuario, isnull(correo,'') correo, isnull(telefono,'') telefono," .
   "CASE WHEN tipo='CF' THEN 'ESTRUCTURA' WHEN tipo='TE' THEN 'EVENTUAL'  WHEN tipo='HS' THEN 'HONORARIOS' WHEN tipo='PR' THEN 'PROFIS' ELSE '' END AS tipo," . 
   "usuario cuenta,  estatus FROM sia_usuarios ORDER BY concat(nombre, ' ', paterno, ' ', materno);");  
  $dbQuery->execute();
  $result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
  if(!$result){
   $app->halt(404, "NO SE ENCONTRARON DATOS ");
  }else{
   $app->render('catUsuarios.php', $result);
  } 
 })->name('listaUsuarios');

	
	
	
	//Guardar un USUARIO
	$app->post('/guardar/usuario', function()  use($app, $db) {
		$request=$app->request;
		$oper = $request->post('operacion');
		$id = $request->post('txtID');
		$tipo = $request->post('txtTipo');
		$empleado = strtoupper($request->post('txtEmpleado'));
		$saludo = strtoupper($request->post('txtSaludo'));
		$nombre = strtoupper($request->post('txtNombre'));
		$paterno = strtoupper($request->post('txtPaterno'));
		$materno = strtoupper($request->post('txtMaterno'));
		$usuario = $request->post('txtCorreo');
		
		$pwd = $request->post('txtPassword');
		$telefono = $request->post('txtTelefono');
		$estatus = $request->post('txtEstatus');
		$usrActual = $_SESSION["idUsuario"];
		
		if ($oper=='INS'){
			$sql = "INSERT INTO sia_usuarios (tipo,saludo, idEmpleado, nombre, paterno, materno, telefono, usuario, pwd, usrAlta, fAlta, estatus) ".
			"VALUES(:tipo, :saludo, :empleado, :nombre, :paterno, :materno,:telefono, :usuario, :pwd, :usrActual, now(), :estatus); ";		
			$dbQuery = $db->prepare($sql);	
			
			$dbQuery->execute(array(':tipo'=> $tipo, ':saludo'=> $saludo, ':empleado'=> $empleado, ':nombre'=> $nombre, ':paterno'=> $paterno, ':materno'=> $materno, ':telefono'=> $telefono,
			 ':usuario'=> $usuario,  ':pwd'=> $pwd, ':usrActual'=> $usrActual, ':estatus'=> $estatus));		
			 
		}else{
			$sql = "UPDATE sia_usuarios " . 
			"SET tipo=:tipo, saludo=:saludo, idEmpleado=:empleado, nombre=:nombre, paterno=:paterno, materno=:materno, telefono=:telefono, usuario=:usuario, pwd=:pwd, " . 
			"usrModificacion=:usrModificacion, fModificacion= now(), estatus=:estatus ".
			"WHERE idUsuario=:id";		
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':tipo'=> $tipo, ':saludo'=> $saludo, ':empleado'=> $empleado, ':nombre'=> $nombre,':paterno'=> $paterno, ':materno'=> $materno, 
			':telefono'=> $telefono, ':usuario'=> $usuario,':pwd'=> $pwd, ':usrModificacion'=> $usrActual,':estatus' => $estatus, ':id' => $id));				
		}
		$app->redirect($app->urlFor('listaUsuarios'));
	});	
	
	//Obtener un usuario
	$app->get('/usuario/:id',  function($id)  use($app, $db) {
		$id = (int)$id;
		$dbQuery = $db->prepare("SELECT idUsuario id, saludo, isnull(idEmpleado, '') idEmpleado, nombre, tipo, paterno, materno, isnull(telefono, '') telefono, usuario, pwd,  estatus FROM sia_usuarios WHERE idUsuario=:id ");		
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});
	

	
	
$app->get('/lstRoles', function()   use($app, $db) {
		$dbQuery = $db->prepare("Select idRol id, nombre texto From sia_roles order by nombre  ");		
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);	
	});	
	
$app->get('/lstApartados', function()   use($app, $db) {
		$dbQuery = $db->prepare("Select idApartado id, nombre texto From sia_apartados order by nombre  ");		
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);	
	});		
	
	
	// Obtener centro gestor
$app->get('/lstUnidadesByArea/:area', function($area)   use($app, $db) {
	$cuenta = $_SESSION["idCuentaActual"];
	
	$sql="Select concat(u.idSector, '-', u.idSubsector, '-', u.idUnidad) id, u.nombre texto  " .
	"From sia_unidades u inner join sia_areasunidades au on u.idCuenta = au.idCuenta and au.idSector = u.idSector and u.idSubsector = au.idSubsector and au.idUnidad = u.idUnidad ".
	"Where u.idCuenta =:cuenta and au.idArea=:area";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);	
	});

	// Obtener centro gestor
$app->get('/lstObjetosByCentro/:area', function($area)   use($app, $db) {
	$cuenta = $_SESSION["idCuentaActual"];
	
	$sql="Select concat(u.idSector, '-', u.idSubsector, '-', u.idUnidad) id, u.nombre texto  " .
	"From sia_unidades u inner join sia_areasunidades au on u.idCuenta = au.idCuenta and au.idSector = u.idSector and u.idSubsector = au.idSubsector and au.idUnidad = u.idUnidad ".
	"Where u.idCuenta =:cuenta and au.idArea=:area";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);	
	});
	
	
		//Listar auditorias by area responsable
	$app->get('/lstAuditoriasByArea/:area', function($area)  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){
			$sql="SELECT idAuditoria id, concat(COALESCE(clave, concat('PROY-',idAuditoria)), ' ', tipoAuditoria) texto FROM sia_auditorias Where idCuenta=:cuenta order by 2 asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="SELECT idAuditoria id, concat(COALESCE(clave, concat('PROY-',idAuditoria)), ' ', tipoAuditoria) texto FROM sia_auditorias Where idCuenta=:cuenta and idArea=:area order by 2 asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));		
		}		
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});
	
	
//Listar auditorias by area responsable y auditor
	$app->get('/lstAuditoriasByAreaAuditor/:area', function($area)  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		//$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		$globalArea = $_SESSION["usrGlobalArea"];
		$rpe = $_SESSION["idEmpleado"];
		
		if ($global=="SI"){
			$sql="SELECT a.idAuditoria id, concat(COALESCE(clave, convert(varchar,a.idAuditoria)), ' ', u.nombre,  ' / TIPO:', ta.nombre) texto  " .
			"FROM sia_auditorias a " .
			"LEFT JOIN sia_unidades u ON a.idCuenta=u.idCuenta and a.idSector=u.idSector and a.idSubsector=u.idSubsector and a.idUnidad=u.idUnidad " .
			//"INNER JOIN  sia_auditoriasauditores aa ON a.idCuenta=aa.idCuenta and a.idAuditoria=aa.idAuditoria " .
			"INNER JOIN sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"WHERE a.idCuenta=:cuenta  order by 2 asc ";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{

			if ($globalArea=="SI"){
				$sql=" SELECT a.idAuditoria id, concat(COALESCE(clave, convert(varchar,a.idAuditoria)), ' ', u.nombre,  ' / TIPO:', ta.nombre) texto " .
				"FROM sia_auditorias a " .
				"LEFT JOIN sia_unidades u ON a.idCuenta=u.idCuenta and a.idSector=u.idSector and a.idSubsector=u.idSubsector and a.idUnidad=u.idUnidad " .
				"INNER JOIN sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
				"WHERE a.idCuenta=:cuenta and a.idArea=:area order by id asc ";
				
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));	
			}else{

				$sql="SELECT a.idAuditoria id, concat(COALESCE(clave, convert(varchar,a.idAuditoria)), ' ', u.nombre,  ' / TIPO: ', ta.nombre) texto  " .
				"FROM sia_auditorias a " .
				"LEFT JOIN sia_unidades u ON a.idCuenta=u.idCuenta and a.idSector=u.idSector and a.idSubsector=u.idSubsector and a.idUnidad=u.idUnidad " .
				"INNER JOIN  sia_auditoriasauditores aa ON a.idCuenta=aa.idCuenta and a.idAuditoria=aa.idAuditoria " .
				"INNER JOIN sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
				"WHERE a.idCuenta=:cuenta and a.idArea=:area and aa.idAuditor=:auditor order by 2 asc ";
					
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area, ':auditor' => $rpe));		
			}		
		}	
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
	
	
	
$app->get('/cedulas', function()  use ($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$auditor = $_SESSION["idEmpleado"];
		
		$usrActual = $_SESSION["idUsuario"];
		
		$global = $_SESSION["usrGlobal"];		
		
		$globalArea = $_SESSION["usrGlobalArea"];
		
		if ($global=="SI"){
			$sql="Select a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, p.idPapel, tp.nombre sPapel, p.fPapel, " . 
			"p.idFase, p.tipoResultado, p.archivoOriginal, p.archivoFinal,  p.estatus " .
			"FROM sia_papeles p  " .
			"inner join  sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma = a.idPrograma and p.idAuditoria = a.idAuditoria " .
			"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
			"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta " .
			"and p.estatus='ACTIVO' and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " . 
			"Order by p.fAlta desc ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':usrActual' => $usrActual));
		}else{
		
			if ($globalArea=="SI"){
				$sql="Select a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, p.idPapel, tp.nombre sPapel, p.fPapel, " . 
				"p.idFase, p.tipoResultado, p.archivoOriginal, p.archivoFinal,  p.estatus " .
				"FROM sia_papeles p  " .
				"inner join  sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma = a.idPrograma and p.idAuditoria = a.idAuditoria " .
				"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
				"Where a.idCuenta=:cuenta  and a.idArea=:area and p.estatus='ACTIVO' " . 
				"Order by p.fAlta desc ";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));	
			}else{
				$sql="Select a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, p.idPapel, tp.nombre sPapel, p.fPapel, " . 
				"p.idFase, p.tipoResultado, p.archivoOriginal, p.archivoFinal,  p.estatus " .
				"FROM sia_papeles p  " .
				"inner join  sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma = a.idPrograma and p.idAuditoria = a.idAuditoria " .
				"inner join  sia_auditoriasauditores aa on a.idAuditoria=aa.idAuditoria " .
				"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
				"Where a.idCuenta=:cuenta  and aa.idAuditor=:auditor " . 
				"and p.estatus='ACTIVO' and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " . 
				"Order by p.fAlta desc ";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':auditor' => $auditor, ':usrActual' => $usrActual));	
			}
		
	
		}
		//$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		$app->render('papeles.php', $result);
	})->name('listaPapeles');	
	
	
$app->get('/lstPapeles', function()    use($app, $db) {
		$sql="SELECT tp.idTipoPapel id, tp.nombre texto FROM sia_papelesfases pf INNER JOIN sia_tipospapeles tp on pf.idTipoPapel= tp.idTipoPapel ORDER BY tp.nombre";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array());
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/lstPapelesByFase/:id', function($id)    use($app, $db) {
		$area = $_SESSION["idArea"];
		$sql="SELECT tp.idTipoPapel id, tp.nombre texto 
			  FROM sia_papelesfases pf 
  			  INNER JOIN sia_tipospapeles tp on pf.idTipoPapel= tp.idTipoPapel 
  			  INNER JOIN sia_areastipospapeles atp on tp.idTipoPapel=atp.idTipoPapel
  			  WHERE atp.idArea=:area and pf.idFase=:id and tp.estatus='ACTIVO' ORDER BY tp.nombre";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id,':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});
	

	$app->get('/papel/:id', function($id)    use($app, $db) {
		$sql="SELECT p.idPapel, p.idAuditoria, COALESCE(clave, concat('Proy-Aud-',a.idAuditoria)) claveAuditoria, p.idFase, p.tipoPapel, p.fPapel, tp.programada, " .
		"p.tipoResultado, p.resultado, p.archivoOriginal, p.archivoFinal, a.idProceso proceso, a.idEtapa etapa,p.estatus, tp.nomenclatura nomen,aa.idAuditor lider,us.idUsuario usuario " . 
		"FROM sia_papeles p inner join sia_auditorias a on a.idCuenta = p.idCuenta AND a.idPrograma = p.idPrograma AND a.idAuditoria = p.idAuditoria " .
		"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
		"inner join sia_auditoriasauditores aa  on p.idAuditoria=aa.idAuditoria AND aa.lider='SI' " .
		"inner join sia_usuarios us on aa.idAuditor=us.idEmpleado " .
		"WHERE p.idPapel=:id";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
			
	
		
	
//Guarda un papel
	$app->post('/guardar/papel', function()  use($app, $db) {
		try{
			$usrActual = $_SESSION["idUsuario"];
			$request=$app->request;
			$id = $request->post('txtID');
			$oper = $request->post('txtOperacion');
			$cuenta = $request->post('txtCuenta');
			$programa = $request->post('txtPrograma');
			$auditoria = $request->post('txtAuditoria');
			$fase = $request->post('txtFase');
			$tipoPapel = $request->post('txtTipoPapel');
			
			$fPapel = date_create($request->post('txtFechaPapel'));
			$fPapel = $fPapel->format('Y-m-d');
			
			$tipoResultado = $request->post('txtTipoRes');
			$resultado = strtoupper($request->post('txtResultado'));
			
			$original = $request->post('txtArchivoOriginal');
			$final = $request->post('txtArchivoFinal');

			if($oper=='INS'){
				$sql="INSERT INTO sia_papeles (idCuenta, idPrograma, idAuditoria, idFase, tipoPapel, fPapel, tipoResultado, resultado, archivoOriginal, archivoFinal, usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :programa, :auditoria, :fase, :tipoPapel, :fPapel, :tipoResultado, :resultado, :original, :final, :usrActual, getdate(), 'ACTIVO');";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':auditoria' => $auditoria, ':fase' => $fase, 
				':tipoPapel' => $tipoPapel, ':fPapel' => $fPapel, ':tipoResultado' => $tipoResultado,':resultado' => $resultado, ':original' => $original, ':final' => $final, ':usrActual' => $usrActual ));				
			}else{
				$sql="UPDATE sia_papeles SET " .
				"idFase=:fase, tipoPapel=:tipoPapel, fPapel=:fPapel, tipoResultado=:tipoResultado, resultado=:resultado, usrModificacion=:usrActual, fModificacion=getdate() " .
				"WHERE idPapel=:id";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':fase' => $fase, ':tipoPapel' => $tipoPapel, ':fPapel' => $fPapel,':tipoResultado' => $tipoResultado,':resultado' => $resultado, ':usrActual' => $usrActual, ':id' => $id  ));
			}
			//echo "SQL=<br>$sql<br><hr> id: " . $id . " fase: " . $fase . " tipoPapel: " . $tipoPapel . " fPapel: " . $fPapel . " tipoResultado: " . $tipoResultado . " resultado: " . $resultado . " usrActual: " . $usrActual;
			$app->redirect($app->urlFor('listaPapeles'));
		}catch (PDOException $e) {
			echo  "<br>Error de BD: " . $e->getMessage();
			die();
		}		
	});


	

///////////////////////////////////A C O P I O S/////////////////////////////////////////////////////////
		
	//Lista de acopios
	$app->get('/acopios', function() use($app, $db){
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$auditor = $_SESSION["idEmpleado"];
		$usrActual = $_SESSION["idUsuario"];
		$global = $_SESSION["usrGlobal"];
		
		$globalArea = $_SESSION["usrGlobalArea"];
		
		if ($global=="SI"){		
			$sql="SELECT a.idAuditoria,  COALESCE(clave, convert(varchar, a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, ac.idAcopio, ac.idClasificacion, ac.idFase, ac.archivoFinal, ac.archivoOriginal, ac.estatus " .
			"FROM sia_acopio ac  " .
			"inner join  sia_auditorias a on ac.idCuenta=a.idCuenta and ac.idPrograma = a.idPrograma and ac.idAuditoria = a.idAuditoria " .
			"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .			
			"WHERE a.idCuenta=:cuenta and ac.estatus='ACTIVO' Order by ac.fAlta desc ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{		
			if ($globalArea=="SI"){
				$sql="SELECT a.idAuditoria,  COALESCE(clave, convert(varchar, a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, a.tipoAuditoria, ac.idAcopio, ac.idClasificacion, ac.idFase, ac.archivoFinal, ac.archivoOriginal, ac.estatus " .
				"FROM sia_acopio ac  " .
				"inner join  sia_auditorias a on ac.idCuenta=a.idCuenta and ac.idPrograma = a.idPrograma and ac.idAuditoria = a.idAuditoria " .
				"WHERE a.idCuenta=:cuenta  and a.idArea=:area and ac.estatus='ACTIVO' Order by ac.fAlta desc ";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));			
			}else{
				$sql="SELECT a.idAuditoria,  COALESCE(clave, convert(varchar, a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, a.tipoAuditoria, ac.idAcopio, ac.idClasificacion, ac.idFase, ac.archivoFinal, ac.archivoOriginal, ac.estatus " .
				"FROM sia_acopio ac  " .
				"inner join  sia_auditorias a on ac.idCuenta=a.idCuenta and ac.idPrograma = a.idPrograma and ac.idAuditoria = a.idAuditoria " .
				"inner join  sia_auditoriasauditores aa on a.idAuditoria=aa.idAuditoria " .
				"WHERE a.idCuenta=:cuenta  and aa.idAuditor=:auditor and ac.estatus='ACTIVO' Order by ac.fAlta desc ";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':auditor' => $auditor));
			
			}
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		$app->render('acopio.php', $result);
	})->name('listaAcopio');
	
//Guarda un acopio
	$app->post('/guardar/acopio', function()  use($app, $db) {
		try{
			$usrActual = $_SESSION["idUsuario"];
			$request=$app->request;
			$id = $request->post('txtID');
			$oper = $request->post('txtOperacion');
			$cuenta = $request->post('txtCuenta');
			$programa = $request->post('txtPrograma');
			$auditoria = $request->post('txtAuditoria');
			$fase = $request->post('txtFase');
			$clasificacion = $request->post('txtClasificacion');
			$observaciones = strtoupper($request->post('txtObservaciones'));
			$original = $request->post('txtArchivoOriginal');
			$final = $request->post('txtArchivoFinal');
			$est = $request->post('txtEstatusAcopio');

			if($oper=='INS'){
				$sql="INSERT INTO sia_acopio (idCuenta, idPrograma, idAuditoria, idFase, idClasificacion, observaciones, archivoOriginal, archivoFinal, usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :programa, :auditoria, :fase, :clasificacion, :observaciones, :original, :final, :usrActual, getdate(), 'ACTIVO');";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':auditoria' => $auditoria, ':fase' => $fase, ':clasificacion' => $clasificacion, ':observaciones' => $observaciones, 
				':original' => $original, ':final' => $final, ':usrActual' => $usrActual ));				
			}else{
				if($est=='INACTIVO'){
					$sql="UPDATE sia_acopio SET usrModificacion=:usrActual, fModificacion=getdate(),estatus=:est " .
						"WHERE idAcopio=:id";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':usrActual' => $usrActual,':est' => $est, ':id' => $id  ));

				}else{
					$sql="UPDATE sia_acopio SET idFase=:fase, idClasificacion=:clasificacion, observaciones=:observaciones, archivoOriginal=:original, archivoFinal=:final, usrModificacion=:usrActual, fModificacion=getdate() " .
					"WHERE idAcopio=:id";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':fase' => $fase, ':clasificacion' => $clasificacion, ':observaciones' => $observaciones, ':original' => $original, ':final' => $final, ':usrActual' => $usrActual, ':id' => $id  ));
				}
			}
			//echo "SQL=<br>$sql<br><hr> id: " . $id . " Cuenta: " . $cuenta .  " Programa: " . $programa .  " Auditoria: " . $auditoria ." fase: " . $fase . " clasificacion: " . $clasificacion . " observaciones: " . $observaciones . " usrActual: " . $usrActual;
			$app->redirect($app->urlFor('listaAcopio'));
		}catch (PDOException $e) {
			echo  "<br>Error de BD: " . $e->getMessage();
			die();
		}
	});
	
	//Obtener un acopio
	$app->get('/acopioByid/:id', function($id)    use($app, $db) {
		$sql="SELECT idAcopio, idAuditoria, idFase, idClasificacion, observaciones, archivoOriginal, archivoFinal,estatus FROM sia_acopio WHERE idAcopio=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
/*	
	$app->get('/acopio/:id', function($id)    use($app, $db) {
		$sql="SELECT idAcopio, idAuditoria, idFase, idClasificacion, observaciones, archivoOriginal, archivoFinal FROM sia_acopio WHERE idAcopio=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
*/

///////////////////////////////////A V A N C E S/////////////////////////////////////////////////////////
		
	//Lista de avances
	$app->get('/avanceActividad', function() use($app, $db){
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){			
			$sql="SELECT a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, ta.nombre tipoAuditoria, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor, aa.idAvance, f.nombre fase, concat(left(aact.actividad, 100), '...') actividad, aa.porcentaje  " .
			"FROM sia_auditoriasavances aa " .
				"inner join sia_auditorias a  on aa.idCuenta=a.idCuenta and aa.idPrograma = a.idPrograma and aa.idAuditoria = a.idAuditoria " .
				"left join sia_empleados e on aa.idAuditor=e.idEmpleado " .
				"inner join sia_fases f on f.idFase=aa.idFase " .
				"inner join sia_auditoriasactividades aact  on aa.idActividad = aact.idActividad " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta " .
			"order by aa.idAvance desc";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="SELECT a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, ta.nombre tipoAuditoria, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor, aa.idAvance, f.nombre fase, concat(left(aact.actividad, 100), '...') actividad, aa.porcentaje  " .
			"FROM sia_auditoriasavances aa " .
				"inner join sia_auditorias a  on aa.idCuenta=a.idCuenta and aa.idPrograma = a.idPrograma and aa.idAuditoria = a.idAuditoria " .
				"left join sia_empleados e on aa.idAuditor=e.idEmpleado " .
				"inner join sia_fases f on f.idFase=aa.idFase " .
				"inner join sia_auditoriasactividades aact  on aa.idActividad = aact.idActividad " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta and a.idArea=:area " .
			"order by aa.idAvance desc";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));		
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		$app->render('avanceActividad.php', $result);
	})->name('listaAvances');
	
//Guarda un avance
	$app->post('/guardar/avance', function()  use($app, $db) {
		try{
			$usrActual = $_SESSION["idUsuario"];
			$request=$app->request;
			$id = $request->post('txtID');
			$oper = $request->post('txtOperacion');
			$cuenta = $request->post('txtCuenta');
			$programa = $request->post('txtPrograma');
			$auditoria = $request->post('txtAuditoria');
			$fase = $request->post('txtFase');
			$actividad = $request->post('txtActividad');
			$porcentaje = $request->post('txtPorcentaje');
			$auditor = $request->post('txtAuditor');
			
			$apartado = $request->post('txtApartado');
			
			$inicio = date_create($request->post('txtFechaInicio'));
			$inicio = $inicio->format('d-m-Y');
			
			
			$fin = date_create($request->post('txtFechaFin'));
			$fin = $fin->format('d-m-Y');
			
			
			
			
			$obs = $request->post('txtObservaciones');

			if($oper=='INS'){
				$sql="INSERT INTO sia_auditoriasavances (idCuenta, idPrograma, idAuditoria, idFase, idActividad, porcentaje, idAuditor, idApartado, fInicio, fFin, observaciones, usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :programa, :auditoria, :fase, :actividad, :porcentaje, :auditor, :apartado, :inicio, :fin, :obs,:usrActual, getdate(), 'ACTIVO');";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':auditoria' => $auditoria, ':fase' => $fase, 
				':actividad' => $actividad, ':porcentaje' => $porcentaje, ':auditor' => $auditor, ':apartado' => $apartado, ':inicio' => $inicio, ':fin' => $fin, ':obs' => $obs,':usrActual' => $usrActual ));										
			}else{
				$sql="UPDATE sia_auditoriasavances SET idFase=:fase, idActividad=:actividad, porcentaje=:porcentaje, idAuditor=:auditor, idApartado=:apartado, fInicio=:inicio, fFin=:fin, observaciones=:obs,   
				usrModificacion=:usrActual, fModificacion=getdate() " .
				"WHERE idAvance=:id";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':fase' => $fase, ':actividad' => $actividad, ':porcentaje' => $porcentaje, ':auditor' => $auditor,':apartado' => $apartado, 
				':inicio' => $inicio, ':fin' => $fin, ':obs' => $obs, ':usrActual' => $usrActual, ':id' => $id  ));
			}
			
			//echo "SQL=<br>$sql<br><br><hr><br><br> id:  $id  Cuenta:  $cuenta  Programa:  $programa  Auditoria:  $auditoria fase:  $fase  actividad:$actividad  porcentaje:$porcentaje idAuditor=$auditor Apartado= $apartado Inicio= $inicio Fin= $fin  Obs=$obs  usrActual= $usrActual";			
			$app->redirect($app->urlFor('listaAvances'));
		}catch (PDOException $e) {
			echo  "<br>Error de BD: " . $e->getMessage();
			die();
		}
	});
	
	//Obtener un avance
	$app->get('/avanceById/:id', function($id)    use($app, $db) {
		$sql="SELECT idAvance, idAuditoria, idFase, idActividad, porcentaje, idAuditor, estatus FROM sia_auditoriasavances WHERE idAvance=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
	
	///////////////////////////////////consolidados/////////////////////////////////////////////////////////
		
	//Lista de Consolidados
	$app->get('/consolidados', function() use($app, $db){
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		
		$usrActual = $_SESSION["idUsuario"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){		
			$sql="Select tc.idTipoConsolidado tipo, tc.nombre sTipoConsolidado, c.idConsolidado docto, c.nombre sDocto, concat(u.idSector, u.idSubsector,u.idUnidad) gestor, concat(u.idSector, u.idSubsector,u.idUnidad, ' ', u.nombre)  sGestor, count(*) cantidad " .
			"FROM sia_consolidadosdetalles cd INNER JOIN sia_unidades u ON cd.idCuenta = u.idCuenta AND cd.idSector = u.idSector and cd.idSubsector = u.idSubsector and cd.idUnidad=u.idUnidad " .
			"INNER JOIN sia_consolidados c on cd.idConsolidado = c.idConsolidado " .
			"INNER JOIN sia_tiposconsolidados tc  ON tc.idTipoConsolidado = c.idTipoConsolidado " .
			"INNER JOIN sia_areasunidades au ON cd.idCuenta = au.idCuenta and cd.idSector = au.idSector and cd.idSubsector=au.idSubsector and cd.idUnidad = au.idUnidad " .
			"Where cd.idCuenta=:cuenta and au.idArea=:area " .
			"Group by tc.idTipoConsolidado, tc.nombre, c.idConsolidado, c.nombre, concat(u.idSector, u.idSubsector,u.idUnidad), concat(u.idSector, u.idSubsector, u.idUnidad, ' ', u.nombre) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="Select tc.idTipoConsolidado tipo, tc.nombre sTipoConsolidado, c.idConsolidado docto, c.nombre sDocto, concat(u.idSector, u.idSubsector,u.idUnidad) gestor, concat(u.idSector, u.idSubsector,u.idUnidad, ' ', u.nombre)  sGestor, count(*) cantidad " .
			"FROM sia_consolidadosdetalles cd INNER JOIN sia_unidades u ON cd.idCuenta = u.idCuenta AND cd.idSector = u.idSector and cd.idSubsector = u.idSubsector and cd.idUnidad=u.idUnidad " .
			"INNER JOIN sia_consolidados c on cd.idConsolidado = c.idConsolidado " .
			"INNER JOIN sia_tiposconsolidados tc  ON tc.idTipoConsolidado = c.idTipoConsolidado " .
			"INNER JOIN sia_areasunidades au ON cd.idCuenta = au.idCuenta and cd.idSector = au.idSector and cd.idSubsector=au.idSubsector and cd.idUnidad = au.idUnidad " .
			"Where cd.idCuenta=:cuenta and au.idArea=:area " .
			"Group by tc.idTipoConsolidado, tc.nombre, c.idConsolidado, c.nombre, concat(u.idSector, u.idSubsector,u.idUnidad), concat(u.idSector, u.idSubsector, u.idUnidad, ' ', u.nombre) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));
		
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		$app->render('catConsolidados.php', $result);
	})->name('listaConsolidados');
	
//Guarda un consolidado
	$app->get('/guardar/consolidado/:oper/:cadena', function($oper, $cadena)  use($app, $db) {
		try{
			$usrActual = $_SESSION["idUsuario"];
			$cuenta = $_SESSION["idCuentaActual"];
			
			
			$dato = explode("|", $cadena);
			$id = $dato[0];
			$sector = $dato[1];
			$subsector = $dato[2];
			$unidad = $dato[3];
			$tipo = $dato[4];
			$consolidado = $dato[5];
			$nivel = $dato[6];
			$nombre = strtoupper($dato[7]);
			$importe = $dato[8];			

			if($oper=='INS'){
				$sql="INSERT INTO sia_consolidadosdetalles (idCuenta, idSector, idSubsector, idUnidad, idConsolidado, nivel, nombre, importe,  usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :sector, :subsector, :unidad, :consolidado, :nivel, :nombre, :importe, :usrActual, getdate(), 'ACTIVO');";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':sector' => $sector, ':subsector' => $subsector, ':unidad' => $unidad, ':consolidado' => $consolidado, ':nivel' => $nivel, 
				':nombre' => $nombre, ':importe' => $importe, ':usrActual' => $usrActual ));
				
				//echo "<br>$sql   <br><br>Cuenta: $cuenta  Sector:$sector  Subsector:$subsector  Unidad:$unidad  Consolidado:$consolidado  Nivel: $nivel   Importe:$importe  Nombre: $nombre  usrActual:$usrActual <hr>INS 100%";
			}else{
				$sql="UPDATE sia_consolidadosdetalles SET " .
				"idSector=:sector, idSubsector=:subsector, idUnidad=:unidad, idConsolidado=:consolidado, nivel=:nivel, nombre=:nombre, importe=:importe, usrModificacion=:usrActual, fModificacion=getdate() " .
				"WHERE idConsolidadoDetalle=:id";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array( ':sector' => $sector, ':subsector' => $subsector, ':unidad' => $unidad, ':consolidado' => $consolidado, ':nivel' => $nivel,':nombre' => $nombre, ':importe' => $importe,  ':usrActual' => $usrActual,':id' => $id ));
				//echo "UPD 100%";
			}
			echo "OK";
			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});	
	

	$app->get('/lstCentrosByArea', function()  use($app, $db) {

		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		
		$sql="SELECT au.idSector + au.idSubsector + au.idUnidad id , u.nombre texto " . 
		"FROM sia_areasunidades au " .
		"INNER JOIN sia_unidades u ON au.idCuenta = u.idCuenta AND au.idSector = u.idSector AND au.idSubsector = u.idSubsector AND au.idUnidad = u.idUnidad ".
		"WHERE au.idCuenta = :cuenta AND au.idArea = :area ORDER BY u.nombre" ;

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));		
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);

	});
	
	
	
	//Obtenerlista de consolidados
	$app->get('/tblRubrosByCentroDocto/:centro/:docto', function($centro, $docto)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$centro= str_replace('|', '', $centro);	
		
		$sql="Select cd.idConsolidadoDetalle id, tc.nombre sTipoConsolidado,  c.nombre sDocto, concat(u.idSector, u.idSubsector,u.idUnidad, ' ', u.nombre)  sGestor, cd.nombre rubro, cd.nivel, isnull(cd.importe,'') importe " .
		"FROM sia_consolidadosdetalles cd " . 
		"INNER JOIN sia_unidades u ON cd.idCuenta = u.idCuenta AND cd.idSector = u.idSector and cd.idSubsector = u.idSubsector and cd.idUnidad=u.idUnidad " .
		"INNER JOIN sia_consolidados c on cd.idConsolidado = c.idConsolidado " .
		"INNER JOIN sia_tiposconsolidados tc  ON tc.idTipoConsolidado = c.idTipoConsolidado " .
		//"INNER JOIN sia_areasunidades au ON cd.idCuenta = au.idCuenta and cd.idSector = au.idSector and cd.idSubsector=au.idSubsector and cd.idUnidad = au.idUnidad " .
		"Where cd.idCuenta=:cuenta and  concat(cd.idSector,cd.idSubsector,cd.idUnidad)=:centro and cd.idConsolidado=:docto;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':centro' => $centro, ':docto' => $docto));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});	
	
	
//Obtener lista de consolidados
	$app->get('/tblRubroByID/:id', function($id)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		
		
		$sql="Select cd.idConsolidadoDetalle id, tc.idTipoConsolidado, tc.nombre sTipoConsolidado, c.idConsolidado,  c.nombre sDocto, " . 
		"concat(u.idSector, u.idSubsector,u.idUnidad) gestor, concat(u.idSector, u.idSubsector,u.idUnidad, ' ', u.nombre)  sGestor, cd.nombre rubro, cd.nivel, isnull(cd.importe,'') importe " .
		"FROM sia_consolidadosdetalles cd " . 
		"INNER JOIN sia_unidades u ON cd.idCuenta = u.idCuenta AND cd.idSector = u.idSector and cd.idSubsector = u.idSubsector and cd.idUnidad=u.idUnidad " .
		"INNER JOIN sia_consolidados c on cd.idConsolidado = c.idConsolidado " .
		"INNER JOIN sia_tiposconsolidados tc  ON tc.idTipoConsolidado = c.idTipoConsolidado " .
		"Where cd.idCuenta=:cuenta and  cd.idConsolidadoDetalle=:id;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});		
	
	
	
	
//Eliminar lista de consolidados
	$app->get('/tblEliminarRubroByID/:id', function($id)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		
		//Obtiene los datos a eliminar
		$sql="Select c.idConsolidado docto, concat(u.idSector, u.idSubsector,u.idUnidad) gestor " .
		"FROM sia_consolidadosdetalles cd " . 
		"INNER JOIN sia_unidades u ON cd.idCuenta = u.idCuenta AND cd.idSector = u.idSector and cd.idSubsector = u.idSubsector and cd.idUnidad=u.idUnidad " .
		"INNER JOIN sia_consolidados c on cd.idConsolidado = c.idConsolidado " .
		"INNER JOIN sia_tiposconsolidados tc  ON tc.idTipoConsolidado = c.idTipoConsolidado " .
		"Where cd.idCuenta=:cuenta and  cd.idConsolidadoDetalle=:id;";		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);

		//Elimina
		$sql="DELETE FROM sia_consolidadosdetalles WHERE idConsolidadoDetalle=:id;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		
		//Regresa los datos eliminados		
		echo json_encode($result);		
	});		
	
	
	
	
	
	
	
	
	
	
	
	
	
	//Obtener proxima etapa
	$app->get('/proximaEtapa/:proceso/:etapa', function($proceso, $etapa)    use($app, $db) {
		$usrActual = $_SESSION ["idUsuario"];
		/*
		$sql="SELECT TOP 1 re.idProceso proceso, re.idEtapa etapa, concat('btn',e.idEtapa) boton " .
		"FROM sia_rolesetapas re inner join sia_etapas e on e.idProceso=re.idProceso and e.idEtapa=re.idEtapa " .
		"WHERE e.idProceso=:proceso and e.idEtapa<>:etapa  and orden > (Select orden from sia_etapas Where idProceso=:proceso2 and idEtapa=:etapa2 )  and re.idRol in (select ur.idRol from sia_usuariosroles ur where idUsuario = :usrActual) ORDER BY e.orden";
		*/
		$sql = "SELECT TOP 1 re.idProceso proceso, re.idEtapa etapa, case re.autorizarEtapa when 'SI' THEN concat('btn',e.idEtapa) ELSE '' END boton, re.idrol FROM sia_rolesetapas re inner join sia_etapas e on e.idProceso=re.idProceso and e.idEtapa=re.idEtapa WHERE e.idProceso=:proceso and e.idEtapa<>:etapa  and orden > (Select orden from sia_etapas Where idProceso=:proceso2 and idEtapa=:etapa2 )  
			and re.idRol in (select ur.idRol from sia_usuariosroles ur where idUsuario = :usrActual and (idRol <> 'GLOBAL-AREA' and idRol <> 'GLOBAL') ) ORDER BY e.orden";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':proceso' => $proceso, ':etapa' => $etapa, ':proceso2' => $proceso, ':etapa2' => $etapa, ':usrActual' => $usrActual));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
	//Asignar etapa
	$app->get('/asignarEtapa/:auditoria/:proceso/:etapa', function($auditoria, $proceso, $etapa)    use($app, $db) {
		$sql="UPDATE sia_auditorias SET idProceso=:proceso, idEtapa=:etapa WHERE idAuditoria=:auditoria";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':proceso' => $proceso, ':etapa' => $etapa, ':auditoria' => $auditoria));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
	// Generar claves de auditoría
	
	//Obtener un avance
	$app->get('/generarClaves/:cuenta/:programa', function($cuenta, $programa)    use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		
		//Obtener lista de auditorias a Integrar
		$sql="SELECT idAuditoria FROM sia_auditorias WHERE idCuenta=:cuenta and idPrograma=:programa and idEtapa='INTEGRACION';";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa));
		$audis = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
				
		//Obtener un folio inicial
		$sql = "SELECT TOP 1 idRango, siguiente folio, siglas, disponible FROM sia_rangos WHERE token='NUM-AUDITORIAS' and estatus='ACTIVO';";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$rsFolios = $dbQuery->fetch(PDO::FETCH_ASSOC);			
		$folioActual = $rsFolios['folio'];
		$rango = $rsFolios['idRango'];
		$siglas = $rsFolios['siglas'];
		$disponible = $rsFolios['disponible'];
		
		foreach ($audis as $audi) {			
			$auditoriaActual = $audi['idAuditoria'];

			//Asignar el folio
			$clave = $siglas . $folioActual;					
			$sql = "UPDATE sia_auditorias SET clave=:clave, usrModificacion=:usrActual, fModificacion=getdate() Where idCuenta=:cuenta and idPrograma=:programa and idAuditoria=:auditoria ";
			$dbQuery = $db->prepare($sql);			
			$dbQuery->execute(array( ':usrActual' => $usrActual, ':clave' => $clave,':cuenta' => $cuenta, ':programa' => $programa, ':auditoria' => $auditoriaActual));
						
			//Registrar bitacora del folio
			$sql = "INSERT INTO sia_rangosfolios (idRango, folio, fFolio, idDocumento, usrAlta, fAlta, estatus) values(:rango, :folio, getdate(), 1, :usrActual, getdate(), 'ACTIVO'); ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':rango' => $rango, ':folio' => $folioActual, ':usrActual' => $usrActual));
			$folioActual = $folioActual + 1; 			
		}
		//Registrar ultimo folio
		$disponible = $disponible - $folioActual + 1;
		$sql = "UPDATE sia_rangos SET siguiente=:folio, disponible=:disponible, usrModificacion=:usrActual, fModificacion=getdate() WHERE idRango=:rango;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':folio' => $folioActual, ':disponible' => $disponible, ':usrActual' => $usrActual, ':rango' => $rango));	
		echo "OK";
	});	


//Obtener lista de tipo de consolidadospor area
	$app->get('/lstTiposConsolidados', function()    use($app, $db) {
		$sql="Select idTipoConsolidado id, nombre texto from sia_tiposconsolidados Where idArea=:area order by 2 asc";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
//Obtener lista de tipo de consolidados by Tipo
	$app->get('/lstConsolidadosByTipo/:tipo', function($tipo)    use($app, $db) {
		$sql="Select idConsolidado id, nombre texto from sia_consolidados Where idTipoConsolidado=:tipo order by 2 asc";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':tipo' => $tipo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});

	
	
//Obtener una lista de audotores por area
	$app->get('/lstAuditoresByArea/:area', function($area)    use($app, $db) {
		$sql="Select idEmpleado id, concat(nombre, ' ', paterno, ' ', materno) texto from sia_empleados Where idArea=:area and idNivel not in ('45.0', '40.0', '31.0') order by 2 asc";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});
	
	

	//Listar actividades by auditoria + fase
	$app->get('/lstActividadesByAuditoriaFase/:audi/:fase', function($audi, $fase)  use($app, $db) {
		$sql="SELECT aa.idActividad id, concat(aa.idActividad, ' ', f.nombre, left(actividad, 100), '...') texto 
		FROM sia_auditoriasactividades aa 
		LEFT join sia_fases f on aa.idFase = f.idFase
		WHERE aa.idAuditoria=:audi     and aa.idFase=:fase 
		ORDER BY 2 asc";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':audi' => $audi, ':fase' => $fase));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
	
	//Obtener un avance
	$app->get('/buscarTipoPapel/:id', function($id)    use($app, $db) {
		$sql="SELECT idTipoPapel, nombre papel, programada FROM sia_tipospapeles WHERE idTipoPapel=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});	
	
	//Obtener un empleado
	$app->get('/empleadoByRPE/:id', function($id)    use($app, $db) {
		$sql="SELECT u.saludo, u.idUsuario, e.idEmpleado, u.tipo, e.nombre, e.paterno, e.materno, e.idArea, u.telefono, u.usuario, u.pwd, u.estatus " . 
		"FROM sia_empleados e left join sia_usuarios u on u.idEmpleado=e.idEmpleado " . 
		"WHERE e.idEmpleado=:id";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});	
	
	
	//Listar actividades by auditoria 
	$app->get('/dpsAuditoriasByArea', function()  use($app, $db) {
		
		$area = $_SESSION["idArea"];
		$cuenta = $_SESSION["idCuentaActual"];
		
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){
			$sql="SELECT ta.nombre texto, count(*) valor " . 
			"FROM sia_auditorias a INNER JOIN sia_tiposauditoria ta  ON a.tipoAuditoria=ta.idTipoAuditoria " . 
			"WHERE a.idCuenta=:cuenta " .
			"GROUP BY ta.nombre ORDER BY ta.nombre";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));				
		}else{
			$sql="SELECT ta.nombre texto, count(*) valor " . 
			"FROM sia_auditorias a INNER JOIN sia_tiposauditoria ta  ON a.tipoAuditoria=ta.idTipoAuditoria " . 
			"WHERE a.idCuenta=:cuenta  AND a.idArea=:area " .
			"GROUP BY ta.nombre ORDER BY ta.nombre ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));					
		}
		
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		
	
	
	//Listar actividades by auditoria + fase
	$app->get('/dpsEmpleadosByArea', function()  use($app, $db) {
		$sql="Select idArea texto, count(*) valor from sia_empleados group by idArea  order by idArea";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		
	
//dpsCedulasByAuditoria	
	$app->get('/dpsCedulasByAuditoria', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){	
			$sql="SELECT a.idAuditoria id, COALESCE(a.clave, concat('PROY-',a.idAuditoria)) texto, " . 
			"(SELECT count(*) FROM sia_papeles p WHERE p.idAuditoria=a.idAuditoria ) valor " . 
			"FROM sia_auditorias a " .
			"WHERE a.idCuenta=:cuenta  " .
			"ORDER BY COALESCE(a.clave, concat('PROY-',a.idAuditoria)) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));	
		}else{
			$sql="SELECT a.idAuditoria id, COALESCE(a.clave, concat('PROY-',a.idAuditoria)) texto, " . 
			"(SELECT count(*) FROM sia_papeles p WHERE p.idAuditoria=a.idAuditoria ) valor " . 
			"FROM sia_auditorias a " .
			"WHERE a.idCuenta=:cuenta and a.idArea=:area  " .
			"ORDER BY COALESCE(a.clave, concat('PROY-',a.idAuditoria)) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));						
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
	
//dpsDoctosByAuditoria	
	$app->get('/dpsDoctosByAuditoria', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){	
			$sql="SELECT a.idAuditoria id, COALESCE(a.clave, concat('PROY-',a.idAuditoria)) texto, " . 
			"(SELECT count(*) FROM sia_acopio ac WHERE ac.idAuditoria=a.idAuditoria ) valor " . 
			"FROM sia_auditorias a " .
			"WHERE a.idCuenta=:cuenta  " .
			"ORDER BY COALESCE(a.clave, concat('PROY-',a.idAuditoria)) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));	
		}else{
			$sql="SELECT a.idAuditoria id, COALESCE(a.clave, concat('PROY-',a.idAuditoria)) texto, " . 
			"(SELECT count(*) FROM sia_acopio ac WHERE ac.idAuditoria=a.idAuditoria ) valor " . 
			"FROM sia_auditorias a " .
			"WHERE a.idCuenta=:cuenta and a.idArea=:area  " .
			"ORDER BY COALESCE(a.clave, concat('PROY-',a.idAuditoria)) ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));						
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		
	
	
	
//dps tipos de papeles
	$app->get('/dpsTipoPapeles', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){	
			$sql="SELECT tp.nombre texto, count(*) valor " . 
			"FROM sia_papeles p INNER JOIN sia_tipospapeles tp ON p.tipoPapel=tp.idTipoPapel " . 
			"INNER JOIN sia_auditorias a on p.idAuditoria=a.idAuditoria " .
			"WHERE p.idCuenta=:cuenta  " .
			"GROUP BY tp.nombre  ORDER BY tp.nombre ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));	
		}else{
			$sql="SELECT tp.nombre texto, count(*) valor " . 
			"FROM sia_papeles p INNER JOIN sia_tipospapeles tp ON p.tipoPapel=tp.idTipoPapel " . 
			"INNER JOIN sia_auditorias a on p.idAuditoria=a.idAuditoria " .
			"WHERE p.idCuenta=:cuenta  and a.idArea=:area " .
			"GROUP BY tp.nombre  ORDER BY tp.nombre ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));						
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		

//Listar actividades by auditoria + fase
	$app->get('/dpsTipoAcopio', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){		
			$sql="SELECT ac.idClasificacion texto, count(*) valor " .
			"FROM sia_acopio ac  " .
			"INNER JOIN  sia_auditorias a on ac.idCuenta=a.idCuenta and ac.idPrograma = a.idPrograma and ac.idAuditoria = a.idAuditoria " .
			"Where ac.idCuenta=:cuenta  Group by idClasificacion  Order by idClasificacion ";
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="SELECT ac.idClasificacion texto, count(*) valor " .
			"FROM sia_acopio ac  " .
			"INNER JOIN  sia_auditorias a on ac.idCuenta=a.idCuenta and ac.idPrograma = a.idPrograma and ac.idAuditoria = a.idAuditoria " .
			"Where ac.idCuenta=:cuenta  and a.idArea=:area Group by idClasificacion  Order by idClasificacion ";
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));		
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		
	
//Listar Avance por cada auditoria
	$app->get('/dpsAvanceByAuditorias', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){		
			$sql="Select distinct a.idAuditoria, COALESCE(clave, concat('PROY-',a.idAuditoria)) text, u.nombre sujeto, " .
			"isnull((Select top 1 porcentaje from sia_auditoriasavances aa2 Where a.idAuditoria=aa2.idAuditoria order by porcentaje desc ), 0) valor " .
			"from sia_auditorias a " .
			"left join sia_auditoriasavances aa on a.idAuditoria=aa.idAuditoria " .
			"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
			"Where a.idCuenta=:cuenta ";
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="Select distinct a.idAuditoria, COALESCE(clave, concat('PROY-',a.idAuditoria)) texto, u.nombre sujeto, " .
			"isnull((Select top 1 porcentaje from sia_auditoriasavances aa2 Where a.idAuditoria=aa2.idAuditoria order by porcentaje desc ), 0) valor " .
			"from sia_auditorias a " .
			"left join sia_auditoriasavances aa on a.idAuditoria=aa.idAuditoria " .
			"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
			"Where a.idCuenta=:cuenta and a.idArea=:area ";
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));	
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});		
		

//Listar Avance por cada auditoria
	$app->get('/dpsMonitorAuditoresByDireccion/:f1/:f2', function($f1, $f2)  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){		
			$sql="Select CONVERT(VARCHAR,fIngreso,105) texto, count(*) valor from sia_accesos ". 
			"Where fIngreso between CONVERT(VARCHAR,:f1,105) and CONVERT(VARCHAR,:f2,105) ".
			"GROUP BY CONVERT(VARCHAR,fIngreso,105) ".
			"ORDER BY CONVERT(VARCHAR,fIngreso,105) ";
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':f1' => $f1, ':f2' => $f2));
			//$dbQuery->execute();	
		}else{
			$sql="Select CONVERT(VARCHAR,a.fIngreso,105) texto, count(*) valor " . 
			"FROM sia_accesos  a inner join sia_usuarios u ON a.idUsuario=u.idUsuario " . 
			"Where a.fIngreso between CONVERT(VARCHAR,:f1,105) and CONVERT(VARCHAR,:f2,105) AND u.idArea=:area ".
			"GROUP BY CONVERT(VARCHAR,a.fIngreso,105) ".
			"ORDER BY CONVERT(VARCHAR,a.fIngreso,105) ";
			
			$dbQuery = $db->prepare($sql);		
			$dbQuery->execute(array(':area' => $area, ':f1' => $f1, ':f2' => $f2));
			//$dbQuery->execute();	
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});

		
	
	//Listar localizacion de las auditorias
	$app->get('/ptsAuditoriasByArea', function()  use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$sql="SELECT a.idAuditoria, COALESCE(a.clave, concat('PROY-',a.idAuditoria)) claveAuditoria, ta.nombre tipoAuditoria,  a.latitud, a.longitud " . 
		"FROM sia_auditorias a inner join sia_tiposauditoria ta  on a.tipoAuditoria=ta.idTipoAuditoria  " . 
		"WHERE a.idCuenta=:cuenta and a.idArea=:area AND a.latitud  IS NOT NULL AND a.longitud IS NOT NULL ";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
	
	//Listar responsables de cada area
	$app->get('/lstAreasResponsables', function()  use($app, $db) {
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		$globalArea = $_SESSION["usrGlobalArea"];
		
		if ($global=="SI" || $globalArea=="SI"){			
			$sql="SELECT idResponsable id, nombre texto FROM sia_areasresponsables ORDER BY nombre asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute();		
		}else{
			$sql="SELECT idResponsable id, nombre texto FROM sia_areasresponsables WHERE idArea=:area ORDER BY nombre asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':area' => $area));	
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
	
	
	//Listar responsables de cada area
	$app->get('/lstAreasResponsablesByID/:id', function($id)  use($app, $db) {		
		$sql="SELECT idSubresponsable id, nombre texto FROM sia_areassubresponsables WHERE idResponsable=:id ORDER BY nombre asc";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));	
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	
		
	//Listar rangos de fechas inhabiles
	$app->get('/lstInhabilesByRango/:f1/:f2', function($f1, $f2)  use($app, $db) {	
		
		$f1 = date_create($f1);
		$f1 = $f1->format('Y-m-d');
		
		$f2 = date_create($f2);
		$f2 = $f2->format('Y-m-d');		

		try{
		$sql="Select 'A' caso , fInicio, fFin from sia_diasinhabiles Where :par1<fInicio and fInicio<:par2 and :par3<fFin " . 
			"Union all " . 
			"Select 'B' caso , fInicio, fFin from sia_diasinhabiles Where fInicio<:par4 and :par5<fFin " . 
			"union all " . 
			"Select 'C' caso , fInicio, fFin from sia_diasinhabiles Where fInicio<:par6 and :par7<fFin and fFin<:par8 " . 
			"union all " . 
			"Select 'D' caso , fInicio, fFin from sia_diasinhabiles Where :par9<fInicio and fFin<:par10 ";
		
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(
			array(':par1'=> $f1, ':par2'=> $f2, ':par3'=> $f2, 
			':par4'=> $f1, ':par5'=> $f2, 
			':par6'=> $f1, ':par7'=> $f1, ':par8'=> $f2, 
			':par9'=> $f1,':par10'=> $f1 ));	
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			//echo "F1:" . $f1 . " F2:" . $f2;
			echo json_encode($result);			
			
			}catch (OException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
			
			
		
	});		




	
	
	
?>