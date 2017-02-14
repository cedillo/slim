
<!DOCTYPE html>
<!-- saved from url=(0035)http://ashobiz.asia/mac52/macadmin/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <meta charset="utf-8">
		<script type="text/javascript" src="js/canvasjs.min.js"></script>
		<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js?ver=3.1.2"></script> 
	
  	<style type="text/css">		
		@media screen and (min-width: 768px) {
			#mapa_content {width:100%; height:150px;}
			#canvasJG, #canvasJD, #canvasDIP{height:125px; width:100%;}			
			#modalHoja .modal-dialog  {width:50%;}
			
			.auditor{background:#f4f4f4; font-size:6pt; padding:7px; display:inline; margin:1px; border:1px gray solid;}
			
			label {text-align:right;}
			
.auditor[type=checkbox] {
    content: "\2713";
    text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
    font-size: 15px;
    color: #f3f3f3;
    text-align: center;
    line-height: 15px;
}
			
			
		}
	</style>
  
  <script type="text/javascript"> 
	var mapa;
	var nZoom=10;
	

	
	
	window.onload = function () {
		var chart = new CanvasJS.Chart("canvasJG", {
			title:{ text: "TIPOS DE PAPELES", fontColor: "#2f4f4f",fontSize: 10,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			//legend: {verticalAlign: "bottom", horizontalAlign: "center" },
			theme: "theme1", 
		  data: [
		  {        
			//color: "#B0D0B0",
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			//indexLabel: "{y}",
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "NORMATIVIDAD 59"}, {y: 21,  indexLabel: "RESUMEN 21" }, {y: 31,  indexLabel: "FINANCIERO 31" }
			]
		  }   
		  ]
		});
		chart.render();

		var chart2 = new CanvasJS.Chart("canvasJD", {
			title:{ text: "ESTATUS DE CAPTURA", fontColor: "#2f4f4f",fontSize: 10,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			//legend: {verticalAlign: "bottom", horizontalAlign: "center" },
			theme: "theme1", 
		  data: [
		  {        
			//color: "#B0D0B0",
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			//indexLabel: "{y}",
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "DESDE CENTRALES 35"}, {y: 21,  indexLabel: "REMOTO 60"}, {y: 21,  indexLabel: "OTROS 10"}
			]
		  }   
		  ]
		});
		chart2.render();			
	  };
	  
	  
	function seleccionarPapel(tipo){
			document.all.divVacio.style.display='none';			
			if(tipo=="NORMATIVIDAD"){
				document.all.divNormatividad.style.display='inline';		
				document.all.divFinanciero.style.display='none';		
			}
			if(tipo=="FINANCIERO"){
				document.all.divNormatividad.style.display='none';		
				document.all.divFinanciero.style.display='inline';					
			}
	}
	  
	  

	var ventana;
	
	function modalWin(sPagina) {
		var sDimensiones; 
		
		if (window.showModalDialog) {
			sDimensiones= "dialogWidth:" + window.innerWidth + "px;dialogHeight:" + window.innerHeight + "px;";
			window.showModalDialog(sPagina,"Reporte",sDimensiones);
		} 
		else {
			sDimensiones= "width=" + window.innerWidth + ", height=" + window.innerHeight + ",location=no, titlebar=no, menubar=no,minimizable=no, resizable=no,  toolbar=no,directories=no,status=no,continued from previous linemenubar=no,scrollbars=no,resizable=no ,modal=yes";
			ventana = window.open(sPagina,'Reporte', sDimensiones);
			ventana.focus();
		}
	}	
	
	$(document).ready(function(){	
	
		$( "#btnGuardar" ).click(function() {
			$('#modalHoja').modal('hide');
		});
		
		
		


		
		
		$( "#btnRegresar" ).click(function() {
			document.all.listasAuditoria.style.display='inline';
			document.all.capturaAuditoria.style.display='none';		
		});
		
		$( "#btnTurnar" ).click(function() {
			document.all.capturaDocto.style.display='none';
			document.all.turnaDocto.style.display='inline';
			
			document.all.btnGuardarEnviar.style.display='inline';
			document.all.btnGuardar.style.display='none';
			document.all.btnTurnar.style.display='none';
		});
		
		$( "#btnGuardarEnviar" ).click(function() {
			alert("Guardando y enviando");
			document.all.capturaDocto.style.display='inline';
			document.all.turnaDocto.style.display='none';
			
			document.all.btnGuardarEnviar.style.display='none';
			document.all.btnGuardar.style.display='inline';
			document.all.btnTurnar.style.display='inline';

			$('#modalHoja').modal('hide');
			
			

		});
		$( "#btnCancelar" ).click(function() {
			document.all.capturaDocto.style.display='inline';
			document.all.turnaDocto.style.display='none';

			document.all.btnGuardarEnviar.style.display='none';
			document.all.btnGuardar.style.display='inline';
			document.all.btnTurnar.style.display='inline';
			
			$('#modalHoja').modal('hide');
		});
		
		
		$( "#btnLigarDocto" ).click(function() {		
			$( "#btnUpload" ).click(); 
		});

		$( "#btnAnexarDocto" ).click(function() {		
			$( "#btnUpload" ).click(); 
		});	
		$( "#btnNuevoDocto" ).click(function() {
			$('#modalDocto').removeClass("invisible");
			$('#modalDocto').modal('toggle');
			$('#modalDocto').modal('show');						
		});
		$( "#btnCancelarDocto" ).click(function() {
			$('#modalDocto').modal('hide');
		});
		$( "#btnGuardarDocto" ).click(function() {
			$('#modalDocto').modal('hide');
		});		
		
		$( "#btnNuevoDictamen" ).click(function() {
			$('#modalDictamen').removeClass("invisible");
			$('#modalDictamen').modal('toggle');
			$('#modalDictamen').modal('show');						
		});
		$( "#btnCancelarDictamen" ).click(function() {
			$('#modalDictamen').modal('hide');
		});
		$( "#btnGuardarDictamen" ).click(function() {
			$('#modalDictamen').modal('hide');
		});			
		
	
	});
	
	
    </script>
  
  
  <!-- Title and other stuffs -->
  <title>Sistema Integral de Auditorias</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Font awesome icon -->
  <link rel="stylesheet" href="css/font-awesome.min.css"> 
  <!-- jQuery UI -->
  <link rel="stylesheet" href="css/jquery-ui.css"> 
  <!-- Calendar -->
  <link rel="stylesheet" href="css/fullcalendar.css">
  <!-- prettyPhoto -->
  <link rel="stylesheet" href="css/prettyPhoto.css">  
  <!-- Star rating -->
  <link rel="stylesheet" href="css/rateit.css">
  <!-- Date picker -->
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
  <!-- CLEditor -->
  <link rel="stylesheet" href="css/jquery.cleditor.css"> 
  <!-- Data tables -->
  <link rel="stylesheet" href="jquery.dataTables.css"> 
  <!-- Bootstrap toggle -->
  <link rel="stylesheet" href="css/jquery.onoff.css">
  <!-- Main stylesheet -->
  <link href="css/style-dashboard.css" rel="stylesheet">
  <!-- Widgets stylesheet -->
  <link href="css/widgets.css" rel="stylesheet">   
  
  <script src="./Dashboard - MacAdmin_files/respond.min.js"></script>
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png">


<body>
	<div class="navbar navbar-fixed-top bs-docs-nav" role="banner">  
			<div class="container">
				<!-- Navigation starts -->
				<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">			
					<div class="col-xs-12">
						<div class="col-xs-3"><a href="/"><img src="img/logo-top.png"></a></div>				
						<div class="col-xs-3">
							<ul class="nav navbar-nav "><li><a href="#"><i class="fa fa-th-list"></i> <?php echo $_SESSION["sCampanaActal"] ?></a></li></ul>
						</div>
						<div class="col-xs-3">
							<ul class="nav navbar-nav "><li><a href="./notificaciones"><i class="fa fa-envelope-o"></i> Tienes (3) Mensajes nuevos</a></li></ul>
						</div>
						<div class="col-xs-3">
						<ul class="nav navbar-nav  pull-right">
							<li class="dropdown pull-right">            
								<a data-toggle="dropdown" class="dropdown-toggle" href="/">
									<i class="fa fa-user"></i> <b>C. <?php echo $_SESSION["sUsuario"] ?></b> <b class="caret"></b> 							
								</a>
								<ul class="dropdown-menu">
								  <li><a href="./perfil"><i class="fa fa-user"></i> Perfil</a></li>
								  <li><a href="./cerrar"><i class="fa fa-sign-out"></i> Salir</a></li>
								</ul>
							</li>
							</ul>						
						</div>				
					</div>
				</nav>
			</div>
	</div>



<!-- Main content starts -->

<div class="content">
  	<div class="panel panel-default">
			<div class="panel-body">
	  			<div class="row">
					<br><br>
					<div class="col-xs-7">
						<div class="form-group">							
							<div class="col-xs-6"><div id="canvasJG" ></div></div>						
							<div class="col-xs-6"><div id="canvasJD" ></div></div>
						</div>
						<div class="clearfix"></div>						
						<div class="widget">
							<div class="widget-head">
							  <div class="pull-left">Captura de Papel de Trabajo</div>
							  <div class="clearfix"></div>
							</div>              
							<div class="widget-content">
								<br>
								<div class="form-group">
									<label class="col-xs-1 control-label">Sujeto</label>
									<div class="col-xs-11">
										<select class="form-control" name="txtNivel">
											<option value="">Seleccione...</option>
											<option value="" SELECTED>SECRETARÍA DE OBRAS Y SERVICIOS</option>
										</select>
									</div>
								</div>									
								
								<br>
								<div class="form-group">									
									<label class="col-xs-1 control-label">Auditoría</label>
									<div class="col-xs-5">
										<select class="form-control" name="txtNivel">
											<option value="">Seleccione</option>
											<option value="" Selected>ASCM/00123/14 OBRA</option>											
											<option value="">ASCM/00124/14 DESEMPEÑO</option>											
											<option value="">ASCM/00125/14 FINANCIERA</option>											

										</select>
									</div>									
	
									<label class="col-xs-3 control-label">Tipo de Papel de trabajo</label>
									<div class="col-xs-3">
										<select class="form-control" name="txtTipoPapel" onchange="javascript:seleccionarPapel(this.value);">
											<option value="">Seleccione...</option>
											<option value="NORMATIVIDAD">NORMATIVIDAD</option>
											<option value="FINANCIERO">FINANCIERO</option>
										</select>
									</div>
								</div>									
								<br>
								
								<hr>
								<div id="divVacio"><br><br><br><br><br><br><br></div>
								<div id="divNormatividad" style="display:none;"><br><br><h1>PAPEL DE TRABAJO DE NORMATIVIDAD</h1><br><br></div>
								<div id="divFinanciero" style="display:none;"><br><br><h1>PAPEL DE TRABAJO DE FINANCIERO</h1><br><br></div>
								
								<div class="clearfix"></div>
							</div>
							<div class="widget-foot">
								<button type="button" class="btn btn-warning  btn-xs" 	id="btnLigarDocto"><i class="fa fa-link"></i> Subir Papel de Trabajo</button>
								<input type="file" name="pic" accept="image/*" style="display:none;" id="btnUpload">
								<button type="button" class="btn btn-primary  btn-xs" 	id="btnLigarDocto"><i class="fa fa-floppy-o"></i> Guardar</button>
								<button type="button" class="btn btn-primary  btn-xs" 	id="btnLimpiarDocto"><i class="fa fa-eraser"></i> Limpiar campos</button>
								<div class="clearfix"></div>
							</div>
						</div>						
						
						
					</div>
					<div class="col-xs-5">
						<div class="widget">
							<div class="widget-head">
							  <div class="pull-left"><h3 class="modal-title"><i class="fa fa-cogs"></i> Papeles de Trabajo</h3></div>
							  <div class="widget-icons pull-right">
								<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 						
							  </div>  
							  <div class="clearfix"></div>
							</div>             
							<div class="widget-content">
									<div class="table-responsive" style="height: 450px; overflow: auto; overflow-x:hidden;">
										<table class="table table-striped table-bordered table-hover">
										  <thead>
											<tr><th>Clave</th><th>Sujeto de Fiscalización</th><th>Tipo de Auditoría</th><th>Tipo de Papel</th><th>No. Papel</th><th>Fecha Papel</th><th>Anexo(s)</th></tr>
										  </thead>
										  <tbody >									  
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-001/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-002/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/pdf.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-003/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"><img onclick="modalWin('mostrarPDF.php');" src="img/pdf.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-004/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-005/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-006/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-007/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/pdf.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-008/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-009/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/xls.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-010/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/pdf.gif"></td>
												</tr>
												<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
													<td>ASCM-DGL-011/14</td><td>SECRETARÍA DE OBRAS Y SERVICIOS</td><td>OBRA PÚBLICA</td><td>VERIFICACIÓN NORMATIVA</td><td>P-0023</td><td>01-Ene-2016</td><td><img onclick="modalWin('mostrarPDF.php');" src="img/pdf.gif"></td>
												</tr>									
										  </tbody>
										</table>
									</div>
							</div>
						</div>				
					</div>
				</div>
			</div>
	</div>
				
</div>


<!-- Content ends -->


<!-- Footer starts -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
            <!-- Copyright info -->
		<p class="copy">Copyright © 2016 | Auditoría Superior de la Ciudad de México</p>
      </div>
    </div>
  </div>
</footer> 	

<!-- Footer ends -->

<!-- Scroll to top -->
<span class="totop" style="display: none;"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 

<!-- JS -->
<script src="./Dashboard - MacAdmin_files/jquery.js"></script> <!-- jQuery -->
<script src="./Dashboard - MacAdmin_files/bootstrap.min.js"></script> <!-- Bootstrap -->
<script src="./Dashboard - MacAdmin_files/jquery-ui.min.js"></script> <!-- jQuery UI -->
<script src="./Dashboard - MacAdmin_files/moment.min.js"></script> <!-- Moment js for full calendar -->
<script src="./Dashboard - MacAdmin_files/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
<script src="./Dashboard - MacAdmin_files/jquery.rateit.min.js"></script> <!-- RateIt - Star rating -->
<script src="./Dashboard - MacAdmin_files/jquery.prettyPhoto.js"></script> <!-- prettyPhoto -->
<script src="./Dashboard - MacAdmin_files/jquery.slimscroll.min.js"></script> <!-- jQuery Slim Scroll -->
<script src="./Dashboard - MacAdmin_files/jquery.dataTables.min.js"></script> <!-- Data tables -->

<!-- jQuery Flot -->
<script src="./Dashboard - MacAdmin_files/excanvas.min.js"></script>
<script src="./Dashboard - MacAdmin_files/jquery.flot.js"></script>
<script src="./Dashboard - MacAdmin_files/jquery.flot.resize.js"></script>
<script src="./Dashboard - MacAdmin_files/jquery.flot.pie.js"></script>
<script src="./Dashboard - MacAdmin_files/jquery.flot.stack.js"></script>

<!-- jQuery Notification - Noty -->
<script src="./Dashboard - MacAdmin_files/jquery.noty.js"></script> <!-- jQuery Notify -->
<script src="./Dashboard - MacAdmin_files/default.js"></script> <!-- jQuery Notify -->
<script src="./Dashboard - MacAdmin_files/bottom.js"></script> <!-- jQuery Notify -->
<script src="./Dashboard - MacAdmin_files/topRight.js"></script> <!-- jQuery Notify -->
<script src="./Dashboard - MacAdmin_files/top.js"></script> <!-- jQuery Notify -->
<!-- jQuery Notification ends -->

<script src="./Dashboard - MacAdmin_files/sparklines.js"></script> <!-- Sparklines -->
<script src="./Dashboard - MacAdmin_files/jquery.cleditor.min.js"></script> <!-- CLEditor -->
<script src="./Dashboard - MacAdmin_files/bootstrap-datetimepicker.min.js"></script> <!-- Date picker -->
<script src="./Dashboard - MacAdmin_files/jquery.onoff.min.js"></script> <!-- Bootstrap Toggle -->
<script src="./Dashboard - MacAdmin_files/filter.js"></script> <!-- Filter for support page -->
<script src="./Dashboard - MacAdmin_files/custom.js"></script> <!-- Custom codes -->
<script src="./Dashboard - MacAdmin_files/charts.js"></script> <!-- Charts & Graphs -->

</body></html>



<div id="modalHoja" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<form id="formulario" METHOD='POST' action='/guardar/intencion' role="form" onsubmit="return validarEnvio();">
			<input type='HIDDEN' name='txtValores' value=''>								
			<!-- Modal content-->
			<div class="modal-content">									
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Registrar auditoría...</h4>
				</div>									
				<div class="modal-body">
					<div class="clearfix"></div>
				</div>
				
				<div class="modal-footer">
					<button  type="button" class="btn btn-primary active" id="btnGuardar" 		style="display:inline;">Guardar</button>	
					<button  type="button" class="btn btn-default active" id="btnCancelar" 		style="display:inline;">Cancelar</button>	
				</div>
			</div>		
		</form>
					
	</div>
</div>