<?php
	include("src/conexion.php");
	
	try{
		$db = new PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
	}catch (PDOException $e) {
		print "ERROR: " . $e->getMessage() . "<br><br>HOSTNAME: " . $hostname . " BD:" . $database . " USR: " . $username . " PASS: " . $password . "<br><br>";
		die();
	}

	$usrActual = $_POST["txtUsuario"];
	$cuenta = $_POST["txtCuenta"];
	$programa = $_POST["txtPrograma"];
	//$id = $_POST["txtIDActividad"];
	$oper = $_POST["txtOperacion"];
	$auditoria = $_POST["txtaudi"]; // The file name
	$FaseActividad = $_POST["txtFaseActividadApartado"];
	$Apartado = $_POST["txtApartado"];
	//$Apartado2 = $_POST["txtApartado2"];

	$DescripcionActividad = $_POST["txtDescripcionActividad"];
	//$DescripcionActividadCom = $_POST["txtDescripcionActividadCom"];
	//$inicioactividad = $_POST["txtInicioActividad"];
	//$Finactividad  = $_POST["txtFinActividad"];
	//$diasactividad = $_POST["txtDiasActividad"];
	//$Porcentaje = $_POST['txtPorcentajeActividad'];
	//$Prioridad = $_POST['txtPrioridadActividad'];
	//$Impacto = $_POST['txtImpactoActividad'];
	//$ResponsableActividad = $_POST['txtResponsableActividad'];
	//$EstatusActividad = $_POST['txtEstatusActividad'];
	//$NotasActividad = $_POST['txtNotasActividad'];

try{
		if($oper=='INS'){
			$sql="INSERT into sia_AuditoriasApartados (idCuenta,idPrograma,idAuditoria,idFase,idApartado,orden,actividad,fAlta,usrAlta,estatus) 
					VALUES (:cuenta,:programa,:auditoria,:FaseActividad,:Apartado,1,:DescripcionActividad,getdate(),:usrActual,'ACTIVO');";

					$dbQuery = $db->prepare($sql);

					$dbQuery->execute(array(':cuenta' => $cuenta,':programa' => $programa,':auditoria' => $auditoria,':FaseActividad' => $FaseActividad,':Apartado' => $Apartado,':DescripcionActividad' => $DescripcionActividad,':usrActual' => $usrActual));

					//echo "INSERTAR <hr> $sql <br> <br>:cuenta=$cuenta  programa=$programa auditoria=$auditoria FaseActividad=$FaseActividad inicioactividad=$inicioactividad Finactividad=$Finactividad Porcentaje=$Porcentaje  Prioridad=$Prioridad Impacto=$Impacto NotasActividad=$NotasActividad ResponsableActividad =$ResponsableActividad usrAlta=$usrActual estatus=$EstatusActividad diasactividad=$diasactividad DescripcionActividad=$DescripcionActividad Apartado=$Apartado DescripcionActividadCom=$DescripcionActividadCom";
					echo "OK";

					}else{
						$sql="UPDATE sia_AuditoriasApartados SET actividad = :DescripcionActividad,fModificacion = getdate(),usrModificacion = :usrActual 
							WHERE idAuditoria = :auditoria  AND idApartado = :Apartado;";


						$dbQuery = $db->prepare($sql);

						$dbQuery->execute(array(':DescripcionActividad' => $DescripcionActividad,':usrActual' => $usrActual,':auditoria' => $auditoria,':Apartado' => $Apartado));

					
						echo "ACTUALIZAR <hr> $sql <br> <br> DescripcionActividad=$DescripcionActividad usrAlta=$usrActual  auditoria=$auditoria  FaseActividad=$FaseActividad Apartado=$Apartado";
						//echo "OK";

					}
	}catch (PDOException $e) {
			echo  "<br>Error de BD: " . $e->getMessage();
			die();
		}   

                                    
	




?>