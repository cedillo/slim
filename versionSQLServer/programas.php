<!DOCTYPE html>
<!-- saved from url=(0035)http://ashobiz.asia/mac52/macadmin/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <meta charset="utf-8">
		<script type="text/javascript" src="js/canvasjs.min.js"></script>
		<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js?ver=3.1.2"></script> 
	
  	<style type="text/css">		
		@media screen and (min-width: 768px) {
			#mapa_content {width:100%; height:150px;}
			#canvasJG, #canvasJD, #canvasDIP{height:175px; width:100%;}			
			#modalFlotante .modal-dialog  {width:50%;}
			.auditor{background:#f4f4f4; font-size:7pt; padding:2px; display:inline; margin:2px; border:1px black solid;}
			label {text-align:right;}
			
		}
	</style>
  
  <script type="text/javascript"> 
	var mapa;
	var nZoom=10;
	
	function agregarAuditoria(){
		document.all.listasAuditoria.style.display='none';
		document.all.capturaAuditoria.style.display='inline';
	}
	
	function recuperaDocto(){
		$('#modalFlotante').removeClass("invisible");
		$('#modalFlotante').modal('toggle');
		$('#modalFlotante').modal('show');
		
		document.all.btnGuardarEnviar.style.display='none';
		document.all.btnGuardar.style.display='inline';
		document.all.btnTurnar.style.display='inline';		
	}
	
	
	
	function inicializar() {
		var opciones = {zoom: nZoom,draggable: false,scrollwheel: true,	mapTypeId: google.maps.MapTypeId.ROADMAP};
		mapa = new google.maps.Map(document.getElementById('mapa_content'), { center: {lat: 19.4339249, lng: -99.1428964},zoom: nZoom});			
	}	
	
	window.onload = function () {
		var chart = new CanvasJS.Chart("canvasJG", {
			title:{ text: "TIPOS DE AUDITORIAS", fontColor: "#2f4f4f",fontSize: 10,verticalAlign: "top", horizontalAlign: "center" },
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
			{y: 59, indexLabel: "FINANCIERAS 59"}, {y: 21,  indexLabel: "ADMINISTRATIVAS 21" }, {y: 31,  indexLabel: "OBRA 31" }
			]
		  }   
		  ]
		});
		chart.render();

		var chart2 = new CanvasJS.Chart("canvasJD", {
			title:{ text: "ESTATUS DE AUDITORIAS", fontColor: "#2f4f4f",fontSize: 10,verticalAlign: "top", horizontalAlign: "center" },
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
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chart2.render();
	
		inicializar();		
	  };

	
	
	$(document).ready(function(){	
	
	$( "#btnGuardar" ).click(function() {
		document.all.listasAuditoria.style.display='inline';
		document.all.capturaAuditoria.style.display='none';
	});
		
		$( "#btnCancelar" ).click(function() {
			document.all.listasAuditoria.style.display='inline';
			document.all.capturaAuditoria.style.display='none';
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
			<div class="row" id="listasAuditoria">
				<div class="col-md-5">
					<div class="col-md-12">
						<div id="canvasJG" ></div>
					</div>
					<div class="col-md-12">
						<table class="table table-striped table-hover table-responsive">
						  <thead>
							<tr><th>Unidad Admiva.</th><th>Financiera</th><th>Obra</th><th>Desempeño</th><th>Suma</th></tr>
						  </thead>
						  <tbody >									  
								<tr style="width: 100%; font-size: xx-small">
									<td>DIRECCIÓN GENERAL DE AUDITORÍA AL SECTOR CENTRAL </td><td>83</td><td>0</td><td>0</td><td>83</td>
								</tr>
								<tr  style="width: 100%; font-size: xx-small">
									<td>DIRECCIÓN GENERAL DE AUDITORÍA A ENTIDADES PÚBLICAS Y ÓRGANOS AUTÓNOMOS </td><td>83</td><td>0</td><td>0</td><td>83</td>
								</tr>
								<tr style="width: 100%; font-size: xx-small">
									<td>DIRECCIÓN GENERAL DE AUDITORÍA A OBRA PÚBLICA Y SU EQUIPAMIENTO </td><td>83</td><td>0</td><td>0</td><td>83</td>
								</tr>
								<tr  style="width: 100%; font-size: xx-small">
									<td>DIRECCIÓN GENERAL DE AUDITORÍA PROGRAMÁTICA-PRESUPUESTAL Y DE DESEMPEÑO </td><td>83</td><td>0</td><td>0</td><td>83</td>
								</tr>
								<tr  style="width: 100%; font-size: xx-small">
									<td>TOTAL</td><td>83</td><td>0</td><td>0</td><td>83</td>
								</tr>
						  </tbody>
						</table>
					</div>
				</div>				

				<div class="col-md-7">				
				  <div class="widget">
					<div class="widget-head">
					  <div class="pull-left"><h3><i class="fa fa-search"></i> Programa General de Auditorías</h3></div>
					  <div class="clearfix"></div>
					</div>             
					<div class="widget-content">
							<div class="table-responsive" style="height: 350px; overflow: auto; overflow-x:hidden;">
								<table class="table table-striped table-bordered table-hover table-condensed">
								  <thead>
									<tr><th>No.</th><th>Sujetos de Fiscalización</th><th>Rubros o funciones de Gasto</th><th>Tipo de Auditoría</th></tr>
								  </thead>
								  <tbody >									  
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>01</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>02</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>03</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>04</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>

										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>05</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>06</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>07</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>08</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>09</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>	
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>10</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>11</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>12</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>
										<tr onclick=<?php echo "agregarAuditoria();"; ?> style="width: 100%; font-size: xx-small">
											<td>13</td><td>SECRETARÍA DE GOBIERNO </td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FINANCIERA</td>
										</tr>											
									</tbody>
								</table>
							</div>
					</div>
					<div class="widget-foot">
						<button onclick="agregarAuditoria();" type="button" class="btn btn-primary  btn-xs"><i class="fa fa-search"></i> Agregar Auditoría...</button>
						<button type="button" class="btn btn-warning  btn-xs"><i class="fa fa-external-link"></i> Enviar PGA  ...</button>
					</div>
					</div>
				</div>
			</div>
		  
			<div class="row" id="capturaAuditoria" style="display:none; padding:0px; margin:0px;">			
				<div class="col-md-12">				
					<div class="widget">
						<!-- Widget head -->
						<div class="widget-head">
						  <div class="pull-left">Captura de auditoria</div>
						  <div class="widget-icons pull-right">
							<button type="button"  id="btnGuardar" class="btn btn-primary  btn-xs"><i class="fa fa-floppy-o"></i> Guardar</button>
							<button type="button" id="btnCancelar" class="btn btn-default  btn-xs"><i class="fa fa-undo"></i> Cancelar</button> 
						  </div>  
						  <div class="clearfix"></div>
						</div>              

						<!-- Widget content -->
						<div class="widget-content">
							
							<div class="col-xs-7">
								<br>							
								<div class="form-group">
									<div class="col-xs-12"><textarea class="form-control" rows="7" placeholder="Objetivo(s)"></textarea></div>
								</div>	
								<br>								
								<div class="form-group">
									<div class="col-xs-12"><textarea class="form-control" rows="9" placeholder="Alcance(s)"></textarea></div>
								</div>	
								<br>								
								<div class="form-group">
									<div class="col-xs-12"><textarea class="form-control" rows="9" placeholder="Justificación"></textarea></div>
								</div>	
							</div>
							
							<div class="col-xs-5">
								<br>
								<div class="form-group">
									<label class="col-xs-2 control-label">Área</label>
									<div class="col-xs-10">
										<select class="form-control" name="txtTipoGestion">
											<option value="">Seleccione...</option>
											<option value="" selected>DIRECCIÓN GENERAL DE AUDITORÍAS "A"</option>
											<option value="">DIRECCIÓN GENERAL DE AUDITORÍAS "B"</option>
											<option value="">DIRECCIÓN GENERAL DE AUDITORÍAS "C"</option>
											
										</select>
									</div>
								</div>	
								<br>								
								<div class="form-group">									
									<label class="col-xs-2 control-label">Tipo</label>
									<div class="col-xs-10">
										<select class="form-control" name="txtTipoGestion">
											<option value="">Seleccione...</option>
											<option value="">DESEMPEÑO</option>
											<option value="" selected>FINANCIERA</option>
											<option value="">OBRA</option>
										</select>
									</div>								
								</div>							
								<br>
								<div class="form-group">
									<label class="col-xs-2 control-label">Instituciones</label>
									<div class="col-xs-10">
										<select class="form-control" name="txtTipoGestion">
											<option value="">Seleccione...</option>
											<option value="">INSTITUTO ELECTORAL DEL DISTRITO FEERAL</option>
											<option value="">COMISIÓN DE DERECHOS HUMANOS DEL DISTRITO FEDERAL</option>
											<option value="" SELECTED>SECRETARÍA DE GOBIERNO</option>
											
										</select>
									</div>								
								</div>
								<br><br>
								<div class="table-responsive" style="height: 400px; overflow: auto; overflow-x:hidden;">
									<table class="table table-striped table-bordered table-hover table-condensed">
									  <thead>
										<tr><th></th><th>No.</th><th>SUJETO DE FISCALIZACIÓN</th><th>OBJETO DE FISCALIZACIÓN</th><th>TIPO</th></tr>
									  </thead>
									  <tbody>									  
											<tr  style="width: 100%; font-size: xx-small">
												<td><input type="checkbox"></td><td>01</td><td>SECRETARÍA DE GOBIERNO</td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FISCALIZACIÓN</td>
											</tr>
											<tr  style="width: 100%; font-size: xx-small">
												<td><input type="checkbox"></td><td>02</td><td>SECRETARÍA DE GOBIERNO</td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FISCALIZACIÓN</td>
											</tr>
											<tr  style="width: 100%; font-size: xx-small">
												<td><input type="checkbox"></td><td>03</td><td>SECRETARÍA DE GOBIERNO</td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FISCALIZACIÓN</td>
											</tr>
											<tr  style="width: 100%; font-size: xx-small">
												<td><input type="checkbox"></td><td>04</td><td>SECRETARÍA DE GOBIERNO</td><td>CAPÍTULO 2000 “MATERIALES Y SUMINISTROS” Y PARTIDA 3221 “ARRENDAMIENTO DE EDIFICIOS” </td><td>FISCALIZACIÓN</td>
											</tr>																					
									  </tbody>
									</table>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget-foot">							
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

			</div>
		  
		  
			<div class="clearfix"></div>
		</div>
	</div>
   <div class="clearfix"></div>
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