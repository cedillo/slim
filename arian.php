<?php
include("src/conexion.php");

 try{
		  $db = new PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
	 }catch (PDOException $e) {
	  	print "ERROR: " . $e->getMessage() . "<br><br>HOSTNAME: " . $hostname . " BD:" . $database . " USR: " . $username . " PASS: " . $password . "<br><br>";
	  	die();
	 }



// auditorias ACCH 23-05-2016 INICIO

	//lista de auditoria
		$app->get('/auditoriasBYauditores/:auditoria', function($auditoria)    use($app, $db) {
		$area = $_SESSION["idArea"];

		$sql="SELECT Case When e.idEmpleado=aa.idAuditor  Then 'SI' Else 'NO' End As asignado, e.idEmpleado, aa.idAuditor, e.idArea, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor,ISNULL(pu.nombre,'') puesto, Case When aa.lider='SI' Then aa.lider Else '' End As lider " .
			"FROM sia_empleados e " .
			"LEFT JOIN sia_usuarios us on e.idEmpleado = us.idEmpleado " .
			"LEFT JOIN sia_auditoriasauditores aa on e.idEmpleado=aa.idAuditor  and aa.idAuditoria=:auditoria " .
  			"LEFT JOIN sia_puestos pu on e.idPuesto=pu.idPuesto " .
			"WHERE e.idArea=:area and e.idNivel not in ('45.0', '40.0', '31.0') " .
			"ORDER BY concat(e.nombre, ' ', e.paterno, ' ', e.materno);"; 
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area, ':auditoria' => $auditoria));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/fehanoti/:fConfronta', function($fConfronta)    use($app, $db) {

		$sql="SELECT dateadd(day,-10, :fConfronta) AS fecha;";
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':fConfronta' => $fConfronta));
		$result = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/feIFA/:fecha', function($fecha)    use($app, $db) {

		$sql="SELECT dateadd(day,20,:fecha) AS mas";
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':fecha' => $fecha));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	//validar fecha de IRA
	$app->get('/valira/:auditoria/:fase', function($auditoria,$fase)    use($app, $db) {

		$sql="SELECT au.fIRA IRA , au.fIFA IFA, aa.fFin fin FROM sia_auditoriasactividades aa " .
 			"INNER JOIN sia_auditorias au on aa.idAuditoria=au.idAuditoria " .
 			"WHERE aa.idAuditoria=:auditoria AND aa.idFase=:fase;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':auditoria' => $auditoria,':fase'=> $fase));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});




 //Modificar Cronograma
 	$app->get('/modifcronograma/:id/:aud',  function($id,$aud)  use($app, $db) {


 		$sql="SELECT aa.idActividad id, isnull(aa.idApartado,'') apartado, aa.idFase fase,aa.fInicio inicio,aa.fFin fin,Case WHEN aa.diasEfectivos is null THEN 0 ELSE aa.diasEfectivos END AS defec,aa.estatus esta " .
			"FROM sia_auditoriasactividades aa " .
	  		"INNER JOIN sia_fases f on aa.idFase=f.idFase " .
    		"INNER JOIN sia_apartados ap on aa.idApartado = ap.idApartado " .
			"LEFT JOIN sia_usuarios us on aa.idResponsable=us.idEmpleado " .
 			"WHERE aa.idAuditoria=:aud and aa.idActividad=:id;";

		
 		$dbQuery = $db->prepare($sql);		
 		$dbQuery->execute(array(':id' => $id, ':aud' => $aud));
 		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
 		if(!$result){
 			$app->halt(404, "RECURSO NO ENCONTRADA.");			
 		}else{		
 			echo json_encode($result);
 		}		
 	});




	

		//lista de equipo de trabajo
		$app->get('/yyy/:auditoria/:objeto', function($auditoria, $objeto)    use($app, $db) {
		$area = $_SESSION["idArea"];
		
		$sql="SELECT e.idEmpleado id, aa.idAuditor, e.idArea, aa.lider lider,concat(e.nombre, ' ', e.paterno, ' ', e.paterno) texto, p.nombre plaza
		from sia_empleados e
		left join sia_auditoriasauditores aa on e.idEmpleado=aa.idAuditor 
		left join sia_plazas p on e.idPlaza=p.idPlaza
		Where e.idArea=:area and   aa.idAuditoria=:auditoria
		ORDER BY lider DESC";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area, ':auditoria' => $auditoria));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


		//lista de equipo de trabajo
		$app->get('/auditoriasByLider/:auditoria', function($auditoria)    use($app, $db) {
		$area = $_SESSION["idArea"];
		
		$sql="SELECT e.idEmpleado id, aa.idAuditor, e.idArea, isnull(aa.lider,'') lider,concat(e.nombre, ' ', e.paterno, ' ', e.materno) texto, isnull(pu.nombre,'') puesto " .
			"from sia_empleados e " .
			"left join sia_auditoriasauditores aa on e.idEmpleado=aa.idAuditor " .
			"LEFT JOIN sia_puestos pu on e.idPuesto=pu.idPuesto " .
			"Where e.idArea=:area and   aa.idAuditoria=:auditoria " .
			"ORDER BY lider DESC;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area, ':auditoria' => $auditoria));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	//lista de auditoria fases
	$app->get('/lstauditoriaByFases/:id', function($id)    use($app, $db) {
		$sql="SELECT f.nombre texto, orden, min(aa.fInicio ) desde, max(aa.fFin) hasta, sum(convert(int,aa.diasEfectivos)) dia " .
			"FROM sia_auditoriasactividades aa " .
			"INNER JOIN sia_fases f on aa.idFase = f.idFase " .
			"WHERE aa.idAuditoria=:id " .
			"Group by f.nombre, orden " .
			"order by f.orden";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	//lista de auditoria fehas reprogramadas
	$app->get('/lstReprograbyfecha/:auditoria', function($auditoria)    use($app, $db) {
		$sql="SELECT u.nombre unidad, f.nombre texto, orden,min(ar.fIniReprogramacion) desde, max(ar.fFinReprogramacion ) hasta, sum(convert(int,ar.diasReprogramados)) dia " .
			"FROM sia_AuditoriasReprogramaciones ar " .
			"INNER JOIN sia_fases f on ar.idFase = f.idFase " .
    		"INNER JOIN sia_unidades u on ar.idSector = u.idSector AND ar.idSubsector = u.idSubsector AND ar.idUnidad = u.idUnidad " .
			"WHERE ar.idAuditoria=:auditoria and ar.fFinReprogramacion is not null " .
			"Group by f.nombre, orden,u.nombre " .
			"order by f.orden;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':auditoria' => $auditoria));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/btnfases/:auditoria', function($auditoria)    use($app, $db) {
		$sql="SELECT aa.idFase fase FROM sia_auditoriasactividades aa where aa.idAuditoria=:auditoria order by aa.idFase ASC;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':auditoria' => $auditoria));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});






	//lista de papeles
	$app->get('/lstpapeles/:id', function($id)    use($app, $db) {

		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$rpe = $_SESSION["idEmpleado"];		
		$usrActual = $_SESSION["idUsuario"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){
			$sql="Select a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, u.nombre sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, p.idPapel papel, tp.nombre sPapel, p.fPapel fecha,
			p.idFase fase, p.tipoResultado tresultado, p.resultado resul, p.archivoOriginal, p.archivoFinal aerchivo,  p.estatus estatus " .
			"FROM sia_papeles p  " .
			"inner join  sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma = a.idPrograma and p.idAuditoria = a.idAuditoria " .
			"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
			"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
			"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta " .

			//Linea nueva para filtrar por rol-etapa
			"and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " . 
			
			"Order by p.fAlta desc ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':usrActual' => $usrActual));
		}else{
			$sql="Select a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, u.nombre sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto, ta.nombre tipoAuditoria, p.idPapel papel, tp.nombre sPapel, p.fPapel fecha,
			p.idFase fase, p.tipoResultado tresultado, p.resultado resul, p.archivoOriginal, p.archivoFinal aerchivo,  p.estatus estatus " .
			"FROM sia_papeles p  " .
			"inner join  sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma = a.idPrograma and p.idAuditoria = a.idAuditoria " .
			"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
			"inner join sia_tipospapeles tp on p.tipoPapel=tp.idTipoPapel " .
			"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta  and a.idArea=:area and aa.idAuditor=:audito " . 
			
			//Linea nueva para filtrar por rol-etapa
			"and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " . 
			
			"Order by p.fAlta desc ";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area, ':usrActual' => $usrActual, ':audito' => $rpe));
		}








		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


//lista de actividades
	$app->get('/lstActividades/:id', function($id)    use($app, $db) {
		$rpe = $_SESSION["idEmpleado"];
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){			
			$sql=" SELECT a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, u.nombre sujeto, ta.nombre tipoAuditoria, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor, aa.idAvance id, f.nombre fase, fa.nombre actividad, aa.porcentaje porcen " .
			"FROM sia_auditoriasavances aa " .
				"inner join sia_auditorias a  on aa.idCuenta=a.idCuenta and aa.idPrograma = a.idPrograma and aa.idAuditoria = a.idAuditoria " .
				//"left join sia_auditoriasauditores aaud  on aaud.idCuenta=a.idCuenta and aaud.idPrograma = a.idPrograma and aaud.idAuditoria = a.idAuditoria and aaud.idAuditor=aa.idAuditor " .
				"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
				"left join sia_empleados e on aa.idAuditor=e.idEmpleado " .
				"inner join sia_fases f on f.idFase=aa.idFase " .
				"inner join sia_auditoriasactividades aact  on aa.idActividad = aact.idAuditoriasAuditores " .
				"inner join sia_fasesactividades fa  on aact.idFase=fa.idFase and aact.idActividad=fa.idActividad " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta " .
			"order by aa.idAvance desc";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));
		}else{
			$sql="SELECT a.idAuditoria, COALESCE(clave, convert(varchar,a.idAuditoria)) claveAuditoria, u.nombre sujeto, ta.nombre tipoAuditoria, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor, aa.idAvance id, f.nombre fase, fa.nombre actividad, aa.porcentaje porcen " .
			"FROM sia_auditoriasavances aa " .
				"inner join sia_auditorias a  on aa.idCuenta=a.idCuenta and aa.idPrograma = a.idPrograma and aa.idAuditoria = a.idAuditoria " .
				"left join sia_auditoriasauditores aaud  on aaud.idCuenta=a.idCuenta and aaud.idPrograma = a.idPrograma and aaud.idAuditoria = a.idAuditoria and aaud.idAuditor=aa.idAuditor " .
				"inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
				"left join sia_empleados e on aa.idAuditor=e.idEmpleado " .
				"inner join sia_fases f on f.idFase=aa.idFase " .
				"inner join sia_auditoriasactividades aact  on aa.idActividad = aact.idAuditoriasAuditores " .
				"inner join sia_fasesactividades fa  on aact.idFase=fa.idFase and aact.idActividad=fa.idActividad " .
				"inner join sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria " .
			"Where a.idCuenta=:cuenta and a.idArea=:area and aa.idAuditor=:audito " .
			"order by aa.idAvance desc";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta, ':area' => $area, ':audito' => $rpe));		
		}
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



 //Modificar Cronograma
 	$app->get('/modifapartado/:auditoria/:apartado',  function($auditoria,$apartado)  use($app, $db) {


 		$sql="SELECT AA.idAuditoria auditoria,AA.idFase fase,idFase fase,ap.nombre,AA.idApartado apartado,AA.actividad FROM sia_AuditoriasApartados AA
  			INNER JOIN sia_apartados ap on AA.idApartado=ap.idApartado
  			WHERE AA.idAuditoria=:auditoria AND AA.idApartado = :apartado;";

		
 		$dbQuery = $db->prepare($sql);		
 		$dbQuery->execute(array(':apartado' => $apartado, ':auditoria' => $auditoria));
 		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
 		if(!$result){
 			$app->halt(404, "RECURSO NO ENCONTRADA.");			
 		}else{		
 			echo json_encode($result);
 		}		
 	});








//lista de presuúesto
	$app->get('/lstpresupuesto/:id', function($id)    use($app, $db) {
		$sql="SELECT idAuditoria, tipoPresupuesto from sia_auditorias " .
		"WHERE pa.idAuditoria=:id";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	//lista de acompañamiento
	$app->get('/lstacompa/:id', function($id)    use($app, $db) {
		$sql="SELECT tp.idTipoPapel numero, tp.nombre tipo, pa.resultado resul, CONCAT(us.nombre,' ',us.paterno,' ',us.materno) texto, pa.fAlta fecha, pa.estatus estatus,pa.archivoOriginal anexo " .
		"FROM sia_papeles pa " .
		"LEFT JOIN sia_tipospapeles tp on pa.tipoPapel=tp.idTipoPapel " .
		"LEFT JOIN sia_usuarios us on pa.usrAlta=us.idUsuario " .
		"WHERE pa.idAuditoria=:id";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/lstAvancesActividad/:id', function($id) use($app, $db){
		$cuenta = $_SESSION["idCuentaActual"];
		//$area = $_SESSION["idArea"];
		
		$sql="SELECT a.idAuditoria, u.nombre sujeto, a.tipoAuditoria, concat(e.nombre, ' ', e.paterno, ' ', e.materno) auditor, aa.idAvance, f.nombre fase, fa.nombre actividad, aa.porcentaje " .
		"FROM sia_auditoriasavances aa " .
			"left join sia_auditorias a  on aa.idCuenta=a.idCuenta and aa.idPrograma = a.idPrograma and aa.idAuditoria = a.idAuditoria " .
			"left join sia_auditoriasauditores aaud  on aaud.idCuenta=a.idCuenta and aaud.idPrograma = a.idPrograma and aaud.idAuditoria = a.idAuditoria " .
			"left join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) " .
			"left join sia_empleados e on aaud.idAuditor=e.idEmpleado " .
			"left join sia_fases f on f.idFase=aa.idFase " .
			"left join sia_auditoriasactividades aact  on aa.idActividad = aact.idAuditoriasAuditores " .
			"left join sia_fasesactividades fa  on aact.idFase=fa.idFase and aact.idActividad=fa.idActividad " .
		"Where a.idCuenta=:cuenta and a.idArea=:id " .
		"order by aa.idAvance desc";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}		
	});



	$app->get('/dashauditorias', function()  use($app, $db) {
		$area = $_SESSION["idArea"];
		$sql="SELECT a.idAuditoria auditoria, ar.nombre area, u.nombre sujeto, o.nombre objeto, a.tipoAuditoria tipo, '0.00' avances, a.idProceso proceso, a.idEtapa etapa  
				FROM sia_programas p 
				INNER JOIN sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma=a.idPrograma 
				LEFT JOIN sia_areas ar on a.idArea=ar.idArea 
				LEFT JOIN sia_unidades u on a.idCuenta = u.idCuenta and a.idSector=u.idSector and a.idUnidad=u.idUnidad
				LEFT JOIN sia_objetos o on a.idObjeto=o.idObjeto and a.idCuenta=o.idCuenta and a.idPrograma=o.idPrograma and a.idAuditoria=o.idAuditoria
				WHERE ar.idArea=:area
				ORDER BY ar.nombre, u.nombre, o.nombre, a.tipoAuditoria ";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS.");
		}else{
			$app->render('auditorias.php', $result);
		}
	});


	

	//lista de auditoria-FASE
	$app->get('/lstFasesActividad', function()    use($app, $db) {
			$cuenta = $_SESSION["idCuentaActual"];
		$sql="SELECT idFase id, nombre texto FROM sia_fases WHERE idCuenta=:cuenta ORDER BY  orden";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});
	




			//Lista de DES-FASE
	$app->get('/lstAuByfase/:fase', function($fase)    use($app, $db) {
		
		$sql=" SELECT aa.idFase fase, fa.nombre actividad, aa.fInicio inicio, aa.fFin fin, aa.porcentaje, aa.idPrioridad prioridad
			FROM sia_auditoriasactividades aa inner join sia_fasesactividades fa on aa.idFase = fa.idFase and aa.idActividad = fa.idActividad
			WHERE aa.idAuditoria=:auditoria and aa.idFase=:fase";
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':auditoria' => $auditoria, ':fase' => $fase));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});




			//Lista de DES-FASE
	$app->get('/lstFaseByDescripciones', function()    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];

		$sql="SELECT idActividad id , nombre texto FROM sia_fasesactividades WHERE  idCuenta=:cuenta  and estatus='ACTIVO' ORDER BY idActividad";
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});







		$app->get('/lstFaseByDescripcion/:fase', function($fase)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];

		$sql="SELECT idActividad id , nombre texto FROM sia_fasesactividades WHERE  idCuenta=:cuenta AND  idFase=:fase and estatus='ACTIVO' ORDER BY idActividad";
	

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':fase' => $fase));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	//lista de responsable-auditor
		$app->get('/lstResponByauditor/:area', function($area)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];


		$sql="SELECT idEmpleado id, idArea, concat(nombre, ' ', paterno, ' ', materno) texto
  			FROM sia_empleados WHERE idArea=:area 
  			ORDER BY concat(nombre, ' ', paterno, ' ', materno);";
		
		// $sql=" SELECT e.idEmpleado id, e.idArea, concat(e.nombre, ' ', e.paterno, ' ', e.materno) texto
		// FROM sia_empleados e
		// LEFT JOIN sia_plazas p on e.idPlaza=p.idPlaza
		// WHERE e.idArea=:area AND e.idNivel in ('1.1', '2.1', '3.1', '4.1', '5.1')
		// ORDER BY concat(e.nombre, ' ', e.paterno, ' ', e.materno) ";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':area' => $area));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


		//lista de responsable-auditor
		$app->get('/lstResponByauditores', function()    use($app, $db) {
		
		
		$sql=" SELECT e.idEmpleado id, e.idArea, concat(e.nombre, ' ', e.paterno, ' ', e.materno) texto
		FROM sia_empleados e
		LEFT JOIN sia_plazas p on e.idPlaza=p.idPlaza
		WHERE e.idNivel in ('1.1', '2.1', '3.1', '4.1', '5.1')
		ORDER BY concat(e.nombre, ' ', e.paterno, ' ', e.materno) ";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


		//lista de actividades por auditoria
	$app->get('/tblActividadesByAuditoria/:auditoria', function($auditoria)    use($app, $db) {
		$sql="SELECT aa.idAuditoria audi,aa.idFase fase,ap.nombre,aa.fInicio inicio,aa.fFin fin,Case When aa.diasEfectivos is null THEN 0 ELSE aa.diasEfectivos END AS efectivos,Case WHEN aa.porcentaje is null THEN 0 ELSE aa.porcentaje END AS porcentaje,aa.idPrioridad prioridad, aa.idActividad idA " .
			"FROM sia_auditoriasactividades aa " .
  			"INNER JOIN sia_apartados ap on aa.idApartado = ap.idApartado " .
  			"INNER JOIN sia_fases f on aa.idFase=f.idFase " .
			"WHERE aa.idAuditoria=:auditoria ORDER BY f.orden;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':auditoria' => $auditoria));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


			//lista de apartado por auditoria
	$app->get('/tblApartadoByAuditoria/:id', function($id)    use($app, $db) {
		$sql="SELECT aa.idApartado id, ap.nombre apartado ,aa.idAuditoria auditoria, aa.actividad from sia_AuditoriasApartados aa 
 			LEFT JOIN sia_apartados ap on aa.idApartado = ap.idApartado  where aa.idAuditoria=:id ORDER BY ap.orden ASC;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 

	

	//lista de actividades por auditoria
	$app->get('/lstActividades/:id', function($id)    use($app, $db) {
		$sql=" SELECT aa.idAuditoria audi,aa.idFase fase, fa.nombre actividad, aa.fInicio inicio, aa.fFin fin, aa.diasEfectivos efectivos,aa.porcentaje, aa.idPrioridad prioridad, aa.idActividad idA
			FROM sia_auditoriasactividades aa 
      		INNER JOIN sia_fasesactividades fa on aa.idFase = fa.idFase and aa.idActividad = fa.idActividad 
      		INNER JOIN sia_fases f on fa.idFase=f.idFase
      		WHERE fa.idActividad='1'";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 








	//lista de actividades por auditoria
	$app->get('/tblActividadesByFases/:fase', function($fase)    use($app, $db) {
		$sql="SELECT aa.idFase texto, fa.nombre actividad, aa.fInicio inicio, aa.fFin fin, aa.porcentaje, aa.idPrioridad prioridad  " .
			"FROM sia_auditoriasactividades aa inner join sia_fasesactividades fa on aa.idFase = fa.idFase and aa.idActividad = fa.idActividad ".
			"WHERE aa.idFase=:fase";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':fase' => $fase));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 


	//Lista actividad actividadPrevia
	$app->get('/lstActividaByPrevia/:auditoria', function($auditoria)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		


		$sql="SELECT aa.idActividad id, fa.nombre texto FROM sia_auditoriasactividades aa " .
		"inner join sia_fasesactividades fa on aa.idFase = fa.idFase and aa.idActividad = fa.idActividad " .
			"WHERE fa.idCuenta=:cuenta and aa.idAuditoria=:auditoria";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':auditoria' => $auditoria));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


//lista sectores-auditorias
	$app->get('/lstSectoresaudi', function()    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];

		$sql="SELECT ltrim(idSector) id, ltrim(nombre) texto FROM sia_sectores Where idCuenta=:cuenta ORDER BY nombre";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});





	//Lista de subsector-unidad
	$app->get('/lstSubsectoresByunidad/:sector', function($sector)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];

		$sql="SELECT ltrim(idSubsector) id, ltrim(nombre) texto FROM sia_Unidades Where idCuenta=:cuenta and idSector=:sector ORDER BY nombre";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':sector' => $sector));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});






$app->get('/lstAuditoriaByAr/:id', function($id)    use($app, $db) {
		$dbQuery = $db->prepare($sql);
		$sql="SELECT idCuenta cuenta, idPrograma programa, idAuditoria auditoria,  tipoAuditoria tipo, idArea area, CONCAT(idSector,' ', idSubsector,' ', idUnidad) unidad, idObjeto objeto, objetivo, alcance, justificacion, idProceso proceso, idEtapa etapa  FROM sia_auditorias  WHERE idAuditoria=:id";


		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	//lista de Subsectores
	$app->get('/lstSubsectores', function()    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];

		$sql="SELECT ltrim(idSector) id, ltrim(nombre) texto FROM sia_sectores Where idCuenta=:cuenta ORDER BY nombre";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

//lista de objetos
		$app->get('/lstObjetosByEnables/', function()  use($app, $db) {

		$sql = "SELECT idObjeto id, nombre texto FROM sia_objetos Where idObjeto not in (Select idObjeto from sia_auditorias) order by nivel, nombre";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON OBJETOS DE FISCALIZACIÓN. ");
		}else{
			echo json_encode($result);
		}
	});


	//Listar objetos by unidad
	$app->get('/lstObjetosByUnidad/:id', function($id)  use($app, $db) {

		$sql="SELECT idObjeto id, nombre texto FROM sia_objetos Where idUnidad=:id order by nombre";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON OBJETOS DE FISCALIZACIÓN. ");
		}else{
			echo json_encode($result);
		}
	});

//guardar auditorias-auditores
$app->get('/guardar/equipo/lider/:oper/:datos',  function($oper, $datos)  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		//$cuenta = $_SESSION["idCuentaActual"];
		//$programa = $_SESSION["idProgramaActual"];
		$ciclo=0;
		try{
			if($datos<>""){
			
			$registros = explode("*", $datos);
			foreach($registros as $registro) {     
		     	$campo = explode("|", $registro);
				$cuenta=$campo[0];
				$programa=$campo[1];
				$auditoria=$campo[2];
				$empleado=$campo[3];
				$lider=$campo[4];
				echo $datos;

				if($ciclo==0) {
					//Elimina
					$sql="DELETE FROM sia_auditoriasauditores WHERE idCuenta=:cuenta and idPrograma=:programa and idAuditoria=:auditoria";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria));
					$ciclo=1;


				}
				//Inserta		
				$sql="INSERT INTO sia_auditoriasauditores (idCuenta, idPrograma, idAuditoria, idAuditor, lider, usrAlta, fAlta, estatus) " .
						"values(:cuenta, :programa, :auditoria, :empleado,:lider,:usrAlta,getdate(),'ACTIVO')";
				$dbQuery = $db->prepare($sql);

				$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':empleado' => $empleado,':lider' => $lider,':usrAlta' => $usrActual));
    
		    }			

		    echo "OK";

		}
		else{
			echo ("NO");
		}
	}catch (PDOException $e) {
			print "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}

	});



//guardar actividad auditor
/*$app->get('/guardar/auditoria/actividad/:oper/:cadena',  function($oper, $cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$cuenta=$dato[0];
				$programa=$dato[1];
				$auditoria=$dato[2];
				$actividad=$dato[3];
				$fase=$dato[4];
				$idapartado=$dato[5];
				$activida=$dato[6];
				
				$inicio = date_create($dato[7]);
				$inicio = $inicio->format('Y-m-d');

				$fin = date_create($dato[8]);
				$fin = $fin->format('Y-m-d');

				$activi=$dato[9];


				$porcentaje=$dato[10];
				$prioridad=$dato[11];
				$impacto=$dato[12];
				$responsable=$dato[13];
				$estatus=$dato[14];
				$notas=strtoupper($dato[15]);





				if ($oper=='INS-ACT')
				{

					if($inicio=='' || $fin==''){
						$sql="INSERT INTO sia_auditoriasactividades (" .
						"idCuenta, idPrograma, idAuditoria, idFase, idTipo, fInicio,fFin, porcentaje, idPrioridad, idImpacto, notas, idResponsable, usrAlta, fAlta, estatus,diasEfectivos, actividad,idApartado) " .
						"values(:cuenta, :programa, :auditoria, :fase,'SIMPLE','', '',:porcentaje, :prioridad, :impacto,:notas,:responsable,:usrAlta,getdate(),'ACTIVO',:activi,:activida,:idapartado)";

						$dbQuery = $db->prepare($sql);

						$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':fase' => $fase,':porcentaje' => $porcentaje,':prioridad' => $prioridad,':impacto' => $impacto,':notas' => $notas,':responsable' => $responsable,':usrAlta' => $usrActual,':activi' => $activi,':activida' => $activida,':idapartado' => $idapartado));

					echo "GLOBAL-AREA <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria fase=$fase inicio=$inicio fin=$fin porcentaje=$porcentaje  prioridad=$prioridad impacto=$impacto notas=$notas responsable =$responsable usrAlta=$usrActual activi=$activi actividad=$actividad idapartado=$idapartado";

					}else{

					$sql="INSERT INTO sia_auditoriasactividades (" .
					"idCuenta, idPrograma, idAuditoria, idFase, idTipo, fInicio,fFin, porcentaje, idPrioridad, idImpacto, notas, idResponsable, usrAlta, fAlta, estatus,diasEfectivos, actividad,idApartado) " .
					"values(:cuenta, :programa, :auditoria, :fase,'SIMPLE',:inicio, :fin,:porcentaje, :prioridad, :impacto,:notas,:responsable,:usrAlta,getdate(),'ACTIVO',:activi,:activida,:idapartado)";

					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':fase' => $fase,':inicio' => $inicio,':fin' => $fin,':porcentaje' => $porcentaje,':prioridad' => $prioridad,':impacto' => $impacto,':notas' => $notas,':responsable' => $responsable,':usrAlta' => $usrActual,':activi' => $activi,':activida' => $activida,':idapartado' => $idapartado));

					echo "GLOBAL <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria fase=$fase inicio=$inicio fin=$fin porcentaje=$porcentaje  prioridad=$prioridad impacto=$impacto notas=$notas responsable =$responsable usrAlta=$usrActual activi=$activi actividad=$actividad idapartado=$idapartado";
					}

					//echo "OK";
				}
			}
			else{
				echo ("NO");
			}
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
});*/


//guardar actividad auditor
	$app->post('/guardar/auditoria/actividad',  function()  use($app, $db) {
		$usrActual = $_POST["txtUsuario"];
		$cuenta = $_POST["txtCuenta"];
		$programa = $_POST["txtPrograma"];
		$id = $_POST["txtIDActividad"];
		$oper = $_POST["txtOperacion"];
		$auditoria = $_POST["txtaudi"]; // The file name
		$FaseActividad = $_POST["txtFaseActividad"];
		$Apartado = $_POST["txtApartado"];
		$DescripcionActividad = $_POST["txtDescripcionActividad"];
		$DescripcionActividadCom = $_POST["txtDescripcionActividadCom"];
		$inicioactividad = $_POST["txtInicioActividad"];
		$Finactividad  = $_POST["txtFinActividad"];
		$diasactividad = $_POST["txtDiasActividad"];
		$Porcentaje = $_POST['txtPorcentajeActividad'];
		$Prioridad = $_POST['txtPrioridadActividad'];
		$Impacto = $_POST['txtImpactoActividad'];
		$ResponsableActividad = $_POST['txtResponsableActividad'];
		$EstatusActividad = $_POST['txtEstatusActividad'];
		$NotasActividad = $_POST['txtNotasActividad'];


		try{
			if($oper=='INS'){
				$sql="INSERT INTO sia_auditoriasactividades (idCuenta,idPrograma,idAuditoria,idFase,idTipo,fInicio,fFin,porcentaje,idPrioridad
	  				,idImpacto,notas,idResponsable,usrAlta,fAlta,estatus,diasEfectivos,actividad,idApartado,actividad2) 
	  				VALUES (:cuenta,:programa,:auditoria,:FaseActividad,'SIMPLE',:inicioactividad,:Finactividad,:Porcentaje,:Prioridad
	  				,:Impacto,:NotasActividad,:ResponsableActividad,:usrActual,getdate(),:EstatusActividad,:diasactividad,:DescripcionActividad,:Apartado,:DescripcionActividadCom);";

						$dbQuery = $db->prepare($sql);

						$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':FaseActividad' => $FaseActividad,':inicioactividad' => $inicioactividad,':Finactividad' => $Finactividad,':Porcentaje' => $Porcentaje,':Prioridad' => $Prioridad,':Impacto' => $Impacto,':NotasActividad' => $NotasActividad,':ResponsableActividad' => $ResponsableActividad,':usrActual' => $usrActual,':EstatusActividad' => $EstatusActividad,':diasactividad' => $diasactividad,':DescripcionActividad' => $DescripcionActividad,':Apartado' => $Apartado,':DescripcionActividadCom' => $DescripcionActividadCom));

						echo "INSERTAR <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria FaseActividad=$FaseActividad inicioactividad=$inicioactividad Finactividad=$Finactividad Porcentaje=$Porcentaje  Prioridad=$Prioridad Impacto=$Impacto NotasActividad=$NotasActividad ResponsableActividad =$ResponsableActividad usrAlta=$usrActual estatus=$EstatusActividad diasactividad=$diasactividad DescripcionActividad=$DescripcionActividad Apartado=$Apartado DescripcionActividadCom=$DescripcionActividadCom";

						}else{
							$sql="UPDATE sia_auditoriasactividades SET idFase=:FaseActividad, fInicio=:inicioactividad,fFin=:Finactividad, porcentaje=:Porcentaje, idPrioridad=:Prioridad,idImpacto=:Impacto, notas=:NotasActividad, idResponsable=:ResponsableActividad, usrModificacion=:usrActual, fModificacion=getdate() ,estatus=:EstatusActividad,diasEfectivos=:diasactividad, actividad=:DescripcionActividad,idApartado=:Apartado,actividad2=:DescripcionActividadCom WHERE idActividad =:id;";


							$dbQuery = $db->prepare($sql);

							$dbQuery->execute(array(':FaseActividad' => $FaseActividad,':inicioactividad' => $inicioactividad,':Finactividad' => $Finactividad,':Porcentaje' => $Porcentaje,':Prioridad' => $Prioridad,':Impacto' => $Impacto,':NotasActividad' => $NotasActividad,':ResponsableActividad' => $ResponsableActividad,':usrActual' => $usrActual,':EstatusActividad' => $EstatusActividad,':diasactividad' => $diasactividad,':DescripcionActividad' => $DescripcionActividad,':Apartado' => $Apartado,':DescripcionActividadCom' => $DescripcionActividadCom,':id' => $id));

						
							echo "ACTUALIZAR <hr> $sql <br> <br>FaseActividad=$FaseActividad inicioactividad=$inicioactividad Finactividad=$Finactividad Porcentaje=$Porcentaje  Prioridad=$Prioridad Impacto=$Impacto NotasActividad=$NotasActividad ResponsableActividad =$ResponsableActividad usrAlta=$usrActual estatus=$EstatusActividad diasactividad=$diasactividad DescripcionActividad=$DescripcionActividad Apartado=$Apartado DescripcionActividadCom=$DescripcionActividadCom id=$id";
							//echo "OK";

						}
			}catch (PDOException $e) {
				echo  "<br>Error de BD: " . $e->getMessage();
				die();
			}		
	});




	$app->get('/actualizar/auditoria/actividad/:oper/:cadena',  function($oper, $cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$cuenta=$dato[0];
				$programa=$dato[1];
				$auditoria=$dato[2];
				$actividad=$dato[3];
				$fase=$dato[4];
				$idapartado=$dato[5];
				$activida=$dato[6];
				
				$inicio = date_create($dato[7]);
				$inicio = $inicio->format('Y-m-d');

				$fin = date_create($dato[8]);
				$fin = $fin->format('Y-m-d');

				$activi=$dato[9];
				$porcentaje=$dato[10];
				$prioridad=$dato[11];
				$impacto=$dato[12];
				$responsable=$dato[13];
				$estatus=$dato[14];
				$notas=strtoupper($dato[15]);
			

				if ($oper=='UPD')
				{
					
					$sql="UPDATE sia_auditoriasactividades SET idFase=:fase, fInicio=:inicio,fFin=:fin, porcentaje=:porcentaje, idPrioridad=:prioridad,idImpacto=:impacto, notas=:notas, idResponsable=:responsable, usrModificacion=:usrAlta, fModificacion=getdate() ,estatus=:estatus,diasEfectivos=:activi, actividad=:activida,idApartado=:idapartado WHERE idActividad =:actividad;";


					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':fase' => $fase,':inicio' => $inicio,':fin' => $fin,':porcentaje' => $porcentaje,':prioridad' => $prioridad,':impacto' => $impacto,':notas' => $notas,':responsable' => $responsable,':usrAlta' => $usrActual,':estatus' => $estatus,':activi' => $activi,':actividad' => $actividad,':activida' => $activida,':idapartado' => $idapartado));
					//echo "GLOBAL-AREA <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria fase=$fase inicio=$inicio fin=$fin porcentaje=$porcentaje  prioridad=$prioridad impacto=$impacto notas=$notas responsable =$responsable usrAlta=$usrActual activi=$activi actividad=$actividad idapartado=$idapartado";
					echo "OK";
				}
				else {

				}
			}
			else{
				echo ("NO");
			}
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});



	//guardar Notas, placeholder
	$app->get('/guardar/fecha/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$cuenta=$dato[0];
				$auditoria=$dato[1];
				$programa=$dato[2];
				$fase=$dato[3];
				$feInicio = date_create($dato[4]);
				$feInicio = $feInicio->format('Y-m-d');
				
				$feFin = date_create($dato[5]);
				$feFin = $feFin->format('d-m-Y');
				$habiles=$dato[6];
				$fIFA=$dato[7];
				$fIRA=$dato[8];
				$oper=$dato[9];
				//$id=$dato[8];
				
				if($oper=='INS'){

					$sql="INSERT INTO sia_auditoriasactividades(idCuenta,idPrograma,idAuditoria,idFase,fInicio,fFin,diasEfectivos,idPrioridad,idImpacto,idResponsable,usrAlta,fAlta,estatus,idApartado) 
						VALUES (:cuenta,:programa,:auditoria,:fase,:feInicio,:feFin,:habiles,'SIMPLE','MEDIO',3132,:usrActual,getdate(),'ACTIVO','PRUEBAS');";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':fase' => $fase,':feInicio' => $feInicio,':feFin' => $feFin,':habiles' => $habiles,':usrActual' => $usrActual));


					// verificar fase
					$dbQuery = $db->prepare("SELECT case when idFase='EJECUCION' then 'SI' else 'NO' end validacion, case when idFase='EJECUCION' then 'SI' else 'NO' end valaudi FROM sia_auditoriasactividades where idFase=:fase AND idAuditoria=:auditoria;");
					$dbQuery->execute(array(':fase' => $fase,':auditoria' => $auditoria));				
					$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
					$validacion = $result ['validacion'];
					$valaudi = $result ['valaudi'];
					//echo "$validacion: " . $validacion ." --- ";

					if($validacion=='SI'){
						$sql="UPDATE sia_auditoriasunidades SET fConfronta=:feFin, usrModificacion=:usrActual, fModificacion=getdate() WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':feFin'=> $feFin,':usrActual'=> $usrActual,':auditoria'=> $auditoria));	
						
					//	echo $sql ."<hr>feFin: " . $feFin ."<hr>usrActual: " . $usrActual ."<hr>auditoria: " . $auditoria . "<br>";
						

						$sql="UPDATE sia_auditorias SET fModificacion=getdate(),usrModificacion=:usrActual,fIRA=:fIRA,fIFA=:fIFA WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':usrActual'=> $usrActual,':fIRA'=> $fIRA,':fIFA'=> $fIFA,':auditoria'=> $auditoria));	
					//	echo "   ------ ".$sql ."<hr>usrActual: " . $usrActual ."<hr>feFin: " . $feFin ."<hr>feFin2: " . $feFin ."<hr>auditoria: " . $auditoria ." --- ";
						echo "OK";

					//echo "OK";
					}

				}else{
					$sql="UPDATE sia_auditoriasactividades SET fInicio=:feInicio, fFin=:feFin, diasEfectivos=:habiles, usrModificacion=:usrActual, fModificacion=getdate() 
						where idAuditoria=:auditoria AND idFase=:fase;";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':feInicio' => $feInicio,':feFin' => $feFin,':habiles' => $habiles,':usrActual' => $usrActual,':auditoria' => $auditoria,':fase' => $fase));

					// verificar fase
					$dbQuery = $db->prepare("SELECT case when idFase='EJECUCION' then 'SI' else 'NO' end validacion, case when idFase='EJECUCION' then 'SI' else 'NO' end valaudi FROM sia_auditoriasactividades where idFase=:fase AND idAuditoria=:auditoria;");
					$dbQuery->execute(array(':fase' => $fase,':auditoria' => $auditoria));				
					$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
					$validacion = $result ['validacion'];
					$valaudi = $result ['valaudi'];
					//echo "$validacion: " . $validacion ." --- ";

					if($validacion=='SI'){
						$sql="UPDATE sia_auditoriasunidades SET fConfronta=:feFin, usrModificacion=:usrActual, fModificacion=getdate() WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':feFin'=> $feFin,':usrActual'=> $usrActual,':auditoria'=> $auditoria));	
						//echo $sql ."<hr>feFin: " . $feFin ."<hr>usrActual: " . $usrActual ."<hr>auditoria: " . $auditoria . "<br>";
						
						$sql="UPDATE sia_auditorias SET fModificacion=getdate(),usrModificacion=:usrActual,fIRA=:fIRA,fIFA=:fIFA WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':usrActual'=> $usrActual,':fIRA'=> $fIRA,':fIFA'=> $fIFA,':auditoria'=> $auditoria));	
						//echo "   ------ ".$sql ."<hr>usrActual: " . $usrActual ."<hr>feFin: " . $feFin ."<hr>feFin2: " . $feFin ."<hr>auditoria: " . $auditoria ." --- ";
						echo "OK";
					}else{echo "OK";}




					echo "OK";
				}
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});

	//guardar Notas, placeholder
	$app->get('/guardar/Reprofecha/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$cuenta=$dato[0];
				$auditoria=$dato[1];
				$programa=$dato[2];
				$fase=$dato[3];
				$feInicio = date_create($dato[4]);
				$feInicio = $feInicio->format('Y-m-d');
				
				$feFin = date_create($dato[5]);
				$feFin = $feFin->format('d-m-Y');
				$habiles=$dato[6];
				$fIFA=$dato[7];
				$fIRA=$dato[8];
				$oper=$dato[9];
				//$id=$dato[8];
				
				if($oper=='INS'){

					$sql="INSERT INTO sia_auditoriasactividades(idCuenta,idPrograma,idAuditoria,idFase,fInicio,fFin,diasEfectivos,idPrioridad,idImpacto,idResponsable,usrAlta,fAlta,estatus,idApartado) 
						VALUES (:cuenta,:programa,:auditoria,:fase,:feInicio,:feFin,:habiles,'SIMPLE','MEDIO',3132,:usrActual,getdate(),'ACTIVO','PRUEBAS');";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':fase' => $fase,':feInicio' => $feInicio,':feFin' => $feFin,':habiles' => $habiles,':usrActual' => $usrActual));


					// verificar fase
					$dbQuery = $db->prepare("SELECT case when idFase='EJECUCION' then 'SI' else 'NO' end validacion, case when idFase='EJECUCION' then 'SI' else 'NO' end valaudi FROM sia_auditoriasactividades where idFase=:fase AND idAuditoria=:auditoria;");
					$dbQuery->execute(array(':fase' => $fase,':auditoria' => $auditoria));				
					$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
					$validacion = $result ['validacion'];
					$valaudi = $result ['valaudi'];
					//echo "$validacion: " . $validacion ." --- ";

					if($validacion=='SI'){
						$sql="UPDATE sia_auditoriasunidades SET fConfronta=:feFin, usrModificacion=:usrActual, fModificacion=getdate() WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':feFin'=> $feFin,':usrActual'=> $usrActual,':auditoria'=> $auditoria));	
						
					//	echo $sql ."<hr>feFin: " . $feFin ."<hr>usrActual: " . $usrActual ."<hr>auditoria: " . $auditoria . "<br>";
						

						$sql="UPDATE sia_auditorias SET fModificacion=getdate(),usrModificacion=:usrActual,fIRA=:fIRA,fIFA=:fIFA WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':usrActual'=> $usrActual,':fIRA'=> $fIRA,':fIFA'=> $fIFA,':auditoria'=> $auditoria));	
					//	echo "   ------ ".$sql ."<hr>usrActual: " . $usrActual ."<hr>feFin: " . $feFin ."<hr>feFin2: " . $feFin ."<hr>auditoria: " . $auditoria ." --- ";
						echo "OK";

					//echo "OK";
					}

				}else{
					$sql="UPDATE sia_auditoriasactividades SET fInicio=:feInicio, fFin=:feFin, diasEfectivos=:habiles, usrModificacion=:usrActual, fModificacion=getdate() 
						where idAuditoria=:auditoria AND idFase=:fase;";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':feInicio' => $feInicio,':feFin' => $feFin,':habiles' => $habiles,':usrActual' => $usrActual,':auditoria' => $auditoria,':fase' => $fase));

					// verificar fase
					$dbQuery = $db->prepare("SELECT case when idFase='EJECUCION' then 'SI' else 'NO' end validacion, case when idFase='EJECUCION' then 'SI' else 'NO' end valaudi FROM sia_auditoriasactividades where idFase=:fase AND idAuditoria=:auditoria;");
					$dbQuery->execute(array(':fase' => $fase,':auditoria' => $auditoria));				
					$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
					$validacion = $result ['validacion'];
					$valaudi = $result ['valaudi'];
					//echo "$validacion: " . $validacion ." --- ";

					if($validacion=='SI'){
						$sql="UPDATE sia_auditoriasunidades SET fConfronta=:feFin, usrModificacion=:usrActual, fModificacion=getdate() WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':feFin'=> $feFin,':usrActual'=> $usrActual,':auditoria'=> $auditoria));	
						//echo $sql ."<hr>feFin: " . $feFin ."<hr>usrActual: " . $usrActual ."<hr>auditoria: " . $auditoria . "<br>";
						
						$sql="UPDATE sia_auditorias SET fModificacion=getdate(),usrModificacion=:usrActual,fIRA=:fIRA,fIFA=:fIFA WHERE idAuditoria=:auditoria;";
						$dbQuery = $db->prepare($sql);	
						$dbQuery->execute(array(':usrActual'=> $usrActual,':fIRA'=> $fIRA,':fIFA'=> $fIFA,':auditoria'=> $auditoria));	
						//echo "   ------ ".$sql ."<hr>usrActual: " . $usrActual ."<hr>feFin: " . $feFin ."<hr>feFin2: " . $feFin ."<hr>auditoria: " . $auditoria ." --- ";
						echo "OK";
					}else{echo "OK";}




					echo "OK";
				}
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});

	
	$app->get('/validarfase/:fase/:auditoria', function($fase,$auditoria)    use($app, $db) {
		
		$sql="SELECT CASE WHEN idFase is null THEN 'false' ELSE 'true' END AS valida FROM sia_auditoriasactividades WHERE idFase=:fase AND idAuditoria=:auditoria;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':fase' => $fase,':auditoria' => $auditoria));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});

	$app->get('/validarApartado/:apartado/:auditoria', function($apartado,$auditoria)    use($app, $db) {
		
		$sql="SELECT idApartado valida FROM sia_AuditoriasApartados WHERE idApartado=:apartado AND idAuditoria=:auditoria";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':apartado' => $apartado,':auditoria' => $auditoria));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});






//guardar Notas, placeholder
$app->get('/guardar/auditoria/Notas/Place/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$auditoria=$dato[0];

				
				if($dato[1]=="null"){
					$FIRA =$dato[1]; 					
				}else{
					$FIRA = date_create($dato[1]);
					$FIRA = $FIRA->format('Y-m-d');
				}

				if($dato[2]=="null"){
					$FIFA=$dato[2];	
				}else{
					$FIFA = date_create($dato[2]);
					$FIFA = $FIFA->format('Y-m-d');
				}

				$TipoObs=$dato[3];
				$observacion=strtoupper($dato[4]);
				
				if($dato[5]=="null"){
					$FAInicio=$dato[5];	
				}else{
					$FAInicio = date_create($dato[5]);
					$FAInicio = $FAInicio->format('Y-m-d');
				}

				if($dato[6]=="null"){
					$FAFinal = $dato[6];	
				}else{
					$FAFinal = date_create($dato[6]);
					$FAFinal = $FAFinal->format('Y-m-d');
				}
				
				$Dirrespon = $dato[7];
				$subrespon = $dato[8];
				
				//echo'<br>$auditoria=' . $auditoria . '   $FIRA=' . $FIRA .  '   $FIFA=' . $FIFA .  '   $TipoObs=' . $TipoObs .  '   $observacion=' . $observacion .  '   $FAInicio=' . $FAInicio .  '   $FAFinal=' . $FAFinal .  '   $Dirrespon=' . $Dirrespon .  '   $subrespon=' . $subrespon . " --- ";
							

				if($FIRA == 'null' && $FIFA == 'null' && $FAInicio == 'null' && $FAFinal =='null'){
					$sql="UPDATE sia_auditorias SET fModificacion=getdate(), tipoObservacion=:TipoObs, observacion=:observacion,idResponsable=:Dirrespon, idSubresponsable=:subrespon WHERE idAuditoria=:auditoria";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon));
					//echo "vamos aguardar:  <br> $Dirrespon <br> $subrespon <br> $TipoObs <br> $observacion";
					echo "OK";
				}else{
					if($FIRA == 'null' && $FIFA == 'null' && $FAFinal =='null'){
						$sql="UPDATE sia_auditorias SET fModificacion=getdate(), fInicio=:FAInicio, tipoObservacion=:TipoObs, observacion=:observacion,idResponsable=:Dirrespon, idSubresponsable=:subrespon WHERE idAuditoria=:auditoria";
						$dbQuery = $db->prepare($sql);
						$dbQuery->execute(array(':FAInicio' => $FAInicio,':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon));
						echo "vamos aguardar:  <br> $FAInicio <br> $Dirrespon <br> $subrespon <br> $TipoObs <br> $observacion";
						echo "OK";
					}else{
						if($FIRA == 'null' && $FIFA == 'null'){
							$sql="UPDATE sia_auditorias SET fModificacion=getdate(), fInicio=:FAInicio,fFin=:FAFinal, tipoObservacion=:TipoObs, observacion=:observacion,idResponsable=:Dirrespon, idSubresponsable=:subrespon WHERE idAuditoria=:auditoria";
							$dbQuery = $db->prepare($sql);
							$dbQuery->execute(array(':FAInicio' => $FAInicio,':FAFinal' => $FAFinal,':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon));
							//echo "vamos aguardar:  <br> $FAInicio <br> $FAFinal <br> $Dirrespon <br> $subrespon <br> $TipoObs <br> $observacion";
							echo "OK";						
						}else{
							if($FAInicio == 'null' && $FAFinal =='null'){
								$sql="UPDATE sia_auditorias SET fModificacion=getdate(), fIRA=:FIRA, fIFA=:FIFA, tipoObservacion=:TipoObs, observacion=:observacion,idResponsable=:Dirrespon, idSubresponsable=:subrespon WHERE idAuditoria=:auditoria";
								$dbQuery = $db->prepare($sql);
								$dbQuery->execute(array(':FIRA' => $FIRA,':FIFA' => $FIFA,':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon));
								//echo "vamos aguardar:  <br> $FIRA <br> $FIFA <br> $Dirrespon <br> $subrespon <br> $TipoObs <br> $observacion";
								echo "OK";
							}else{
								if($FIRA == 'null' && $FIFA == 'null' && $FAInicio == 'null'){
									$sql="UPDATE sia_auditorias SET fModificacion=getdate(), tipoObservacion=:TipoObs, observacion=:observacion, fInicio=:FAInicio,idResponsable=:Dirrespon, idSubresponsable=:subrespon,fIRA=:FIRA, fIFA=:FIFA WHERE idAuditoria=:auditoria;";
									$dbQuery = $db->prepare($sql);
									$dbQuery->execute(array(':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria, ':FAInicio' => $FAInicio,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon ,':FIRA' => $FIRA,':FIFA' => $FIFA));
									//echo "vamos aguardar:  <br> $FAFinal <br> $Dirrespon <br> $subrespon <br> $TipoObs <br> $observacion";
									echo "OK";
								}else{
									$sql="UPDATE sia_auditorias SET fModificacion=getdate(), tipoObservacion=:TipoObs, observacion=:observacion, fInicio=:FAInicio,fFin=:FAFinal,idResponsable=:Dirrespon, idSubresponsable=:subrespon,fIRA=:FIRA, fIFA=:FIFA WHERE idAuditoria=:auditoria";
									$dbQuery = $db->prepare($sql);
									$dbQuery->execute(array(':TipoObs' => $TipoObs, ':observacion' => $observacion,':auditoria' => $auditoria, ':FAInicio' => $FAInicio,':FAFinal' => $FAFinal,':Dirrespon' => $Dirrespon ,':subrespon' => $subrespon ,':FIRA' => $FIRA,':FIFA' => $FIFA));
									//echo "<hr> $sql <br> <br>TipoObs=$TipoObs  observacion=$observacion auditoria=$auditoria FAInicio=$FAInicio FAFinal=$FAFinal Dirrespon=$Dirrespon subrespon=$subrespon  FIRA=$FIRA FIFA=$FIFA";
									echo "OK";
								}		
							}
						}
					}
				}
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});









 $app->get('/tblAuditorias', function()    use($app, $db) {
 		$cuenta = $_SESSION["idCuentaActual"];
 		$area = $_SESSION["idArea"];
		
 		$usrActual = $_SESSION["idUsuario"];
 		$global = $_SESSION["usrGlobal"];
 		$programa = $_SESSION["idProgramaActual"];

 		if ($global=="SI"){
			
 			$sql="SELECT a.area, a.finan, a.fincum, a.cumpli, a.obrapub, a.desem, (a.finan + a.fincum + a.cumpli + a.obrapub + a.desem) as total FROM (" .
					"SELECT a.idArea, ar.nombre area, " .
       				"finan= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='FINANCIERA' and idArea=a.idArea),0), " .
       				"fincum= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='FINAN-CUMPL' and idArea=a.idArea),0), " .
       				"cumpli= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='CUMPLIMIENTO' and idArea=a.idArea),0), " .
       				"obrapub= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='OBRA' and idArea=a.idArea),0), " .
       				"desem= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='DESEMPENO' and idArea=a.idArea),0) " .
       				"FROM  sia_auditorias a LEFT JOIN sia_areas ar on a.idArea = ar.idArea " .
       				"WHERE a.idCuenta=:cuenta and a.idprograma=:programa and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " .
   					"GROUP BY a.idArea, ar.nombre) a";
				
 				$dbQuery = $db->prepare($sql);
 				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':usrActual' => $usrActual));
 		}else{

 			$sql="SELECT a.area, a.finan, a.fincum, a.cumpli, a.obrapub, a.desem, (a.finan + a.fincum + a.cumpli + a.obrapub + a.desem) as total FROM (" .
					"SELECT a.idArea, ar.nombre area, " .
       				"finan= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='FINANCIERA' and idArea=a.idArea),0), " .
       				"fincum= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='FINAN-CUMPL' and idArea=a.idArea),0), " .
       				"cumpli= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='CUMPLIMIENTO' and idArea=a.idArea),0), " .
       				"obrapub= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='OBRA' and idArea=a.idArea),0), " . 
       				"desem= ISNULL((Select COUNT(*) from sia_auditorias where tipoAuditoria='DESEMPENO' and idArea=a.idArea),0) " .
       				"FROM  sia_auditorias a LEFT JOIN sia_areas ar on a.idArea = ar.idArea " .
       				"WHERE a.idCuenta=:cuenta and a.idprograma=:programa and a.idArea=:area and a.idEtapa in (Select idEtapa from sia_rolesetapas re inner join sia_usuariosroles ur on ur.idRol = re.idRol  Where ur.idUsuario=:usrActual) " .
   					"GROUP BY a.idArea, ar.nombre) a";
				
 				$dbQuery = $db->prepare($sql);
 				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':area' => $area,':usrActual' => $usrActual));		
 		}

 		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
 		echo json_encode($result);
 	});



	$app->get('/lstAuditoriaByIds/:id', function($id)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		
		$usrActual = $_SESSION["idUsuario"];
		$global = $_SESSION["usrGlobal"];
		/*
		$sql="SELECT idCuenta cuenta, idPrograma programa, idAuditoria auditoria,  tipoAuditoria tipo, idArea area, idSector sector, idSubsector subSector, idUnidad unidad, idObjeto objeto, objetivo, alcance, justificacion, tipoPresupuesto, acompanamiento, idProceso proceso, idEtapa etapa  FROM sia_auditorias  WHERE idAuditoria=:id ";
		*/
		if ($global=="SI"){

			$sql="SELECT a.idCuenta cuenta, a.idPrograma programa, a.idAuditoria auditoria,  COALESCE(convert(varchar(20),a.clave),convert(varchar(20),a.idAuditoria)) claveAuditoria, isnull(a.clave,'') clave, a.tipoAuditoria tipo, a.idArea area, a.idSector sector, a.idSubsector subSector, a.idUnidad unidad, a.idObjeto objeto, a.objetivo, a.alcance, a.justificacion, a.tipoPresupuesto, a.acompanamiento, a.idProceso proceso, a.idEtapa etapa, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fInicio,103), ''), '1900-01-01', '') feInicio, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fFin,103), ''), '1900-01-01', '') feFin, a.latitud, a.longitud, a.tipoObservacion tipoObse, a.observacion,a.idResponsable responsable, a.idSubresponsable subresponsable 
				FROM sia_auditorias a INNER JOIN sia_unidades u ON concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad) = CONCAT(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) LEFT JOIN  sia_objetos o ON a.idObjeto = o.idObjeto WHERE  a.idCuenta=:cuenta AND a.idAuditoria =:id AND a.idEtapa in (SELECT idEtapa FROM sia_rolesetapas re INNER JOIN sia_usuariosroles ur ON ur.idRol = re.idRol  WHERE ur.idUsuario=:usrActual) ";
				
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id, ':usrActual' => $usrActual));
		}else{

			$sql="SELECT a.idCuenta cuenta, a.idPrograma programa, a.idAuditoria auditoria,  COALESCE(convert(varchar(20),a.clave),convert(varchar(20),a.idAuditoria)) claveAuditoria, isnull(a.clave,'') clave, a.tipoAuditoria tipo, a.idArea area, a.idSector sector, a.idSubsector subSector, a.idUnidad unidad, a.idObjeto objeto, a.objetivo, a.alcance, a.justificacion, a.tipoPresupuesto, a.acompanamiento, a.idProceso proceso, a.idEtapa etapa, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fInicio,103), ''), '1900-01-01', '') feInicio, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fFin,103), ''), '1900-01-01', '') feFin, a.latitud, a.longitud, a.tipoObservacion tipoObse, a.observacion,a.idResponsable responsable, a.idSubresponsable subresponsable 
				FROM sia_auditorias a INNER JOIN sia_unidades u ON concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad) = CONCAT(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) LEFT JOIN  sia_objetos o ON a.idObjeto = o.idObjeto WHERE  a.idCuenta=:cuenta AND a.idArea =:area AND a.idAuditoria =:id AND a.idEtapa in (SELECT idEtapa FROM sia_rolesetapas re INNER JOIN sia_usuariosroles ur ON ur.idRol = re.idRol  WHERE ur.idUsuario=:usrActual) ";
				
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id, ':area' => $area, ':usrActual' => $usrActual));		
		}

		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});






	$app->get('/lstObjetos/:auditoria/:objeto', function($auditoria, $objeto)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$programa = $_SESSION["idProgramaActual"];
		//$area = $_SESSION["idArea"];
		
		$sql="SELECT ltrim(idObjeto) id, nombre texto FROM sia_objetos WHERE idCuenta=:cuenta and idAuditoria=:auditoria and idPrograma=:programa and idObjeto=:objeto";
	
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':auditoria' => $auditoria, ':programa' => $programa, ':objeto' => $objeto));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



$app->get('/porecentaje/:datos',  function($datos)    use($app, $db) {
	

	if($datos<>""){
			$dato = explode("|",$datos);
			$auditoria =$dato[0];
			$fase = strtoupper ($dato[1]);
			$efectivos =(float)$dato[2];
			$efectivos1 =(float)$dato[2];
			//echo $datos;		

		
   
			$sql="SELECT format(((convert(DECIMAL(20,2),:efectivos)/sum(a.suma))*100),'N','en-us') total FROM (
			SELECT  idAuditoria, idFase, (sum(convert(DECIMAL(20,2),diasEfectivos)) + convert(DECIMAL(20,2),:efectivos1)) suma FROM sia_auditoriasactividades  GROUP BY idAuditoria, idFase) a WHERE a.idAuditoria=:auditoria  AND a.idFase=:fase GROUP BY a.idFase, a.suma,a.idAuditoria";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':efectivos' => $efectivos,':efectivos1' => $efectivos1,':auditoria' => $auditoria, ':fase' => $fase));
			$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
			echo json_encode($result);
	}
		
});





$app->get('/reportenota/:id', function($id)    use($app, $db) {

	$sql="SELECT r.idReporte, r.nombre sReporte, r.idModulo sModulo, r.archivo

		from sia_reportes r  WHERE r.idReporte=:id";

	$dbQuery = $db->prepare($sql);
	$dbQuery->execute(array(':id' => $id));
	$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
	echo json_encode($result);		
});




$app->get('/lstAreasSubResponsablesByResponsable/:res', function($res)  use($app, $db) {
	$area = $_SESSION["idArea"];

	$sql="SELECT idSubresponsable id, nombre texto FROM sia_areassubresponsables WHERE idArea=:area AND idResponsable=:res;";

	$dbQuery = $db->prepare($sql);
	$dbQuery->execute(array(':area' => $area,':res' => $res));
	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);		

});



	//Listar responsables de cada area
	$app->get('/lstSubResponsables', function()  use($app, $db) {
		$area = $_SESSION["idArea"];
		$global = $_SESSION["usrGlobal"];
		
		if ($global=="SI"){			
			$sql="SELECT idSubresponsable id, nombre texto FROM sia_areassubresponsables ORDER BY nombre asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute();		
		}else{
			$sql="SELECT idSubresponsable id, nombre texto FROM sia_areassubresponsables WHERE idArea=:area ORDER BY nombre asc";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':area' => $area));	
		}
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	

        




// CatAuditores///////////////////////////////////////


$app->get('/catAuditores', function()    use($app, $db) {
 		$cuenta = $_SESSION["idCuentaActual"];
 		$area = $_SESSION["idArea"];
		
 		$usrActual = $_SESSION["idUsuario"];
 		$global = $_SESSION["usrGlobal"];
 		$programa = $_SESSION["idProgramaActual"];

 		if ($global=="SI"){
			
 			$sql="SELECT em.idEmpleado id, us.idUsuario,  CONCAT(em.nombre,' ',em.paterno,' ',em.materno) nombre, ar.nombre area, pl.nombre puesto, em.estatus
					FROM sia_empleados em
    				LEFT JOIN sia_usuarios us on em.idEmpleado = us.idEmpleado 
					LEFT JOIN sia_areas ar on em.idArea = ar.idArea
					LEFT JOIN sia_plazas pl on em.idPlaza = pl.idPlaza
    				WHERE em.idNivel in ('1.1', '2.1', '3.1', '4.1', '5.1'); ";
 
				
 				$dbQuery = $db->prepare($sql);
 				$dbQuery->execute();
 		}else{

 			$sql="SELECT em.idEmpleado  id, us.idUsuario,  CONCAT(em.nombre,' ',em.paterno,' ',em.materno) nombre, ar.nombre area, pl.nombre puesto, em.estatus
					FROM sia_empleados em
    				LEFT JOIN sia_usuarios us on em.idEmpleado = us.idEmpleado 
					LEFT JOIN sia_areas ar on em.idArea = ar.idArea
					LEFT JOIN sia_plazas pl on em.idPlaza = pl.idPlaza
    				WHERE em.idArea=:area and em.idNivel in ('1.1', '2.1', '3.1', '4.1', '5.1'); ";
				
 				$dbQuery = $db->prepare($sql);
 				$dbQuery->execute(array(':area' => $area));		
 		}

 		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	$app->render('catAuditores.php', $result);

})->name('listaAuditores');




// obtener auditores
$app->get('/AuditoresById/:id', function($id)    use($app, $db) {

	$sql="SELECT a.idAuditoria auditoria, em.idEmpleado empleado, em.nombre, em.paterno, em.materno, em.idPlaza puesto, em.estatus FROM sia_empleados em  LEFT JOIN sia_auditorias a on em.idArea = a.idArea WHERE em.idEmpleado=:id";
	$dbQuery = $db->prepare($sql);
	$dbQuery->execute(array(':id' => $id));
	$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
	echo json_encode($result);		
});



//Lista de Auditores
$app->get('/xxx', function()    use($app, $db) {

	$sql="SELECT idPlaza id, nombre texto FROM sia_Plazas order by nombre";

	$dbQuery = $db->prepare($sql);
	$dbQuery->execute();
	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	if(!$result){
		$app->halt(404, "NO SE ENCONTRARON DATOS ");
	}else{
		echo json_encode($result);
	}
});

$app->get('/estatus', function()    use($app, $db) {

	$sql="SELECT idEmpleado id, estatus texto FROM sia_empleados";

	$dbQuery = $db->prepare($sql);
	$dbQuery->execute();
	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	if(!$result){
		$app->halt(404, "NO SE ENCONTRARON DATOS ");
	}else{
		echo json_encode($result);
	}
});


//lista de auditoria
$app->get('/lstAsignadasByAuditores/:empleado', function( $empleado)    use($app, $db) {
	$area = $_SESSION["idArea"];
	$cuenta = $_SESSION["idCuentaActual"];

	  $sql="SELECT a.idAuditoria auditoria, Case When e.idEmpleado=aa.idAuditor  Then 'SI' Else 'NO' End As asignado, e.idEmpleado, COALESCE(clave, concat('Proy-Aud-',a.idAuditoria)) claveAuditoria, isnull(u.nombre,'') sujeto, isnull(o.nombre,'') objeto, a.tipoAuditoria tipo 
			from sia_empleados e 
			inner join  sia_auditorias a on e.idArea=a.idArea 
			inner join sia_unidades u on concat(a.idCuenta,a.idSector, a.idSubsector, a.idUnidad)=concat(u.idCuenta,u.idSector, u.idSubsector, u.idUnidad) and a.idCuenta = u.idCuenta 
			left join sia_objetos o on a.idObjeto=o.idObjeto 
			left join sia_auditoriasauditores aa on a.idAuditoria = aa.idAuditoria and e.idEmpleado=aa.idAuditor 
			Where a.idCuenta=:cuenta and e.idArea=:area and e.idNivel in ('1.1', '2.1', '3.1', '4.1', '5.1') and e.idEmpleado=:empleado order by a.idAuditoria asc;";

	  	


	$dbQuery = $db->prepare($sql);
	$dbQuery->execute(array(':area' => $area,':cuenta' => $cuenta,':empleado' => $empleado));
	$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	if(!$result){
		$app->halt(404, "NO SE ENCONTRARON DATOS ");
	}else{
		echo json_encode($result);
	}
});



//guardar auditorias-auditores
$app->get('/guardar/auditorias/auditor/:oper/:datos',  function($oper, $datos)  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		$cuenta = $_SESSION["idCuentaActual"];
		$programa = $_SESSION["idProgramaActual"];
		$ciclo=0;
		try{
			if($datos<>""){
			


			$registros = explode("*", $datos);
			foreach($registros as $registro) {     
		     	$campo = explode("|", $registro);
				$cuenta=$campo[0];
				$programa=$campo[1];
				$auditoria=$campo[2];
				$empleado=$campo[3];
				echo $datos;

				if($ciclo==0) {
					//Elimina
					$sql="DELETE FROM sia_auditoriasauditores WHERE idCuenta=:cuenta and idPrograma=:programa and idAuditoria=:auditoria and idAuditor=:empleado";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':empleado' => $empleado));
					$ciclo=1;
				}

				
				

				//Inserta		
				$sql="INSERT INTO sia_auditoriasauditores (idCuenta, idPrograma, idAuditoria, idAuditor, lider, usrAlta, fAlta, estatus) " .
						"values(:cuenta, :programa, :auditoria, :empleado,'',:usrAlta,getdate(),'ACTIVO')";
				$dbQuery = $db->prepare($sql);

				$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':empleado' => $empleado,':usrAlta' => $usrActual));
    
		    }			

		    echo "OK";

		}
		else{
			echo ("NO");
		}
	}catch (PDOException $e) {
			print "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}

	});


$app->get('/lstTiposAuditoria/:aud', function($aud)    use($app, $db) {

		$sql="SELECT idTipoAuditoria id, nombre texto FROM sia_tiposAuditoria ta INNER JOIN sia_auditorias au on ta.idTipoAuditoria=au.tipoAuditoria where au.idAuditoria=:aud";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':aud' => $aud));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});




	
	$app->get('/lstAudiUnida/:audi', function($audi)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		$area = $_SESSION["idArea"];
		
		$sql="SELECT au.idAuditoria audi, CONCAT(un.idsector,un.idSubsector,un.idUnidad) id , un.nombre texto FROM sia_auditoriasunidades au " .
			"INNER JOIN sia_unidades un on au.idSector = un.idSector and au.idSubsector = un.idSubsector and au.idUnidad = un.idUnidad and au.idCuenta = un.idCuenta " .
			"WHERE au.idCuenta=:cuenta and au.idAuditoria=:audi ORDER BY un.nombre;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':audi' => $audi));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/lstAudiUnidades/:audi/:valor', function($audi,$valor)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		
		$sql="SELECT au.idAuditoria id, CONCAT(un.idsector,un.idSubsector,un.idUnidad) ssuu, un.nombre nombre, REPLACE(ISNULL(CONVERT(VARCHAR(10),au.fConfronta,105), ''), '1900-01-01', '') FeCon,REPLACE(ISNULL(CONVERT(VARCHAR(10),aud.fIRA,105), ''), '1900-01-01', '') irac, REPLACE(ISNULL(CONVERT(VARCHAR(10),aud.fIFA,105), ''), '1900-01-01', '') ifa
			FROM sia_auditoriasunidades au
			INNER JOIN sia_unidades un on  au.idSector = un.idSector and au.idSubsector = un.idSubsector and au.idUnidad = un.idUnidad and au.idCuenta = un.idCuenta and CONCAT(un.idsector,un.idSubsector,un.idUnidad)=:valor
			INNER JOIN sia_auditorias aud on au.idCuenta = aud.idCuenta AND au.idPrograma = aud.idPrograma AND au.idAuditoria = aud.idAuditoria
			WHERE au.idCuenta=:cuenta and au.idAuditoria=:audi;";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':audi' => $audi, ':valor' => $valor));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/lstmodifUnidades/:audi/:valor', function($audi,$valor)    use($app, $db) {
		$cuenta = $_SESSION["idCuentaActual"];
		
		$sql="SELECT au.idAuditoria id, CONCAT(un.idsector,un.idSubsector,un.idUnidad) ssuu, un.nombre nombre, REPLACE(ISNULL(CONVERT(VARCHAR(10),au.fConfronta,105), ''), '1900-01-01', '') FeCon,  dateadd(day,-10, fConfronta) quitar,isnull(au.resumenConfronta,'') RCon FROM sia_auditoriasunidades au
			INNER JOIN sia_unidades un on  au.idSector = un.idSector and au.idSubsector = un.idSubsector and au.idUnidad = un.idUnidad and au.idCuenta = un.idCuenta and CONCAT(un.idsector,un.idSubsector,un.idUnidad)=:valor
			WHERE au.idCuenta=:cuenta and au.idAuditoria=:audi";
		
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta, ':audi' => $audi, ':valor' => $valor));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	







	//lista de auditoria-FASE
	$app->get('/lstApartados', function()    use($app, $db) {
		
		$sql="SELECT idApartado id, nombre texto FROM sia_apartados ORDER BY orden asc";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/guardar/confron/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$auditoria=$dato[0];
				$sector=$dato[1];
				$sub=$dato[2];
				$unidad=$dato[3];
				$FConfron = date_create($dato[4]);
				$FConfron = $FConfron->format('Y-m-d');

				//$ReConfron=$dato[5];				
					
				
				$sql="UPDATE sia_auditoriasunidades SET fConfronta=:FConfron, usrModificacion=:usrActual, fModificacion=getdate() WHERE idSector=:sector and idSubsector=:sub and idUnidad=:unidad and idAuditoria=:auditoria;";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':FConfron' => $FConfron,'usrActual' =>$usrActual,':sector' => $sector,':sub' => $sub,':unidad' => $unidad,':auditoria' => $auditoria));

				echo "OK";
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});	



	//lista de auditoria fases
	$app->get('/tlbaudiuni/:id', function($id)    use($app, $db) {
		$sql="SELECT au.idAuditoria audi, CONCAT(un.idsector,un.idSubsector,un.idUnidad) val, un.nombre nombre, REPLACE(ISNULL(CONVERT(VARCHAR(10),au.fConfronta,105), ''), '1900-01-01', '') fecha,REPLACE(ISNULL(CONVERT(VARCHAR(10),aud.fIRA,105), ''), '1900-01-01', '') irac, REPLACE(ISNULL(CONVERT(VARCHAR(10),aud.fIFA,105), ''), '1900-01-01', '') ifa " .
			"FROM sia_auditoriasunidades au " .
 			"INNER JOIN sia_unidades un on au.idCuenta = un.idCuenta and au.idSector=un.idSector and au.idSubsector = un.idSubsector and au.idUnidad = un.idUnidad " .
 			"INNER JOIN sia_auditorias aud on au.idCuenta = aud.idCuenta AND au.idPrograma = aud.idPrograma AND au.idAuditoria = aud.idAuditoria " .
 			"WHERE au.idAuditoria=:id ORDER BY un.nombre;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});





// auditorias ACCH  FIN


	//Listar rangos de fechas inhabiles
	$app->get('/lstInhabilesByRangos/:f1/:f2', function($f1, $f2)  use($app, $db) {	
		
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
	



$app->get('/lstInhabilesByRango1/:f1/:f2', function($f1, $f2)  use($app, $db) {	
		
		$f1 = date_create($f1);
		$f1 = $f1->format('Y-m-d');

		
		$f2 = date_create($f2);
		$f2 = $f2->format('Y-m-d');		

		try{
			//echo "F1= " . $f1 . " F2= " . $f2;
		$sql="Select 'A' caso , fInicio, fFin from sia_diasinhabiles Where :par1<=fInicio and fInicio<=:par2 and :par3<=fFin " . 
			"Union all " . 
			"Select 'B' caso , fInicio, fFin from sia_diasinhabiles Where fInicio<=:par4 and :par5<=fFin " . 
			"union all " . 
			"Select 'C' caso , fInicio, fFin from sia_diasinhabiles Where fInicio<=:par6 and :par7<=fFin and fFin<:par8 " . 
			"union all " . 
			"Select 'D' caso , fInicio, fFin from sia_diasinhabiles Where :par9<=fInicio and fFin<=:par10 ";
		
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(
			array(':par1'=> $f1, ':par2'=> $f2, ':par3'=> $f2, 
			':par4'=> $f1, ':par5'=> $f2, 
			':par6'=> $f1, ':par7'=> $f1, ':par8'=> $f2, 
			':par9'=> $f1,':par10'=> $f2 ));
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			//echo "F1:" . $f1 . " F2:" . $f2;
			echo json_encode($result);			
			
			}catch (OException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
			
			
		
	});		
	
////////////////////////////Fases Actividades


$app->get('/catFasesActividades', function()   use($app, $db) {
		$dbQuery = $db->prepare("SELECT fa.idCuenta, fs.idFase fase, fs.orden ,fa.idActividad id, fa.nombre actividad, fa.estatus estatus FROM sia_fasesactividades fa INNER JOIN sia_fases fs on fa.idFase=fs.idFase ORDER BY fs.orden;");		
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		
		$app->render('catFasesActividades.php', $result);
		
	})->name('listaFasesActividades');





//Obtener un usuario
	$app->get('/RecuperaFase/:id',  function($id)  use($app, $db) {
		$sql="SELECT idCuenta, idActividad id, idFase fase,  nombre actividad,estatus FROM sia_fasesactividades WHERE idActividad=:id";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':id' => $id));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});




	$app->post('/guardar/faseactividad', function()  use($app, $db) {
			$usrActual = $_SESSION["idUsuario"];
			$cuenta = $_SESSION["idCuentaActual"];
			$request=$app->request;
			$oper = $request->post('txtOperacion');
			$idactividad = $request->post('txtIDActividad');
			$fase = strtoupper($request->post('txtFase'));
			$actividad = strtoupper($request->post('txtNomActividad'));			
			$estatus = $request->post('txtEstatus');
		
		try{
	
			if($oper=='INS'){
				$sql="INSERT INTO sia_fasesactividades (idCuenta, idFase, nombre, usrAlta, fAlta, estatus) " .
						"VALUES (:cuenta, :fase, :actividad, :usrActual, getdate(), :estatus);";
	
					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':cuenta' => $cuenta,':fase' => $fase,':actividad' => $actividad,':usrActual' => $usrActual, ':estatus' => $estatus));
				 
			}else
			{
				$sql="UPDATE sia_fasesactividades SET " .
				"nombre=:actividad, usrModificacion=:usrActual, fModificacion=getdate(), estatus=:estatus " .
				"WHERE idActividad=:idactividad;";
					
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':actividad' => $actividad,':usrActual' => $usrActual, ':estatus' => $estatus,':idactividad' => $idactividad));
					
			}

		$app->redirect($app->urlFor('listaFasesActividades'));
			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
	});






////////////////// catTipoPapeles///////////////////////////////////////////////////////////////////////////////////////////////////////////


	$app->get('/tipoPapeles', function()   use($app, $db) {

		$sql="SELECT idTipoPapel id, nombre , programada, estatus, archivoFinal FROM sia_tipospapeles where estatus='ACTIVO';";

		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute();
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		
	 	$app->render('catTipoPapeles.php', $result);
		
	})->name('listacatTipoPapeles');





	$app->get('/ActiviPapeles/:papel',  function($papel)  use($app, $db) {

		$sql="SELECT idTipoPapel idt, nombre, programada, estatus, archivoOriginal ,archivoFinal,nomenclatura FROM sia_tipospapeles  WHERE idTipoPapel=:papel;";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':papel' => $papel));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});

	
	$app->get('/lstpapelesbyfases/:fase', function($fase)    use($app, $db) {
		
		$sql="SELECT  fa.idFase id, tp.idTipoPapel, fa.nombre texto FROM sia_tipospapeles tp INNER JOIN sia_papelesfases pf on tp.idTipoPapel=pf.idTipoPapel INNER JOIN sia_fases fa on pf.idFase=fa.idFase WHERE tp.idTipoPapel=:fase";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':fase' => $fase));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});



	$app->get('/prueba/:papel/:modi', function($papel,$modi)    use($app, $db) {
		$sql="EXECUTE sp_MatrizControles :papel,:modi;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':papel' => $papel,':modi' => $modi));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});

	
	
	$app->get('/validpapel/:papel', function($papel)    use($app, $db) {
		
		$sql="SELECT idTipoPapel pap FROM sia_tipospapeles WHERE idTipoPapel=:papel";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':papel' => $papel));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});


	$app->get('/validariesgo/:riesgo', function($riesgo)    use($app, $db) {
		
		$sql="SELECT idRiesgo riesgo, descripcion FROM sia_riesgos WHERE idRiesgo=:riesgo;";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':riesgo' => $riesgo));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});


	$app->get('/validnomen/:nomencla', function($nomencla)    use($app, $db) {
		
		$sql="SELECT nomenclatura nomen, archivoFinal final FROM sia_tipospapeles WHERE nomenclatura=:nomencla";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':nomencla' => $nomencla));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});




	$app->get('/nomenpapel/:papel', function($papel)    use($app, $db) {
		
		$sql="SELECT nomenclatura nomen from sia_tipospapeles where idTipoPapel=:papel";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':papel' => $papel));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});


	$app->post('/guardar/papeles', function()  use($app, $db) {
			$usrActual = $_SESSION["idUsuario"];
			$area = $_SESSION["idArea"];			
			$request=$app->request;
			$oper = $request->post('txtoperacion');
			$archivoOriginal = $request->post('txtArchivoOriginal');
			$archivoFinal = $request->post('txtArchivoFinal');
			$idpapel = strtoupper($request->post('txtTipoPapel'));
			$nomen = strtoupper($request->post('txtNomcla'));
			$nmpapel = strtoupper($request->post('txtNombrePapel'));
			$programa = $request->post('txtProgramadaPapel');			
			$estatus = $request->post('txtEstausPapel');
			$fasdiasi = $request->post('txtFases');
			$fareas = $request->post('txtAreas');


		try{
	
			if($oper=='INS'){
				$sql="INSERT INTO sia_tipospapeles (idTipoPapel, nombre, programada, usrAlta, fAlta, estatus,archivoOriginal, archivoFinal,nomenclatura) " .
						"VALUES (:idpapel, :nmpapel, :programa, :usrActual, getdate(), :estatus, :archivoOriginal, :archivoFinal,:nomen);";
	
					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':idpapel' => $idpapel,':nmpapel' => $nmpapel,':programa' => $programa,':usrActual' => $usrActual, ':estatus' => $estatus, ':archivoOriginal'=>$archivoOriginal, ':archivoFinal'=>$archivoFinal,':nomen'=>$nomen));

				 
			}else{
				$sql="UPDATE sia_tipospapeles SET " .
				"nombre=:nmpapel, programada=:programa,usrModificacion=:usrActual, fModificacion=getdate(), estatus=:estatus, archivoOriginal=:archivoOriginal, archivoFinal=:archivoFinal " .
				"WHERE idTipoPapel=:idpapel;";
					
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':nmpapel' => $nmpapel,':programa' => $programa,':usrActual' => $usrActual, ':estatus' => $estatus, ':archivoOriginal' => $archivoOriginal, ':archivoFinal' => $archivoFinal,':idpapel' => $idpapel));
					
			}

			echo("<br>idpapel: " . $idpapel . " usrActual: " . $usrActual);

			if($fareas != ""){
				$registrosAreas = explode('*', $fareas);

				if ( count($registrosAreas) > 0 ){

					// Se inicia eliminando todos los módulos que pueda tener el rol.
					
					$sql ="DELETE FROM sia_areastipospapeles WHERE idTipoPapel= :idpapel";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':idpapel'=>$idpapel));
					
					// Ahora se agregan a la tabla sia_areastipospapeles los módulos que se hayan seleccionado para el rol.

					foreach ($registrosAreas as $registroAreas) {
					    	$camposAreas = explode("|", $registroAreas);

							$idAreas 		= $camposAreas [0];
							$nombreAreas	= $camposAreas [1];


						$sql="INSERT INTO sia_areastipospapeles (idArea,idTipoPapel,usrAlta,fAlta,estatus) " .
							"VALUES (:idAreas, :idpapel,:usrActual,getdate(),'ACTIVO');";
		
						$dbQuery = $db->prepare($sql);

						$dbQuery->execute(array(':idAreas' => $idAreas,':idpapel' => $idpapel,':usrActual' => $usrActual));
					}
				}
 			}
 			echo("<br>idpapel: " . $idpapel . " usrActual: " . $usrActual);
 			//echo("<br>idpapel: "+ $idpapel);



			if ($fasdiasi != ""){

				$registrosModulos = explode('*', $fasdiasi);

				//echo('<br>count($registrosModulos)='+ count($registrosModulos) );

				if ( count($registrosModulos) > 0 ){

					// Se inicia eliminando todos los módulos que pueda tener el rol.
					
					$sql ="DELETE FROM sia_papelesfases WHERE idTipoPapel= :idpapel";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':idpapel'=>$idpapel ));
					
					// Ahora se agregan a la tabla sia_papelesfases los módulos que se hayan seleccionado para el rol.
					
					foreach ($registrosModulos as $registroModulos) {
				    	$camposModulo = explode("|", $registroModulos);

						$idModulo 		= $camposModulo [0];
						$nombreModulo	= $camposModulo [1];

						//echo("<br>id: " . $id . "  idModulo: " . $idModulo . " nombreModulo: " . $nombreModulo);

						$sql = "INSERT INTO sia_papelesfases (idFase, idTipoPapel, usrAlta, fAlta, estatus) VALUES (:idModulo, :idpapel, :usrActual, getdate(), 'ACTIVO');";

						$dbQuery = $db->prepare($sql);
						$dbQuery->execute(array(':idModulo'=>$idModulo, ':idpapel'=>$idpapel, ':usrActual'=> $usrActual));
					}
				}
			}

			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
		$app->redirect($app->urlFor('listacatTipoPapeles'));

	});



/////////////////////FIN DE catTipoPapeles /////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////INICIO catnotificaciones 21/07/2016////////////////////////////////////////////////////////////////////////////////////////////////

	$app->get('/catNotificaciones', function()   use($app, $db) {

		$sql="SELECT idNotificacion id, nombre, descripcion,tipo,consulta,estatus FROM sia_notificaciones;";

		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute();
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		
	 	$app->render('catNotificaciones.php', $result);
		
	})->name('listaCatNotificaciones');


	$app->get('/lstNotifiByID/:id',  function($id)  use($app, $db) {

		$sql="SELECT idNotificacion idt, nombre, descripcion,tipo,consulta,estatus FROM sia_notificaciones  WHERE idNotificacion=:id;";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':id' => $id));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});


	$app->get('/validnoti/:noti', function($noti)    use($app, $db) {
	
		$sql="SELECT idNotificacion noti FROM sia_notificaciones WHERE idNotificacion=:noti";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':noti' => $noti));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);

	});





	$app->post('/guardar/notificacion', function()  use($app, $db) {
			$usrActual = $_SESSION["idUsuario"];
			$request=$app->request;
			$oper = $request->post('txtoperacion');

			$id = $request->post('txtIDNotificacion');
			$nombre = strtoupper($request->post('txtNombreNotifi'));
			$descrip = $request->post('txtDescripcion');			
			$tipo = $request->post('txtTipoNoti');
			$consulta = $request->post('txtConsulta');
			$esta = $request->post('txtEstatus');
			$rolnoti = $request->post('txtNotificaciones');


		try{
	
			if($oper=='INS'){
				$sql="INSERT INTO sia_notificaciones (nombre, descripcion,tipo,consulta, usrAlta, fAlta, estatus) " .
						"VALUES (:nombre, :descrip,:tipo,:consulta ,:usrActual, getdate(),:esta);";
	
					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':nombre' => $nombre,':descrip' => $descrip,':tipo' => $tipo,':consulta' => $consulta,':usrActual' => $usrActual, ':esta' => $esta));
				 
			}else{
				$sql="UPDATE sia_notificaciones SET " .
				"nombre=:nombre, descripcion=:descrip,tipo=:tipo,consulta=:consulta,usrModificacion=:usrActual, fModificacion=getdate(), estatus=:esta " .
				"WHERE idNotificacion=:id;";
					
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':nombre' => $nombre,':descrip' => $descrip,':tipo' => $tipo,':consulta' => $consulta,':usrActual' => $usrActual, ':esta' => $esta,':id' => $id));
					
			}


 				// Si fue insertar se debe recuperar el valor del campo idUsuario recien ingresado
			if ($oper=='INS'){
				$sql="SELECT MAX(idNotificacion) id FROM sia_notificaciones WHERE nombre=:nombre AND descripcion=:descrip AND tipo=:tipo AND consulta=:consulta AND usrAlta=:usrActual AND estatus=:estatus;";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':nombre' => $nombre,':descrip' => $descrip,':tipo' => $tipo,':consulta' => $consulta,':usrActual' => $usrActual,':estatus' => $estatus));				
				$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
				$id = $result ['id']; 
			}

 			echo("<br>id: "+ $id);

			if ($rolnoti != ""){

				$registrosModulos = explode('*', $rolnoti);

				//echo('<br>count($registrosModulos)='+ count($registrosModulos) );

				if ( count($registrosModulos) > 0 ){

					// Se inicia eliminando todos los módulos que pueda tener el rol.
					
					$sql ="DELETE FROM sia_notificacionesroles WHERE idNotificacion= :id";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':id'=>$id));
					
					// Ahora se agregan a la tabla sia_rolesmodulos los módulos que se hayan seleccionado para el rol.
					
					foreach ($registrosModulos as $registroModulos) {
				    	$camposModulo = explode("|", $registroModulos);

						$idModulo 		= $camposModulo [0];
						$nombreModulo	= $camposModulo [1];

						//echo("<br>id: " . $id . "  idModulo: " . $idModulo . " nombreModulo: " . $nombreModulo);

						$sql = "INSERT INTO sia_notificacionesroles (idNotificacion, idRol, usrAlta, fAlta, estatus) VALUES (:id, :idModulo, :usrActual, getdate(), 'ACTIVO');";

						$dbQuery = $db->prepare($sql);
						$dbQuery->execute(array(':id'=>$id, ':idModulo'=>$idModulo, ':usrActual'=> $usrActual));
					}
				}
			}

			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
		$app->redirect($app->urlFor('listaCatNotificaciones'));
	});





	//Obtener los roles asignados a un usuario
	$app->get('/lstRolesBynotifica/:id',  function($id)  use($app, $db) {
		//$id = (int)$id;
		$sql= "SELECT isnull(nr.idRol,'') id, isnull(r.nombre, '') texto FROM sia_notificacionesroles nr INNER JOIN sia_roles r ON nr.idRol = r.idRol WHERE nr.idNotificacion=:id ";
		
		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':id' => $id));
		$result ['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);

		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});

/////////////////////FIN catnotificaciones 21/07/2016//////////////////////////////////////////////////////////////////////////////////////////////////
	
/////////////////////INICIO notificaciones.php 01/08/2016//////////////////////////////////////////////////////////////////////////////////////////////

	$app->get('/notificaciones', function()   use($app, $db) {
			$usrActual = $_SESSION["idUsuario"];


		$sql="SELECT Case WHEN estatus='INACTIVO' THEN 'true' ELSE 'false' END  AS asignado, idMensaje noti, idMensaje id,CONVERT(VARCHAR(10),fAlta,103) fecha, mensaje, CONVERT(VARCHAR(10), fLectura,103) lectura, idPrioridad import, idImpacto impacto, situacion FROM sia_notificacionesmensajes WHERE estatus='ACTIVO' and idUsuario=:usrActual ORDER BY situacion DESC;";


		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute(array(':usrActual' => $usrActual));
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		
	 	$app->render('notificaciones.php', $result);
		
	})->name('listaNotificaciones');


	




	$app->get('/lstMensaByID/:id',  function($id)  use($app, $db) {

	$sql="SELECT idNotificacion noti, idMensaje mensa, idUsuario usua, CONVERT(VARCHAR(10),fAlta,103) fecha, mensaje, Case WHEN fLectura is null THEN '' ELSE fLectura END AS lectura, idPrioridad prioridad, idImpacto impacto,estatus,estado,idpapel refe,usrAlta usuario,idAuditoria audi  FROM sia_notificacionesmensajes WHERE idMensaje=:id";

		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':id' => $id));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});



////guargar post de notificación

	$app->post('/guardar/notifica', function()  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		$request=$app->request;
		//$oper = $request->post('txtoperacion');

		$idnoti = $request->post('txtID');
		$idMensaje = $request->post('txtIdMensaje');
		$fabierto = $request->post('txtfeAbMensaje');			
		
		$esta = $request->post('txtEstatusMensaje');

		try{
	
			// if($oper=='UPD'){
				if($esta=="ACTIVO"){
				$sql="UPDATE sia_notificacionesmensajes SET fLectura =:fabierto,usrModificacion =:usrActual ,fModificacion = getdate(), situacion='LEIDO' WHERE idNotificacion =:idnoti AND idMensaje =:idMensaje;";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':fabierto' => $fabierto,':usrActual' => $usrActual,':idnoti' => $idnoti, ':idMensaje' => $idMensaje));

				}else{

				$sql="UPDATE sia_notificacionesmensajes SET fLectura =:fabierto ,fEliminado = getdate() ,usrModificacion =:usrActual ,fModificacion = getdate() ,estatus ='INACTIVO' WHERE idNotificacion =:idnoti AND idMensaje =:idMensaje;";
	
					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':fabierto' => $fabierto,':usrActual' => $usrActual,':idnoti' => $idnoti, ':idMensaje' => $idMensaje));
				}
			//}

			
		}catch (PDOException $e) {
			echo  "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}
		$app->redirect($app->urlFor('listaNotificaciones'));
	});



$app->get('/guardar/notificaciones/:datos',  function($datos)  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		//$cuenta = $_SESSION["idCuentaActual"];
		$programa = $_SESSION["idProgramaActual"];
		$ciclo=0;
		try{
			if($datos<>""){
			
			$registros = explode("*", $datos);
			foreach($registros as $registro) {     
		     	$campo = explode("|", $registro);
				$idMensaje=$campo[0];

				echo $datos;

				//Inserta		
				$sql="UPDATE sia_notificacionesmensajes SET fEliminado = getdate() ,usrModificacion =:usrActual ,fModificacion = getdate() ,estatus ='INACTIVO' WHERE idMensaje =:idMensaje;";
	
					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':usrActual' => $usrActual,':idMensaje' => $idMensaje));
    
		    }			

		    echo "OK";

		}
		else{
			echo ("NO");
		}
	}catch (PDOException $e) {
			print "<br>¡Error en el TRY!: " . $e->getMessage();
			die();
		}

	});

	$app->get('/notifica',  function()  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		
		$sql=" SELECT Case WHEN  count(*)=0 THEN '0' ELSE count(*)  END  AS valor  FROM sia_notificacionesmensajes WHERE  situacion='NUEVO' AND estatus='ACTIVO' AND idUsuario=:usrActual;";


		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':usrActual' => $usrActual));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});


	$app->get('/notific',  function()  use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];
		$sql=" SELECT Case WHEN  count(*)=0 THEN '0' ELSE count(*)  END  AS valor  FROM sia_notificacionesmensajes WHERE  estatus='ACTIVO' AND idUsuario=:usrActual;";
		$dbQuery = $db->prepare($sql);		
		$dbQuery->execute(array(':usrActual' => $usrActual));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "RECURSO NO ENCONTRADA.");			
		}else{		
			echo json_encode($result);
		}		
	});



	$app->get('/vistoaprobado/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		$cuenta = $_SESSION["idCuentaActual"];
		$programa = $_SESSION["idProgramaActual"];
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$usuario=$dato[0];
				$mensaje=$dato[1];
				$prioridad=$dato[2];
				$papel=$dato[3];
				$auditoria=$dato[4];
				$idmensaje=$dato[5];
				$estado=$dato[6];
				
				$sql="UPDATE sia_papeles SET usrModificacion=:usrActual, fModificacion=getdate(),  estatus = 'INACTIVO' WHERE idCuenta = :cuenta AND idPrograma = :programa AND idAuditoria = :auditoria AND idPapel = :papel;";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':usrActual' => $usrActual,':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':papel' => $papel));

				$dbQuery = $db->prepare("SELECT estatus FROM sia_papeles where idAuditoria = :auditoria AND idPrograma = :programa AND idCuenta = :cuenta AND idPapel = :papel");
				$dbQuery->execute(array(':auditoria' => $auditoria,':programa' => $programa,':cuenta' => $cuenta,':papel' => $papel));				
				$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
				$estatus = $result ['estatus'];

				if($estatus=='INACTIVO'){
					$sql="INSERT INTO sia_notificacionesmensajes (idNotificacion,idUsuario,mensaje,idPrioridad,idImpacto,usrAlta,fAlta,estatus,situacion) " .
					"VALUES (11,:usuario,:mensaje,:prioridad,'MEDIO',:usrActual,getdate(),'ACTIVO','NUEVO');";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':usuario' => $usuario,':mensaje' => $mensaje,':prioridad' => $prioridad,':usrActual' => $usrActual));
				}

				if($estatus=='INACTIVO'){
					$sql="UPDATE sia_notificacionesmensajes SET usrModificacion=:usrActual, fModificacion=getdate(),estado =:estado WHERE idMensaje = :idmensaje AND idAuditoria = :auditoria AND idPapel = :papel;";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':usrActual' => $usrActual,':estado' => $estado,':idmensaje' => $idmensaje,':auditoria' => $auditoria,':papel' => $papel));
					echo "OK";
				}

			echo "OK";	
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});


	$app->get('/rechazovobo/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		$cuenta = $_SESSION["idCuentaActual"];
		$programa = $_SESSION["idProgramaActual"];
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$usuario=$dato[0];
				$mensaje=$dato[1];
				$prioridad=$dato[2];
				$papel=$dato[3];
				$auditoria=$dato[4];
				$idmensaje=$dato[5];
				$estado=$dato[6];
				
				/*$sql="UPDATE sia_papeles SET usrModificacion=:usrActual, fModificacion=getdate(),  estatus = 'INACTIVO' WHERE idCuenta = :cuenta AND idPrograma = :programa AND idAuditoria = :auditoria AND idPapel = :papel;";
				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':usrActual' => $usrActual,':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':papel' => $papel));*/

				$dbQuery = $db->prepare("SELECT estatus FROM sia_papeles where idAuditoria = :auditoria AND idPrograma = :programa AND idCuenta = :cuenta AND idPapel = :papel");
				$dbQuery->execute(array(':auditoria' => $auditoria,':programa' => $programa,':cuenta' => $cuenta,':papel' => $papel));				
				$result = $dbQuery->fetch(PDO::FETCH_ASSOC); 
				$estatus = $result ['estatus'];

				if($estatus=='ACTIVO'){
					$sql="INSERT INTO sia_notificacionesmensajes (idNotificacion,idUsuario,mensaje,idPrioridad,idImpacto,usrAlta,fAlta,estatus,situacion) " .
					"VALUES (11,:usuario,:mensaje,:prioridad,'MEDIO',:usrActual,getdate(),'ACTIVO','NUEVO');";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':usuario' => $usuario,':mensaje' => $mensaje,':prioridad' => $prioridad,':usrActual' => $usrActual));
				}

				if($estatus=='ACTIVO'){
					$sql="UPDATE sia_notificacionesmensajes SET usrModificacion=:usrActual, fModificacion=getdate(),estado =:estado WHERE idMensaje = :idmensaje AND idAuditoria = :auditoria AND idPapel = :papel;";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':usrActual' => $usrActual,':estado' => $estado,':idmensaje' => $idmensaje,':auditoria' => $auditoria,':papel' => $papel));
					echo "OK";
				}

			echo "OK";	
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});







////////////////////////////////////////cedulas 17/08/16 seguimeinto INICIO////////////////////////////////////////////////////
	//Lista de TIPO-PAPEL
	$app->get('/lstTipoPapel', function()    use($app, $db) {
		$sql="SELECT idTipoPapel id,nombre texto FROM sia_tipospapeles";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	//Lista de TIPO-PAPEL
	$app->get('/lstMomentos', function()    use($app, $db) {
		$sql="SELECT idMomento id, nombre texto FROM sia_momentos;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/lstpapelesbymomento/:momen/:pape', function($momen,$pape)    use($app, $db) {
		
		$sql="SELECT Case when pc.idRiesgo=ri.idRiesgo Then 'SI' Else 'NO' END AS asignado,mr.idRiesgo ,ri.idRiesgo id, ri.descripcion riesgo, pc.idPapel papel FROM sia_riesgos ri " .
			"INNER JOIN sia_momentosriesgos mr on ri.idRiesgo=mr.idRiesgo " .
			"INNER JOIN sia_momentos mo on mr.idMomento=mo.idMomento " .
			"LEFT JOIN sia_papelescontroles pc on ri.idRiesgo=pc.idRiesgo and mo.idMomento = pc.idMomento and pc.idPapel =:pape " .
			"where mo.idMomento=:momen;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':momen' => $momen,':pape' => $pape));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});


		//Lista de TIPO-PAPEL
	$app->get('/lstProced', function()    use($app, $db) {
		$sql="SELECT idProcedimiento id, nombre texto FROM sia_procedimientos;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	//Lista de TIPO-PAPEL
	$app->get('/lstElem/:pro', function($pro)    use($app, $db) {
		$sql="SELECT pe.idProcedimiento, pe.idElemento id, pe.nombre texto FROM sia_procedimientoselementos pe " .
			"INNER JOIN sia_procedimientos pr on pe.idProcedimiento=pr.idProcedimiento  " .
			"WHERE pr.idProcedimiento=:pro;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':pro' => $pro));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


//Guarda un papel
	$app->post('/guardar/pa', function()  use($app, $db) {
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
			$est = $request->post('txtEstatusRegistro');
			//$comentario = $request->post('txtAproba');



			if($oper=='INS'){
				$sql="INSERT INTO sia_papeles (idCuenta, idPrograma, idAuditoria, idFase, tipoPapel, fPapel, tipoResultado, resultado, archivoOriginal, archivoFinal, usrAlta, fAlta, estatus) " .
				"VALUES(:cuenta, :programa, :auditoria, :fase, :tipoPapel, :fPapel, :tipoResultado, :resultado, :original, :final, :usrActual, getdate(), 'ACTIVO');";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta, ':programa' => $programa, ':auditoria' => $auditoria, ':fase' => $fase, 
				':tipoPapel' => $tipoPapel, ':fPapel' => $fPapel, ':tipoResultado' => $tipoResultado,':resultado' => $resultado, ':original' => $original, ':final' => $final, ':usrActual' => $usrActual ));				
			}else{
				if($tipoResultado=='NINGUNA' && $est=='ACTIVO'){				
					$sql="UPDATE sia_papeles SET " .
						"idFase=:fase, tipoPapel=:tipoPapel, fPapel=:fPapel, tipoResultado=:tipoResultado, resultado='', usrModificacion=:usrActual, fModificacion=getdate(), archivoOriginal=:original, archivoFinal=:final " .
						"WHERE idPapel=:id";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':fase' => $fase, ':tipoPapel' => $tipoPapel, ':fPapel' => $fPapel,':tipoResultado' => $tipoResultado,':usrActual' => $usrActual,  ':original' => $original, ':final' => $final,':id' => $id  ));
				
				//echo "GLOBAL-AREA <hr>  <br>fase: $fase  tipoPapel= $tipoPapel   fPapel= $fPapel tipoResultado: $tipoResultado  resultado= $resultado   usrActual= $usrActual final= $final Original= $original id= $id";					
				}else{
					if($est=='APROBACION'){



						$sql="UPDATE sia_papeles SET " .
						"usrModificacion=:usrActual, fModificacion=getdate(), estatus=:est " .
						"WHERE idPapel=:id";

						$dbQuery = $db->prepare($sql);
						$dbQuery->execute(array(':usrActual' => $usrActual,':est' => $est ,':id' => $id  ));

						
						echo "INACTIVO <hr>  <br>usrActual= $usrActual est= $est id= $id";

					}else{
						$sql="UPDATE sia_papeles SET " .
						"idFase=:fase, tipoPapel=:tipoPapel, fPapel=:fPapel, tipoResultado=:tipoResultado, resultado=:resultado, usrModificacion=:usrActual, fModificacion=getdate(), archivoOriginal=:original, archivoFinal=:final " .
						"WHERE idPapel=:id";

						$dbQuery = $db->prepare($sql);
						$dbQuery->execute(array(':fase' => $fase, ':tipoPapel' => $tipoPapel, ':fPapel' => $fPapel,':tipoResultado' => $tipoResultado,':resultado' => $resultado, ':usrActual' => $usrActual, ':original' => $original, ':final' => $final ,':id' => $id  ));
						
						echo "GLOBAL-AREA <hr>  <br>fase: $fase  tipoPapel= $tipoPapel   fPapel= $fPapel tipoResultado: $tipoResultado  resultado= $resultado   usrActual= $usrActual final= $final Original= $original id= $id";
					}
				}
			}
			//echo "SQL=<br>$sql<br><hr> id: " . $id . " fase: " . $fase . " tipoPapel: " . $tipoPapel . " fPapel: " . $fPapel . " tipoResultado: " . $tipoResultado . " esultado: " . $resultado . " usrActual: " . $usrActual . "  final" . $final ."   Original" . $original;
			$app->redirect($app->urlFor('listaPapeles'));
		}catch (PDOException $e) {
			echo  "<br>Error de BD: " . $e->getMessage();
			die();
		}		
	});

		//Obtener un avance
	$app->get('/DescarTipoPapel/:id', function($id)    use($app, $db) {
		$sql="SELECT idTipoPapel id,archivoFinal final FROM sia_tipospapeles WHERE idTipoPapel=:id;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});

	$app->get('/validararchivo/:arch', function($arch)    use($app, $db) {
		
		$sql="SELECT archivoFinal final FROM sia_tipospapeles WHERE archivoFinal=:arch";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':arch' => $arch));
		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});	




	$app->get('/guardar/control/matriz/:oper/:cadena',  function($oper, $cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$cuenta=$dato[0];
				$programa=$dato[1];
				$auditoria=$dato[2];
				$papel=$dato[3];
				$momento=$dato[4];
				$riesgo=$dato[5];
				$procedimiento=$dato[6];
				$Elemento = $dato[7];
				$control = strtoupper($dato[8]);
		

				if ($oper=='INS')
				{
					
					$sql="INSERT INTO sia_papelescontroles (idCuenta,idPrograma,idAuditoria,idPapel,idMomento,idRiesgo,idProcedimiento,idElemento,usrAlta,fAlta,estatus,descripcion) VALUES(:cuenta,:programa,:auditoria,:papel,:momento,:riesgo,:procedimiento,:Elemento,:usrActual,getdate(),'ACTIVO',:control);";


					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':papel' => $papel,':momento' => $momento,':riesgo' => $riesgo,':procedimiento' => $procedimiento,':Elemento' => $Elemento,':usrActual' => $usrActual,':control' => $control));
					//echo "/guardar/control/matriz/ <br> <br> insert <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria papel=$papel momento=$momento riesgo=$riesgo procedimiento=$procedimiento  Elemento=$Elemento usrActual=$usrActual control=$control";
					//echo "OK";
				}
				else {

				}
			}
			else{
				echo ("NO");
			}
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});

	/*
		//lista de auditoria fases
		$app->get('/lstpapelescontrol/:id/:momento', function($id,$momento)    use($app, $db) {
			$sql="SELECT pr.nombre procedi ,mo.nombre momen, ri.idRiesgo riesgo ,ri.descripcion des,pc.descripcion descrip FROM sia_papelescontroles pc " .
	    		"INNER JOIN sia_riesgos ri on pc.idRiesgo = ri.idRiesgo " .
	    		"INNER JOIN sia_procedimientos pr on pc.idProcedimiento = pr.idProcedimiento " .
			    "INNER JOIN sia_momentos mo on pc.idMomento = mo.idMomento " .
			    "WHERE pc.idPapel =:id and mo.idMomento=:momento;";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':id' => $id,':momento' => $momento));
			$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});
	*/

		//lista de auditoria fases
	$app->get('/lstpapelescontro/:id/:momento', function($id,$momento)    use($app, $db) {
		$sql="SELECT idControl ,ri.descripcion Riesgo,pc.descripcion controI FROM sia_papelescontroles pc " .
			"INNER JOIN sia_riesgos ri on pc.idRiesgo = ri.idRiesgo " .
			"INNER JOIN sia_procedimientos pr on pc.idProcedimiento = pr.idProcedimiento " .
			"INNER JOIN sia_momentos mo on pc.idMomento = mo.idMomento " .
			"WHERE pc.idPapel =:id and mo.idMomento=:momento ORDER BY pc.idControl ASC";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id,':momento' => $momento));
		$result ['arreglo']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});




		//lista de auditoria fases
	$app->get('/lstpapelescontrol/:id/:momento', function($id,$momento)    use($app, $db) {
		$sql="SELECT pr.nombre procedi ,mo.nombre momen, ri.idRiesgo riesgo ,ri.descripcion des,pc.descripcion descrip FROM sia_papelescontroles pc " .
    		"INNER JOIN sia_riesgos ri on pc.idRiesgo = ri.idRiesgo " .
    		"INNER JOIN sia_procedimientos pr on pc.idProcedimiento = pr.idProcedimiento " .
		    "INNER JOIN sia_momentos mo on pc.idMomento = mo.idMomento " .
		    "WHERE pc.idPapel =:id and mo.idMomento=:momento;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id,':momento' => $momento));
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/obtenpapel/:id', function($id)    use($app, $db) {
		$usrActual = $_SESSION["idUsuario"];

		$sql="SELECT TOP 1 idPapel pap FROM sia_papeles WHERE idAuditoria=:id and usrAlta=:usrActual ORDER BY idPapel desc";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id, 'usrActual' => $usrActual));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	//Modificar Cronograma
 	$app->get('/modifriesgo/:riesg',  function($riesg)  use($app, $db) {

 		$sql="  SELECT idRiesgo riesgo, descripcion, estatus FROM sia_riesgos WHERE idRiesgo=:riesg;";
 		$dbQuery = $db->prepare($sql);		
 		$dbQuery->execute(array(':riesg' => $riesg));
 		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
 		if(!$result){
 			$app->halt(404, "RECURSO NO ENCONTRADA.");			
 		}else{		
 			echo json_encode($result);
 		}		
 	});

$app->get('/vistobueno/:cadena',  function($cadena)  use($app, $db) {
		$datos= $cadena;
		$usrActual = $_SESSION["idUsuario"];
		$cuenta = $_SESSION["idCuentaActual"];
		
		try{
			if($datos<>""){
				$dato = explode("|", $datos);
				$usuario=$dato[0];
				$mensaje=$dato[1];
				$prioridad=$dato[2];
				$papel=$dato[3];
				$auditoria=$dato[4];
				
				$sql="INSERT INTO sia_notificacionesmensajes (idNotificacion,idUsuario,mensaje,idPrioridad,idImpacto,usrAlta,fAlta,estatus,situacion,estado,idpapel,idCuenta,idAuditoria) " .
					"VALUES (11,:usuario,:mensaje,:prioridad,'MEDIO',:usrActual,getdate(),'ACTIVO','NUEVO','PENDIENTE',:papel,:cuenta,:auditoria);";

				$dbQuery = $db->prepare($sql);

				$dbQuery->execute(array(':usuario' => $usuario,':mensaje' => $mensaje,':prioridad' => $prioridad,':usrActual' => $usrActual,':papel' => $papel,':cuenta' => $cuenta,':auditoria' => $auditoria));

				echo "OK";	
				
			}
			else{
				echo ("NO");
			}

		
		}catch (Exception $e) {
				print "<br>¡Error en el TRY!: " . $e->getMessage();
				die();
			}
	});


	//Concultar papel
 	$app->get('/verificarpapel/:id',  function($id)  use($app, $db) {

 		$sql="  SELECT estado FROM sia_notificacionesmensajes WHERE idPapel=:id;";
 		$dbQuery = $db->prepare($sql);		
 		$dbQuery->execute(array(':id' => $id));
 		$result= $dbQuery->fetch(PDO::FETCH_ASSOC);
 		echo json_encode($result);
 	});




	////////////////////////////////////////////////////////


	$app->get('/btndescar/:id', function($id)    use($app, $db) {
		$sql="SELECT idTipoPapel, programada pro FROM sia_tipospapeles WHERE idTipoPapel=:id;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':id' => $id));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);		
	});

	//lista de PAPELES-FASE
	$app->get('/lstPapelesFases', function()    use($app, $db) {
			$cuenta = $_SESSION["idCuentaActual"];
		$sql="SELECT idArea id, nombre texto FROM sia_areas ORDER BY id";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();
		$result ['datos']= $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

		$app->get('/lstAreasbyPapeles/:papel', function($papel)    use($app, $db) {
		
		$sql="SELECT atp.idArea id, ar.nombre texto FROM sia_areastipospapeles atp
  			INNER JOIN sia_areas ar on atp.idArea = ar.idArea
  			INNER JOIN sia_tipospapeles tp on atp.idTipoPapel = tp.idTipoPapel
  			WHERE tp.idTipoPapel=:papel;";
		

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':papel' => $papel));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	
	});

   /////////////////////////////   CONTINUIDAD DE ACOPIO //////

	//Listar Tipo de documento
	$app->get('/lsttipoDocumento', function()  use($app, $db) {		
		$sql="SELECT idTipoDocto id, nombre texto FROM sia_tiposdocumentos where tipo='ACOPIO';";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute();	
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});	

	//////////////////////////// INICIO CUENTA PUBLICA VISTA //////////////


	/*$app->get('/cuentapublica', function()   use($app, $db) {

		$sql="SELECT idCuenta cuenta, nombre, anio, fInicio inicio, fFin fin ,observaciones obser, estatus FROM sia_cuentas ORDER BY idCuenta desc;";
		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute();
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	 	$app->render('cuentapublica.php', $result);
	})->name('listacuentapublica');*/




	$app->get('/cuentaegresos', function()   use($app, $db) {

		$sql="SELECT idCuenta cuenta, nombre, anio, fInicio inicio, fFin fin ,observaciones obser, estatus FROM sia_cuentas ORDER BY idCuenta desc;";
		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute();
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	 	$app->render('cuentapublicaegresos.php', $result);
	})->name('listacuentapublicaegresos');



	$app->get('/cuentaingresos', function()   use($app, $db) {
		$sql="SELECT cue.idCuenta cuenta, cue.nombre, cue.anio, cue.fInicio inicio, cue.fFin fin ,cue.observaciones obser, cue.estatus FROM sia_cuentas cue
			 INNER JOIN sia_ingresosOrdParaNoFin ingre on cue.idCuenta=ingre.idCuenta
			 GROUP BY cue.idCuenta, cue.nombre, cue.anio, cue.fInicio, cue.observaciones, cue.fFin, cue.estatus 
			 ORDER BY cue.idCuenta desc;";
		$dbQuery = $db->prepare($sql);		
	 	$dbQuery->execute();
	 	$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
	 	$app->render('cuentapublicaingre.php', $result);
	})->name('listacuentapublicaingresos');



	//Lista de TIPO-PAPEL
	$app->get('/funcion/:sector/:subsector/:unidad/:cuenta', function($sector,$subsector,$unidad,$cuenta)    use($app, $db) {
		$sql="SELECT SELECT cd.funcion id,cd.finalidad,sb.nombre texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta
			GROUP BY fin.nombre,fin.idFinalidad;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/lstSujetoingre/:cuenta', function($cuenta)  use($app, $db) {

		$sql="SELECT CONCAT(ingre.idSector, ingre.idSubsector, ingre.idUnidad) id, un.nombre texto
			FROM sia_ingresosOrdParaNoFin ingre
			INNER JOIN sia_unidades un on ingre.idCuenta=un.idCuenta AND CONCAT(ingre.idSector,ingre.idSubsector,ingre.idUnidad)=CONCAT(un.idSector,un.idSubsector,un.idUnidad)
			WHERE ingre.idCuenta=:cuenta
			GROUP BY ingre.idCuenta,un.nombre,ingre.idSector, ingre.idSubsector, ingre.idUnidad;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/lstSujeto/:cuenta', function($cuenta)  use($app, $db) {

		$sql="SELECT CONCAT(idSector,idSubsector,idUnidad) id, nombre texto from sia_unidades where idCuenta=:cuenta ORDER BY nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	//tabla de Cuenta Publica.
	$app->get('/tblCuentaByUnidad/:sector/:subsector/:unidad/:cuenta', function($sector,$subsector,$unidad,$cuenta)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo, CONCAT(pa.idPartida,' - ',pa.nombre) partida, CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre, ac.nombre,ca.idCapitulo, ca.nombre, pa.idPartida, pa.nombre, fin.idFinalidad, fin.nombre,cd.original, ac.idActividad,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 
		
		//tabla de Cuenta Publica.
	$app->get('/tblCuentaByEgresos/:cuenta', function($cuenta)    use($app, $db) {
		$sql="SELECT idCuenta,clave,nivel,nombre,tipo,origen,original,recaudado FROM sia_cuentasingresos Where idCuenta=:cuenta
			GROUP BY idCuenta, clave, clavePadre, nombre, nivel, tipo, origen, original, recaudado;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 

	$app->get('/tblCuentaByIngresosDE/:sector/:subsector/:unidad/:cuenta', function($sector,$subsector,$unidad,$cuenta)    use($app, $db) {

		$sql="SELECT ingre.idCuenta cuenta,CONCAT(ingre.idSector, ingre.idSubsector, ingre.idUnidad,' - ' ,un.nombre) nombre,FORMAT(sum(CONVERT(DECIMAL(20,2),ingre.estimado)), 'c', 'en-US')estimado,FORMAT(sum(CONVERT(DECIMAL(20,2),ingre.registrado)), 'c', 'en-US') registrado,FORMAT(sum(CONVERT(DECIMAL(20,2),ingre.variacionImporte)), 'c', 'en-US') importe FROM sia_ingresosOrdParaNoFin ingre
			INNER JOIN sia_unidades un on ingre.idCuenta=un.idCuenta AND CONCAT(ingre.idSector,ingre.idSubsector,ingre.idUnidad)=CONCAT(un.idSector,un.idSubsector,un.idUnidad)
			WHERE ingre.idSector =:sector AND ingre.idSubsector =:subsector AND ingre.idUnidad =:unidad AND ingre.idCuenta=:cuenta
			GROUP BY ingre.idCuenta, ingre.estimado, ingre.registrado, ingre.variacionImporte, ingre.variacionPorcentaje, un.nombre,ingre.idSector, ingre.idSubsector, ingre.idUnidad;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/valsujeto/:sujeto/:cuenta',  function($sujeto,$cuenta)    use($app, $db) {
		
		$sql="SELECT CASE WHEN estatus='ACTIVO' THEN 'true' ELSE 'false' END  AS verdadero
			FROM sia_ingreso.sOrdParaNoFin
			WHERE CONCAT(idSector,idSubsector,idUnidad)=:sujeto AND idCuenta=:cuenta;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':sujeto' => $sujeto,':cuenta' => $cuenta));
		$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
		echo json_encode($result);
	});




		//tabla de Cuenta Publica.
	$app->get('/tblCuentaByUnidad/:sector/:subsector/:unidad/:cuenta', function($sector,$subsector,$unidad,$cuenta)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo, CONCAT(pa.idPartida,' - ',pa.nombre) partida, CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre, ac.nombre,ca.idCapitulo, ca.nombre, pa.idPartida, pa.nombre, fin.idFinalidad, fin.nombre,cd.original, ac.idActividad,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 


	///Finalidad
	$app->get('/finalidad', function()    use($app, $db) {
		$cuenta = $_SESSION ["idCuentaActual"];

		$sql="SELECT  ltrim(idFinalidad) id, nombre FROM sia_finalidades WHERE idCuenta=:cuenta;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

///funciones
	$app->get('/funciones/:sector/:subsector/:unidad/:cuenta/:finalidad', function($sector,$subsector,$unidad,$cuenta,$finalidad)    use($app, $db) {
		//$cuenta = $_SESSION ["idCuentaActual"];
		if($sector==''){
			$sql="SELECT cd.finalidad,cd.funcion id,fu.nombre texto FROM sia_cuentasdetalles cd
				LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
				LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
				WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad
				GROUP BY cd.finalidad,cd.funcion,fu.nombre ORDER BY cd.funcion ASC;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad));
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			
		}else{
			$sql="SELECT cd.finalidad,cd.funcion id,fu.nombre texto FROM sia_cuentasdetalles cd
				LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
				LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
				WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad
				GROUP BY cd.finalidad,cd.funcion,fu.nombre ORDER BY cd.funcion ASC;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad));
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		}

		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


///subfunciones
	$app->get('/subfunciones/:sector/:subsector/:unidad/:cuenta/:funcion/:finalidad', function($sector,$subsector,$unidad,$cuenta,$funcion,$finalidad)    use($app, $db) {
		$sql="SELECT cd.finalidad,cd.funcion,cd.subfuncion id,sb.nombre texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.funcion=:funcion AND cd.finalidad=:finalidad
			GROUP BY cd.finalidad,cd.funcion,cd.subfuncion,sb.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':funcion' => $funcion,':finalidad' => $finalidad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///Actividad
	$app->get('/actividad/:sector/:subsector/:unidad/:cuenta/:funcion/:finalidad/:subfuncion', function($sector,$subsector,$unidad,$cuenta,$funcion,$finalidad,$subfuncion)    use($app, $db) {
		$sql="SELECT cd.finalidad, cd.funcion,cd.subfuncion, ac.idActividad id, ac.nombre texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_actividades ac on cd.idCuenta = ac.idCuenta and cd.finalidad = ac.idFinalidad AND cd.funcion = ac.idFuncion AND cd.subfuncion = ac.idSubfuncion AND cd.actividad = ac.idActividad 
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.funcion=:funcion AND cd.finalidad=:finalidad AND cd.subfuncion=:subfuncion
			GROUP BY cd.finalidad, cd.funcion,cd.subfuncion, ac.idActividad, ac.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':funcion' => $funcion,':finalidad' => $finalidad,':subfuncion' => $subfuncion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///Capitulo
	$app->get('/capitulo', function()    use($app, $db) {
		$cuenta = $_SESSION ["idCuentaActual"];
		$sql="   SELECT  idCapitulo id, CONCAT(idCapitulo,'-',nombre) texto FROM sia_capitulos Where idCuenta =:cuenta;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///Partida
	$app->get('/partida/:sector/:subsector/:unidad/:cuenta/:capitulo', function($sector,$subsector,$unidad,$cuenta,$capitulo)    use($app, $db) {
		$sql="SELECT ca.idCapitulo, pa.idPartida, cd.partida id, CONCAT(cd.partida,'-',pa.nombre) texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_capitulos ca on cd.capitulo=ca.idCapitulo and cd.idCuenta = ca.idCuenta 
			LEFT JOIN sia_partidas pa on cd.idCuenta = pa.idCuenta and cd.capitulo = pa.idCapitulo AND cd.partida = pa.idPartida
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.capitulo=:capitulo
			GROUP BY ca.idCapitulo, pa.idPartida, cd.partida, pa.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	//cuenta-publica completa
	$app->get('/tblaCuentapublica/:cuenta', function($cuenta)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo, CONCAT(pa.idPartida,' - ',pa.nombre) partida, CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
			WHERE cd.idCuenta=:cuenta
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre, ac.nombre,ca.idCapitulo, ca.nombre, pa.idPartida, pa.nombre, fin.idFinalidad, fin.nombre,cd.original, ac.idActividad,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 



	$app->get('/finalidad/:sector/:subsector/:unidad/:cuenta/:finalidad', function($sector,$subsector,$unidad,$cuenta,$finalidad)    use($app, $db) {
		$sql=" SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente FROM sia_cuentasdetalles cd
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 

	$app->get('/capitulo/:sector/:subsector/:unidad/:cuenta/:capitulo', function($sector,$subsector,$unidad,$cuenta,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,
			CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.capitulo =:capitulo
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finacapi/:sector/:subsector/:unidad/:cuenta/:finalidad/:capitulo', function($sector,$subsector,$unidad,$cuenta,$finalidad,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      FROM sia_cuentasdetalles cd
	      LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	      LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo
	      GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfuncap/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:capitulo', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	


	$app->get('/finfunsubcap/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion/:capitulo', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
 			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfunsubactcap/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion/:actividad/:capitulo', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion,$actividad,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
 			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion AND cd.actividad =:actividad
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente,ac.idActividad,ac.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfunsubactcappar/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion/:actividad/:capitulo/:partida', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion,$actividad,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      	FROM sia_cuentasdetalles cd
	      	LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      	LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	 		LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
	      	LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
	 		LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
	      	LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      	LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      	WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion AND cd.subfuncion=:subfuncion AND cd.actividad=:actividad
	      	GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, sb.nombre, ac.idActividad,ac.nombre,fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});
	
	$app->get('/finfun/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	


	$app->get('/finfunsub/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/finfunsubact/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion/:actividad', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion,$actividad)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
      		WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion AND cd.actividad=:actividad
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente,ac.idActividad,ac.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/fincappar/:sector/:subsector/:unidad/:cuenta/:finalidad/:capitulo/:partida', function($sector,$subsector,$unidad,$cuenta,$finalidad,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      FROM sia_cuentasdetalles cd
	      LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	      LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida
	      GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/finfuncappar/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:capitulo/:partida', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      	FROM sia_cuentasdetalles cd
	      	LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      	LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	 		LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
	      	LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      	LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      	WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion
	      	GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



		$app->get('/finfunsubcappar/:sector/:subsector/:unidad/:cuenta/:finalidad/:funcion/:subfuncion/:capitulo/:partida', function($sector,$subsector,$unidad,$cuenta,$finalidad,$funcion,$subfuncion,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
      		FROM sia_cuentasdetalles cd
      		LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
 			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
      		LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
      		WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion AND cd.subfuncion=:subfuncion
      		GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, sb.nombre, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/cappar/:sector/:subsector/:unidad/:cuenta/:capitulo/:partida', function($sector,$subsector,$unidad,$cuenta,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle,CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
      		FROM sia_cuentasdetalles cd
      		LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
      		LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
      		WHERE cd.sector=:sector AND cd.subsector=:subsector AND cd.unidad=:unidad AND cd.idCuenta=:cuenta AND cd.capitulo=:capitulo AND cd.partida=:partida
      		GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array('sector' => $sector,'subsector' => $subsector,'unidad' => $unidad,':cuenta' => $cuenta,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///funciones
	$app->get('/funciones/:cuenta/:finalidad', function($cuenta,$finalidad)    use($app, $db) {
		//$cuenta = $_SESSION ["idCuentaActual"];
			$sql="SELECT cd.finalidad,cd.funcion id,fu.nombre texto FROM sia_cuentasdetalles cd
				LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
				LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
				WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad
				GROUP BY cd.finalidad,cd.funcion,fu.nombre ORDER BY cd.funcion ASC;";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad));
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


///subfunciones
	$app->get('/subfunciones/:cuenta/:funcion/:finalidad', function($cuenta,$funcion,$finalidad)    use($app, $db) {
		$sql="SELECT cd.finalidad,cd.funcion,cd.subfuncion id,sb.nombre texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			WHERE cd.idCuenta=:cuenta AND cd.funcion=:funcion AND cd.finalidad=:finalidad
			GROUP BY cd.finalidad,cd.funcion,cd.subfuncion,sb.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':funcion' => $funcion,':finalidad' => $finalidad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///Actividad
	$app->get('/actividad/:cuenta/:funcion/:finalidad/:subfuncion', function($cuenta,$funcion,$finalidad,$subfuncion)    use($app, $db) {
		$sql="SELECT cd.finalidad, cd.funcion,cd.subfuncion, ac.idActividad id, ac.nombre texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_actividades ac on cd.idCuenta = ac.idCuenta and cd.finalidad = ac.idFinalidad AND cd.funcion = ac.idFuncion AND cd.subfuncion = ac.idSubfuncion AND cd.actividad = ac.idActividad 
			WHERE cd.idCuenta=:cuenta AND cd.funcion=:funcion AND cd.finalidad=:finalidad AND cd.subfuncion=:subfuncion
			GROUP BY cd.finalidad, cd.funcion,cd.subfuncion, ac.idActividad, ac.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':funcion' => $funcion,':finalidad' => $finalidad,':subfuncion' => $subfuncion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

///Partida
	$app->get('/partida/:cuenta/:capitulo', function($cuenta,$capitulo)    use($app, $db) {
		$sql="SELECT ca.idCapitulo, pa.idPartida, cd.partida id, CONCAT(cd.partida,'-',pa.nombre) texto FROM sia_cuentasdetalles cd
			LEFT JOIN sia_capitulos ca on cd.capitulo=ca.idCapitulo and cd.idCuenta = ca.idCuenta 
			LEFT JOIN sia_partidas pa on cd.idCuenta = pa.idCuenta and cd.capitulo = pa.idCapitulo AND cd.partida = pa.idPartida
			WHERE cd.idCuenta=:cuenta AND cd.capitulo=:capitulo
			GROUP BY ca.idCapitulo, pa.idPartida, cd.partida, pa.nombre;";
		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/finalidad/:cuenta/:finalidad', function($cuenta,$finalidad)    use($app, $db) {
		$sql=" SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente FROM sia_cuentasdetalles cd
			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad
			GROUP BY cd.idCuenta, fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	}); 

	$app->get('/capitulo/:cuenta/:capitulo', function($cuenta,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.idCuenta=:cuenta AND cd.capitulo =:capitulo
			GROUP BY cd.idCuenta,ca.idCapitulo, ca.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finacapi/:cuenta/:finalidad/:capitulo', function($cuenta,$finalidad,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      FROM sia_cuentasdetalles cd
	      LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	      LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo
	      GROUP BY cd.idCuenta,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfuncap/:cuenta/:finalidad/:funcion/:capitulo', function($cuenta,$finalidad,$funcion,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, fu.nombre funombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion
			GROUP BY cd.idCuenta, fu.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	


	$app->get('/finfunsubcap/:cuenta/:finalidad/:funcion/:subfuncion/:capitulo', function($cuenta,$finalidad,$funcion,$subfuncion,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
 			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfunsubactcap/:cuenta/:finalidad/:funcion/:subfuncion/:actividad/:capitulo', function($cuenta,$finalidad,$funcion,$subfuncion,$actividad,$capitulo)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
 			LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion AND cd.actividad =:actividad
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente,ac.idActividad,ac.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad,':capitulo' => $capitulo));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	

	$app->get('/finfunsubactcappar/:cuenta/:finalidad/:funcion/:subfuncion/:actividad/:capitulo/:partida', function($cuenta,$finalidad,$funcion,$subfuncion,$actividad,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      	FROM sia_cuentasdetalles cd
	      	LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      	LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	 		LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
	      	LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
	 		LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
	      	LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      	LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      	WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion AND cd.subfuncion=:subfuncion AND cd.actividad=:actividad
	      	GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, sb.nombre, ac.idActividad,ac.nombre,fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});
	
	$app->get('/finfun/:cuenta/:finalidad/:funcion', function($cuenta,$finalidad,$funcion)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});	


	$app->get('/finfunsub/:cuenta/:finalidad/:funcion/:subfuncion', function($cuenta,$finalidad,$funcion,$subfuncion)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/finfunsubact/:cuenta/:finalidad/:funcion/:subfuncion/:actividad', function($cuenta,$finalidad,$funcion,$subfuncion,$actividad)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ac.idActividad,' - ', ac.nombre) actividad,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
			FROM sia_cuentasdetalles cd
 			LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
			LEFT JOIN sia_actividades ac on cd.idCuenta=ac.idCuenta AND cd.finalidad=ac.idFinalidad AND cd.funcion=ac.idFuncion AND cd.subfuncion=ac.idSubfuncion AND cd.actividad = ac.idActividad
      		WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.funcion=:funcion AND cd.subfuncion=:subfuncion AND cd.actividad=:actividad
			GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre, fu.nombre, sb.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente,ac.idActividad,ac.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':actividad' => $actividad));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/fincappar/:cuenta/:finalidad/:capitulo/:partida', function($cuenta,$finalidad,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      FROM sia_cuentasdetalles cd
	      LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	      LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida
	      GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});


	$app->get('/finfuncappar/:cuenta/:finalidad/:funcion/:capitulo/:partida', function($cuenta,$finalidad,$funcion,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
	      	FROM sia_cuentasdetalles cd
	      	LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
	      	LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
	 		LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion 
	      	LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
	      	LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
	      	WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion
	      	GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});



	$app->get('/finfunsubcappar/:cuenta/:finalidad/:funcion/:subfuncion/:capitulo/:partida', function($cuenta,$finalidad,$funcion,$subfuncion,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle, CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(fin.idFinalidad,' - ',fin.nombre) finalidad, fu.nombre funombre, sb.nombre sbnombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
      		FROM sia_cuentasdetalles cd
      		LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
 			LEFT JOIN sia_funciones fu on cd.idCuenta=fu.idCuenta AND cd.finalidad=fu.idFinalidad and cd.funcion = fu.idFuncion
      		LEFT JOIN sia_subfunciones sb on cd.idCuenta=sb.idCuenta AND cd.finalidad = sb.idFinalidad AND cd.funcion=sb.idFuncion AND cd.subfuncion=sb.idSubfuncion
      		LEFT JOIN sia_finalidades fin on cd.idCuenta=fin.idCuenta AND cd.finalidad=fin.idFinalidad
      		LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
      		WHERE cd.idCuenta=:cuenta AND cd.finalidad=:finalidad AND cd.capitulo=:capitulo AND cd.partida=:partida AND cd.funcion =:funcion AND cd.subfuncion=:subfuncion
      		GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,fin.idFinalidad, fu.nombre, sb.nombre, fin.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':finalidad' => $finalidad,':funcion' => $funcion,':subfuncion' => $subfuncion,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	$app->get('/cappar/:cuenta/:capitulo/:partida', function($cuenta,$capitulo,$partida)    use($app, $db) {
		$sql="SELECT cd.idCuenta,cd.idCuentaDetalle,CONCAT(CONCAT(un.idsector,un.idsubsector,un.idunidad),' - ',un.nombre) nombre,CONCAT(ca.idCapitulo, ' - ',ca.nombre) capitulo,CONCAT(pa.idPartida,' - ',pa.nombre) partida,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.original)), 'c', 'en-US') as original,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.modificado)), 'c', 'en-US') as modificado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.ejercido)), 'c', 'en-US') as ejercido,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pagado)), 'c', 'en-US') as pagado,FORMAT(sum(CONVERT(DECIMAL(20,2),cd.pendiente)), 'c', 'en-US') as pendiente
      		FROM sia_cuentasdetalles cd
      		LEFT JOIN sia_unidades un on CONCAT(cd.sector,cd.subsector,cd.unidad) = CONCAT(un.idSector,un.idSubsector,un.idUnidad) AND cd.idCuenta=un.idCuenta
      		LEFT JOIN sia_capitulos ca on cd.idCuenta=ca.idCuenta AND cd.capitulo=ca.idCapitulo
      		LEFT JOIN sia_partidas pa on cd.idCuenta=pa.idCuenta AND cd.capitulo = pa.idCapitulo AND cd.partida=pa.idPartida
      		WHERE cd.idCuenta=:cuenta AND cd.capitulo=:capitulo AND cd.partida=:partida
      		GROUP BY cd.idCuenta, un.idSector , un.idSubsector, un.idUnidad, un.nombre,ca.idCapitulo, ca.nombre,cd.original,cd.idCuentaDetalle,cd.capitulo,cd.pagado,cd.pendiente, pa.idPartida, pa.nombre ORDER BY cd.capitulo;";

		$dbQuery = $db->prepare($sql);
		$dbQuery->execute(array(':cuenta' => $cuenta,':capitulo' => $capitulo,':partida' => $partida));
		$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
		if(!$result){
			$app->halt(404, "NO SE ENCONTRARON DATOS ");
		}else{
			echo json_encode($result);
		}
	});

	// INICIO --- PEA

		$app->get('/consultapea', function()  use ($app, $db) {
			$cuenta = $_SESSION["idCuentaActual"];
			$area = $_SESSION["idArea"];
			$usrActual = $_SESSION["idUsuario"];
			$global = $_SESSION["usrGlobal"];
			
			$sql="SELECT a.idAuditoria auditoria, COALESCE(convert(varchar(20),a.clave),convert(varchar(20),a.idAuditoria)) claveAuditoria, isnull(a.clave,'') clave, ar.nombre area,dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto,ta.nombre tipo, a.idProceso proceso, a.idEtapa etapa
				FROM sia_programas p
				INNER JOIN sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma=a.idPrograma
				INNER JOIN sia_areas ar on a.idArea=ar.idArea
				LEFT JOIN sia_tiposauditoria ta on a.tipoAuditoria= ta.idTipoAuditoria
				WHERE a.idCuenta=:cuenta and a.clave is not null
				ORDER BY a.idAuditoria;";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta));						
				
				$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			$app->render('pea.php', $result);
		})->name('listaPea');

		$app->get('/lstAuditoriaBypea/:id', function($id)    use($app, $db) {
			$cuenta = $_SESSION["idCuentaActual"];
			$area = $_SESSION["idArea"];
			
				$sql="SELECT a.idCuenta cuenta,a.idPrograma programa, a.idAuditoria auditoria,COALESCE(convert(varchar(20),a.clave),convert(varchar(20),a.idAuditoria)) claveAuditoria, isnull(a.clave,'') clave, a.tipoAuditoria tipo, a.idArea area,a.objetivo, a.alcance, a.justificacion, a.tipoPresupuesto, a.acompanamiento, a.idProceso proceso, a.idEtapa etapa, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fInicio,105), ''), '1900-01-01', '') feInicio, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fFin,105), ''), '1900-01-01', '') feFin, a.tipoObservacion tipoObse, a.observacion,a.idResponsable responsable, isnull(a.idSubresponsable,'') subresponsable,REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fIRA,105), ''), '1900-01-01', '') ira, REPLACE(ISNULL(CONVERT(VARCHAR(10),a.fIFA,105), ''), '1900-01-01', '') ifa
					FROM sia_auditorias a 
					LEFT JOIN  sia_objetos o ON a.idObjeto = o.idObjeto 
					WHERE  a.idCuenta=:cuenta AND a.idAuditoria =:id ORDER BY a.idAuditoria;";
					
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':cuenta' => $cuenta, ':id' => $id));

			$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});

		//recuperar de modulo de PEA--
		$app->get('/tblComentariosByAuditoriaspea/:id/:seccion/:seccion2', function($id,$seccion,$seccion2)  use($app, $db) {
	        $sql="SELECT DISTINCT 'Registrado el ' + CONVERT(VARCHAR(10), ac.fAlta, 103) + ' a las ' + SUBSTRING(CONVERT(VARCHAR(10), ac.fAlta, 108),1,5) fechayhora, ac.idAuditoriaComentario id, u.saludo + ' ' + u.nombre + ' ' + u.paterno + ' ' + u.materno usuario, ac.comentario comentario FROM sia_auditoriasComentarios ac INNER JOIN sia_usuarios u ON ac.usrAlta = u.idUsuario WHERE ac.idAuditoria=:id AND ac.Seccion IN(:seccion,:seccion2) ORDER BY ac.idAuditoriaComentario DESC;";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':id' => $id,':seccion' => $seccion,':seccion2' => $seccion2));

			// USO DEL FETCH PARA CUANDO SE REGRESA UN GRUPO DE REGISTROS
			$result ['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});

		//recuperar de modulo de auditorias--
		$app->get('/tblComentariosByAuditoriasaudi/:id/:seccion/:seccion2', function($id,$seccion,$seccion2)  use($app, $db) {
	        $sql="SELECT DISTINCT 'Registrado el ' + CONVERT(VARCHAR(10), ac.fAlta, 103) + ' a las ' + SUBSTRING(CONVERT(VARCHAR(10), ac.fAlta, 108),1,5) fechayhora, ac.idAuditoriaComentario id, u.saludo + ' ' + u.nombre + ' ' + u.paterno + ' ' + u.materno usuario, ac.comentario comentario FROM sia_auditoriasComentarios ac INNER JOIN sia_usuarios u ON ac.usrAlta = u.idUsuario WHERE ac.idAuditoria=:id AND ac.Seccion IN(:seccion,:seccion2) ORDER BY ac.idAuditoriaComentario DESC;";

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':id' => $id,':seccion' => $seccion,':seccion2' => $seccion2));

			// USO DEL FETCH PARA CUANDO SE REGRESA UN GRUPO DE REGISTROS
			$result ['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});

		$app->get('/guardar/auditoriaComentario/:cadena', function($cadena)  use($app, $db) {
			$usrActual = $_SESSION ["idUsuario"];
			$cuenta    = $_SESSION ["idCuentaActual"];
			$programa  = $_SESSION ["idProgramaActual"];


			$cdValores = explode("|", $cadena);

			$oper           	   = $cdValores [0];
			$idAuditoria       	   = $cdValores [1];
			$idAuditoriaComentario = $cdValores [2];
			$comentario            = $cdValores [3];
			$idPrioridad           = $cdValores [4];
			$estatus 		       = $cdValores [5];
			$Seccion 			   = $cdValores [6];

			try
			{
				if($oper=='INS')
				{
					$sql="INSERT INTO sia_AuditoriasComentarios (idCuenta, idPrograma, idAuditoria, comentario, idPrioridad,fAlta,usrAlta,Seccion) VALUES (:idCuenta, :idPrograma, :idAuditoria, :comentario, :idPrioridad, getdate(), :usrAlta,:Seccion);";

					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':idCuenta'=>$cuenta, ':idPrograma'=>$programa, ':idAuditoria'=>$idAuditoria, ':comentario'=>$comentario, ':idPrioridad'=>$idPrioridad, ':usrAlta'=>$usrActual,':Seccion'=>$Seccion));
				}
				else
				{
					$sql="UPDATE sia_AuditoriasComentarios SET comentario=:comentario, idPrioridad=:idPrioridad ,usrModificacion=:usrModificacion, fModificacion=getdate(), estatus=:estatus WHERE idCuenta=:idCuenta AND idPrograma=:idPrograma AND idAuditoria=:idAuditoria AND idAuditoriaComentario=:idAuditoriaComentario AND Seccion=:Seccion";
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':comentario'=>$comentario, ':idPrioridad'=>$idPrioridad, ':usrModificacion'=>$usrActual, ':estatus'=>$estatus, ':idCuenta'=>$cuenta, ':idPrograma'=>$programa, ':idAuditoria'=>$idAuditoria, ':idAuditoriaComentario'=>$idAuditoriaComentario,':Seccion'=>$Seccion));
				}
				echo"OK";
			}catch (Exception $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		});

		$app->get('/dpsAuditorias', function()  use($app, $db) {
			
			$cuenta = $_SESSION["idCuentaActual"];
			
			$sql="SELECT ta.nombre texto, count(*) valor " .
			"FROM sia_auditorias a INNER JOIN sia_tiposauditoria ta  ON a.tipoAuditoria=ta.idTipoAuditoria " .
			"WHERE a.idCuenta=:cuenta and a.clave is not null " .
			"GROUP BY ta.nombre ORDER BY ta.nombre";
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':cuenta' => $cuenta));				
		
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($result);
		});

		$app->get('/btnreprogra', function()    use($app, $db) {
			$usrActual = $_SESSION["idUsuario"];

			$sql="SELECT TOP 1 ur.idRol rol FROM sia_usuariosroles ur " .
				"inner join sia_rolesetapas re on ur.idRol = re.idRol " .
				"inner join sia_etapas et on re.idEtapa = et.idEtapa and re.idProceso = et.idProceso " .
				"where ur.idUsuario=:usrActual;";
		
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':usrActual' => $usrActual));
			$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});

		$app->get('/btnformatoreprogramacion/:auditoria', function($auditoria)    use($app, $db) {
			

			$sql="SELECT count(idAuditoria) total FROM sia_AuditoriasReprogramaciones where idAuditoria=:auditoria;";
		
			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':auditoria' => $auditoria));
			$result = $dbQuery->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});



		

	// INICIO --- REP

		$app->get('/consultarep', function()  use ($app, $db) {
			$cuenta = $_SESSION["idCuentaActual"];
			$area = $_SESSION["idArea"];
			$usrActual = $_SESSION["idUsuario"];
			$global = $_SESSION["usrGlobal"];
			
			$sql="SELECT a.idAuditoria auditoria, COALESCE(convert(varchar(20),a.clave),convert(varchar(20),a.idAuditoria)) claveAuditoria, isnull(a.clave,'') clave, ar.nombre area,dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, dbo.lstObjetosByAuditoria(a.idAuditoria) objeto,ta.nombre tipo, a.idProceso proceso, a.idEtapa etapa
				FROM sia_programas p
				INNER JOIN sia_auditorias a on p.idCuenta=a.idCuenta and p.idPrograma=a.idPrograma
				INNER JOIN sia_areas ar on a.idArea=ar.idArea
				LEFT JOIN sia_tiposauditoria ta on a.tipoAuditoria= ta.idTipoAuditoria
				WHERE a.idCuenta=:cuenta and a.clave is not null
				ORDER BY a.idAuditoria;";

				$dbQuery = $db->prepare($sql);
				$dbQuery->execute(array(':cuenta' => $cuenta));						
				
				$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			$app->render('REP.php', $result);
		})->name('listaRep');


		$app->get('/lstApartadosrep/:auditoria', function($auditoria)    use($app, $db) {
				
				$sql="SELECT aa.idAuditoria auditoria,ap.orden,aa.idApartado apartado,ap.nombre ,aa.actividad
					from sia_AuditoriasApartados aa inner join sia_apartados ap on aa.idApartado = ap.idApartado
					where aa.idAuditoria=:auditoria order by ap.orden ASC; ";
					
					$dbQuery = $db->prepare($sql);
					$dbQuery->execute(array(':auditoria' => $auditoria));

			$result ['datos']= $dbQuery->fetchALL(PDO::FETCH_ASSOC);
			if(!$result){
				$app->halt(404, "NO SE ENCONTRARON DATOS ");
			}else{
				echo json_encode($result);
			}
		});

		$app->get('/lstAuditoriasUnidades/:auditoria', function($auditoria)  use($app, $db) {
			
			$cuenta = $_SESSION["idCuentaActual"];
			
			
			$sql="SELECT aa.idPrograma programa,aa.idAuditoria auditoria, aa.idSector +'|'+ aa.idSubsector+'|'+aa.idUnidad id,un.nombre texto FROM sia_auditoriasunidades aa " .
  				"INNER JOIN sia_unidades un on aa.idCuenta = un.idCuenta and aa.idSector = un.idSector and aa.idSubsector = un.idSubsector and aa.idUnidad = un.idUnidad " . 
  				"WHERE aa.idAuditoria=:auditoria and aa.idCuenta=:cuenta;";
  			
  			//$sql="SELECT aa.idPrograma programa,aa.idAuditoria auditoria, concat(aa.idSector,aa.idSubsector,aa.idUnidad) id,un.nombre FROM sia_auditoriasunidades aa INNER JOIN sia_unidades un on aa.idCuenta = un.idCuenta and aa.idSector = un.idSector and aa.idSubsector = un.idSubsector and aa.idUnidad = un.idUnidad WHERE aa.idAuditoria=:auditoria and aa.idCuenta=:cuenta;";	

			$dbQuery = $db->prepare($sql);
			$dbQuery->execute(array(':auditoria' => $auditoria,':cuenta' => $cuenta));				
		
			$result['datos'] = $dbQuery->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($result);
		});	



?>