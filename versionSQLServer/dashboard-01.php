<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <meta charset="utf-8">
		<script type="text/javascript" src="js/canvasjs.min.js"></script>
		<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js?ver=3.1.2"></script> 
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?&sensor=true"></script>
		<script type="text/javascript" src="https://google-maps-utility-library-v3.googlecode.com/svn-history/r391/trunk/markerwithlabel/src/markerwithlabel.js"></script>
		<script type="text/javascript" src="js/genericas.js"></script>

  	<style type="text/css">		
		@media screen and (min-width: 768px) {
			#canvasMapa {width:100%; height:500px;}
			#canvasJG, #canvasJD, #canvasDIP{height:180px; width:100%;}			
			
			#canvasMapa1, #canvasMapa2, #canvasMapa3, #canvasMapa4, #canvasMapa5, #canvasMapa6,
			#canvasMapa7, #canvasMapa8, #canvasMapa9, #canvasMapa10, #canvasMapa11, #canvasMapa12{height:250px; width:100%;}			
			
			#modalFlotante .modal-dialog  {width:50%;}
			
			.cabezaInfo{
				border: 0px;background-color:#D9D9F3;color: black;font-weight: Normal;
				text-align: center;font-family:'Arial';font-size:9px;padding:0px;margin:0px;cursor:pointer;-webkit-appearance: none;white-space:normal;
			}

			
		}
	</style>
  
  <script type="text/javascript"> 
	var mapa;
	var nZoom=12;
	
	
	
	var nUsr='<?php echo $_SESSION["idUsuario"];?>';
	var nCampana='<?php echo $_SESSION["idCampanaActal"];?>';
	  
	$(document).ready(function(){
		if(nUsr!="" && nCampana!=""){
			recuperarListaSelected('lstCampanasByUsr', nUsr, document.all.txtCampana,nCampana);
			cargarMenu( nCampana);			
		}else{
			if(nCampana=="")alert("Debe establecer una CAMPAÑA como PREDETERMINADA. Por favor consulte con su administrador del sistema.");
		}
		
		
	
		$( "#btnGuardar" ).click(function() {
			alert("Guardando");
			$('#modalFlotante').modal('hide');
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

			$('#modalFlotante').modal('hide');
		});
		$( "#btnCancelar" ).click(function() {
			document.all.capturaDocto.style.display='inline';
			document.all.turnaDocto.style.display='none';

			document.all.btnGuardarEnviar.style.display='none';
			document.all.btnGuardar.style.display='inline';
			document.all.btnTurnar.style.display='inline';
			
			$('#modalFlotante').modal('hide');
		});
	
	});
	
	
	function pintarAuditoria(sAuditoria, lat, lng, cadena){

		var pos = new google.maps.LatLng(lat, lng);							
		var marker = new MarkerWithLabel({position: pos, draggable: false, raiseOnDrag: true,  map:  mapa,labelContent: " Auditoría " + sAuditoria,	
		labelAnchor: new google.maps.Point(22, 0),			
		labelClass: "labels", // the CSS class for the label
		labelStyle: {opacity: 0.99}
		});
		//markers.push(marker);
		
		var infoWindow = new google.maps.InfoWindow({});
		infoWindow.setContent('<p class="cabezaInfo">AUDITORÍA<BR>' + sAuditoria + '</p>' + cadena);
		google.maps.event.addListener(marker, 'click', function() {	infoWindow.open(mapa,marker); 	});		
		google.maps.event.addListener(mapa, 'click', function() {   infoWindow.close(); });
	}


	
	
	

	function inicializar() {
		var opciones = {
			center: new google.maps.LatLng(19.4339249,-99.1428964),
			zoom:nZoom,
			panControl:false,
			zoomControl:true,
			mapTypeControl:false,
			scaleControl:false,
			streetViewControl:false,
			overviewMapControl:false,
			rotateControl:false,    
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: {
			  mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			}
			,styles: [
				{"stylers": [{"hue": "#2c3e50"},{"saturation": 250}]},
				{"featureType": "road","elementType": "geometry","stylers": [{"lightness": 50},{"visibility": "simplified"}]},
				{"featureType": "road","elementType": "labels","stylers": [{"visibility": "off"}]}
			]			
		};
		
		mapa = new google.maps.Map(document.getElementById('canvasMapa'), opciones);
		
		//var pos = new google.maps.LatLng(19.4339249, -99.1428964);	
		//mapa.setCenter(new google.maps.LatLng(19.4339249, -99.1428964));
		
	}	
	
	window.onload = function () {
		var chart = new CanvasJS.Chart("canvasJG", {
			title:{ text: "Tipo de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
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
			{y: 59, indexLabel: "FINANCIERAS 59"}, {y: 21,  indexLabel: "DESEMPEÑO 21" }, {y: 31,  indexLabel: "OBRA 31" }
			]
		  }   
		  ]
		});
		chart.render();

		var chart2 = new CanvasJS.Chart("canvasJD", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chart2.render();
		
		//////////////////////////////////////////////////////
		
		var chartMapa1 = new CanvasJS.Chart("canvasMapa1", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa1.render();
		
		var chartMapa2 = new CanvasJS.Chart("canvasMapa2", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "line",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa2.render();	

		var chartMapa3 = new CanvasJS.Chart("canvasMapa3", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "column",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa3.render();


		var chartMapa4 = new CanvasJS.Chart("canvasMapa4", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa4.render();	


		var chartMapa5 = new CanvasJS.Chart("canvasMapa5", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa5.render();	


		var chartMapa6 = new CanvasJS.Chart("canvasMapa6", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa6.render();


		var chartMapa7 = new CanvasJS.Chart("canvasMapa7", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa7.render();		
		

		var chartMapa8 = new CanvasJS.Chart("canvasMapa8", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa8.render();	


		var chartMapa9 = new CanvasJS.Chart("canvasMapa9", {
			title:{ text: "Avance de Auditoría", fontColor: "#2f4f4f",fontSize: 15,verticalAlign: "top", horizontalAlign: "center" },
			axisX: {labelFontSize: 10,labelFontColor: "black", tickColor: "red",tickLength: 5,tickThickness: 2},		
			animationEnabled: true,
			theme: "theme1", 
		  data: [
		  {        
			indexLabelFontSize: 10,indexLabelFontColor:"black",type: "pie",bevelEnabled: true,				
			showInLegend: false,legendMarkerColor: "gray",legendText: "{indexLabel} {y}",			
			dataPoints: [  				
			{y: 59, indexLabel: "EN PROCESO 59"}, {y: 21,  indexLabel: "POR INICIAR 21"}, {y: 21,  indexLabel: "CONCLUIDAS 10"}
			]
		  }   
		  ]
		});
		chartMapa9.render();		
		
	  };	
	
	function cambiarModo(sModo){
		var stylesActual;
		
		if (sModo=="M-AVANCE"){
			document.all.divAvances.style.display="block";
			document.all.divMapa.style.display="none";
			document.all.divMapas.style.display="none";
		}
		
		if (sModo=="M-GRAFICAS"){
			document.all.divMapas.style.display="block";
			document.all.divAvances.style.display="none";
			document.all.divMapa.style.display="none";			
		}		
		
		if (sModo=="M-REGIONES"){
			document.all.divAvances.style.display="none";
			document.all.divMapa.style.display="block";
			document.all.divMapas.style.display="none";
			
			stylesActual = [
			  {stylers: [{ hue: "#2c3e50" },{ saturation: 250 }]},
			  {featureType: "road",elementType: "geometry",stylers: [{ lightness: 50 },{ visibility: "simplified" }]},
			  {featureType: "road",elementType: "labels",stylers: [{ visibility: "off" }]}
			];		
	
			inicializar();
			
			pintarAuditoria("ASCM/0123/2014", 19.3924428,-99.1681316, "50% Avance<br><br>2 Auditor(es) en sitio:<br>Alfonso Sosa, Juan Hernandez");
			pintarAuditoria("ASCM/0123/2014", 19.3781436,-99.133083, "20% Avance<br><br>1 Auditor(es) en sitio:<br>Alfonso Hernandez");
			pintarAuditoria("ASCM/0123/2014", 19.4525486,-99.1136091, "90% Avance<br><br>3 Auditor(es) en sitio:<br>Luis Sosa, Juan Cruz, Sofía Valenzuela Rojas");
			
			pintarAuditoria("ASCM/0123/2014", 19.4672405,-99.2059982, "50% Avance<br><br>4 Auditor(es) en sitio:<br>Alejadro Lujan, Oscar Garduño, Ricardo Peña, Alfredo Valez Ríos");
			pintarAuditoria("ASCM/0123/2014  (IEDF)", 19.2903742,-99.1376439, "35% Avance<br><br>2 Auditor(es) en sitio:<br>Alejandro  Garduño, Ricardo Ríos");
			pintarAuditoria("SEDE CENTRAL", 19.2624521,-99.1164637, "AUDITORÍA SUPERIOR DE LA CIUDAD DE MÉXICO");
			
			
		//mapa = new google.maps.Map(document.getElementById('canvasMapa'), stylesActual);
		
		var styledMap = new google.maps.StyledMapType(stylesActual,{name: "Styled Map"});		
		mapa.mapTypes.set('map_style', styledMap);
		mapa.setMapTypeId('map_style');
		
		//mapa.setCenter(new google.maps.LatLng(19.4339249, -99.1428964));


		
			
			
		}		
	}
	
	
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
					<div class="col-xs-4">
						<ul class="nav navbar-nav "><li><a href="./notificaciones"><i class="fa fa-user"></i> <?php echo $_SESSION["sCampanaActal"] ?></a></li></ul>
					</div>
					<div class="col-xs-4">
						<ul class="nav navbar-nav "><li><a href="./notificaciones"><i class="fa fa-user"></i> Tienes (3) Mensajes nuevos</a></li></ul>
					</div>
					<div class="col-xs-4">
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

	<!-- Header starts -->
	<header>
		<div class="container">
			<div class="row">
				<!-- Logo section -->
				<div class="col-xs-12">
					<img src="img/logo.jpg" width="100%">
				</div>		
			</div>
		</div>
	</header><!-- Header ends -->

<!-- Main content starts -->

<div class="content">
  	<!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-dropdown"><a href="/">Navigation</a></div>
		<!--- Sidebar navigation -->
		<ul id="nav">
		  <li class="open"><a href="/"><i class="fa fa-home"></i> Inicio</a></li>
		  
		  <li class="has_sub"  id="GESTION" style="display:none;">
			<a href=""><i class="fa fa-pencil-square-o"></i> Gestión<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="GESTION-UL"></ul>
		  </li>
		  
		  <li class="has_sub"  id="PROGRAMA" style="display:none;">
			<a href=""><i class="fa fa-bars"></i> Programas<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="PROGRAMA-UL"></ul>
		  </li>

		  <li class="has_sub"  id="AUDITORIA" style="display:none;">
			<a href=""><i class="fa fa-search"></i> Auditorías<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="AUDITORIA-UL"></ul>
		  </li>
		  
		  <li class="has_sub"  id="OBSERVACIONES" style="display:none;">
			<a href=""><i class="fa fa-cogs"></i> Acciones<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="OBSERVACIONES-UL"></ul>
		  </li>
		  
		  <li class="has_sub"  id="CONFIGURACION" style="display:none;">
			<a href=""><i class="fa fa-pencil-square-o"></i> Catálogos<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="CONFIGURACION-UL"></ul>
		  </li>
		  

		  <li class="has_sub"  id="REPORTEADOR" style="display:none;">
			<a href=""><i class="fa fa-file-text-o"></i> Informes<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="REPORTEADOR-UL"></ul>
		  </li>	
		  
		  <li class="open"><a href="/cerrar"><i class="fa fa-sign-out"></i> Salir</a></li>		  
		  
		</ul>
	</div> <!-- Sidebar ends -->

  	  	<!-- Main bar -->
  	<div class="mainbar">
		<div class="col-md-12">
			<div class="widget">
				<div class="widget-head">
				  <div class="pull-left"><h3 class="pull-left"><i class="fa fa-desktop	"></i> Control de Mando</h3></div>
				  <div class="widget-icons pull-right">
					<div class="form-group">
						<div class="col-xs-12">
							<select class="form-control" name="txtTipoGestion" onchange="cambiarModo(this.value);">
								<option value="M-AVANCE">Modo Avances</option>
								<option value="M-REGIONES">Modo Regiones</option>
								<option value="M-GRAFICAS">Modo Gráficas</option>
							</select>
						</div>
					</div>							
				  </div>  
				  <div class="clearfix"></div>
				</div>             
				<div class="widget-content">
					<div  class="col-md-12" id="divMapas"  style="display:none;">
						<div class="form-group">
							<div class="col-md-3"><div  id="canvasMapa1"></div></div>
							<div class="col-md-3"><div  id="canvasMapa2"></div></div>
							<div class="col-md-3"><div  id="canvasMapa3"></div></div>
							<div class="col-md-3"><div  id="canvasMapa4"></div></div>
						</div>
						<br>
						<div class="form-group">
							<div class="col-md-3"><div  id="canvasMapa5"></div></div>
							<div class="col-md-3"><div  id="canvasMapa6"></div></div>
							<div class="col-md-3"><div  id="canvasMapa7"></div></div>
							<div class="col-md-3"><div  id="canvasMapa8"></div></div>
						</div>	
						<div class="clearfix"></div>						
					</div>
					
					<div  class="col-xs-12" id="divAvances">
						<div class="col-xs-3">
							<br>
							<div class="col-md-12">
								<div id="canvasJG" ></div>
							</div>
							<br><br>
							<div class="col-md-12">
								<div id="canvasJD" ></div>
							</div>					
						</div>

						<div class="col-xs-9">				
							<div class="widget">
								<div class="widget-head">
								  <div class="pull-left">Auditorias</div>
								  <div class="clearfix"></div>
								</div>             
								<div class="widget-content">
									<div class="table-responsive" style="height: 250px; overflow: auto; overflow-x:hidden;">
										<table class="table table-striped table-bordered table-hover table-condensed">
										  <thead>
											<tr><th>No.</th><th>Sujetos de Fiscalización</th><th>Rubros o funciones de Gasto</th><th>Tipo de Auditoría</th></tr>
										  </thead>
										  <tbody>									  
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
									<div class="clearfix"></div>
								</div>
								<div class="widget-foot">15 Auditoría(s), Promedio de Avance 48%   Ultima Actualización: 10 Marzo 2016 13:35 hrs </div>
							</div>
						</div>
					</div>

					<div class="col-xs-12" id="divMapa" style="display:none;">
						<div id="canvasMapa" ></div>
					</div>
					
					<div class="clearfix"></div>
				</div>
				<div class="widget-foot"></div>
			</div>
				</div>
		<!-- Matter ends -->
    </div>
   <!-- Mainbar ends -->
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