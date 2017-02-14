<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.png">
    <title>Sistema Integral de Auditorías</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">	
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/estilos.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<style type="text/css">		
		@media screen and (min-width: 768px) {
			#mapa_content {width:100%; height:150px;}
			#canvasJG, #canvasJD, #canvasDIP{height:140px; width:100%;}			
			#modalFlotante .modal-dialog  {width:50%;}			
		}		
		
		
		.panel-default > .panel-heading-custom {background: #996666; color: #fff; opacity: 0.9;}
		
		
		
			
		.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th { background-color: #f1f1f1; }		
		.table > thead > tr > th { background-color: #f0f0f0; font-weight: bold; text-align: center;}		
				
	</style>
	
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>          
		  <a href="/"><img src="img/logo-top.png"></a>		  
        </div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-left">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> CUENTA PÚBLICA 2014</a>
				</li>	
			</ul>
		
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> C. Jose Aurelio Cota Zazueta<span class="caret"></span></a>
				  <ul class="dropdown-menu">
					<li><a href="/perfil"><i class="fa fa-user"></i> Perfil</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="/cerrar"><i class="fa fa-sign-out"></i>Salir</a></li>
				  </ul>
				</li>
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Usted tiene <span class="badge">3</span> Mensaje(s).</a>
				</li>				
			</ul>
			
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>
    <div class="container-fluid">		
      <div class="row">		
        <div class="col-sm-3 col-md-2 sidebar menu">
			<div class="bs-example">
				<div class="panel-group" id="accordion">
				
					<div class="panel panel-default"  data-parent="#accordion"  href="/">
						<div class="panel-heading panel-heading-custom">
							<h4 class="panel-title"><i class="fa fa-home"></i> Inicio</h4>
							<div class="clearfix"></div>
						</div>
					</div>				
				
					<div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						<div class="panel-heading " >
							<h4 class="panel-title">
								<i class="fa fa-bars"></i> Programas
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse">
							<div class="panel-body">
								<a href="/programas"><i class="fa fa-check-circle"></i> Programa Gral. de Auditorias</a><br>
								<a href="/cuentas"><i class="fa fa-check-circle"></i> Cuenta Pública</a><br>
							</div>
						</div>
					</div>
					<div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-search"></i> Auditorias</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
							<div class="panel-body">
								<a href="./auditorias"><i class="fa fa-check-circle"></i> Auditorías</a><br>
								<a href="./papeles"><i class="fa fa-check-circle"></i> Papeles de Trabajo</a><br>
								<a href="./avances"><i class="fa fa-check-circle"></i> Avances por Actividad</a><br>
								<a href="./acopio"><i class="fa fa-check-circle"></i> Acopio de Información</a><br>
								<a href="./auditores"><i class="fa fa-check-circle"></i> Auditores</a><br>
							</div>						
						</div>
					</div>
					<div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-cogs"></i> Acciones</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse">
							<div class="panel-body">
								<a href="./acciones"><i class="fa fa-check-circle"></i> Promoción de Acciones</a><br>
							</div>
						</div>
					</div>
					<div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-pencil-square-o"></i> Catalogos</h4>
						</div>
						<div id="collapse4" class="panel-collapse collapse">
							<div class="panel-body">
								<a href="./usuarios"><i class="fa fa-check-circle"></i> Usuario</a><br>
								<a href="./areas"><i class="fa fa-check-circle"></i> Areas</a><br>
								<a href="./roles"><i class="fa fa-check-circle"></i> Roles</a><br>
								<a href="./perfil"><i class="fa fa-check-circle"></i> Perfil</a><br>
							</div>
						</div>
					</div>					
					<div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="#collapse5">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-file-text-o"></i> Informes</h4>
						</div>
						<div id="collapse5" class="panel-collapse collapse">
							<div class="panel-body">
								<a href="./reportes"><i class="fa fa-check-circle"></i> Reporteador</a><br>
							</div>
						</div>
					</div>	

					<div class="panel panel-default"  data-parent="#accordion"  href="/cerrar">
						<div class="panel-heading  panel-heading-custom">
							<h4 class="panel-title"><i class="fa fa-sign-out"></i> Salir</h4>
						</div>
					</div>	
				</div>				
			</div>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

			<div class="panel panel">
				<div class="panel-heading " >
					<h4 class="panel-title"><b><i class="fa fa-signal"></i> Estadística</b></h4>					
					
					<hr>
					<div class="clearfix"></div>
					
				</div>
				<div class="panel-body" style="padding:0px;">
		
				</div>
			</div>		
			<br>
		
			<div class="panel panel-default">
				<div class="panel-heading " >
					<div class="col-sm-6"><h4 class="panel-title"><b><i class="fa fa-bars"></i> Avances x Auditoria</b></h4></div>
					<div class="col-sm-6"><h4 class="panel-title pull-right"><i class="	fa fa-close"></i></h4></div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body" style="padding:0px;">
					<table class="table table-condensed table-hover table-bordered table-striped">							  
					  <thead>
						<tr>
						  <th>Cve.</th>
						  <th>Sujeto</th>
						  <th>Objeto</th>
						  <th>% Avance</th>
						  <th>Observaciones</th>
						</tr>
					  </thead>
					  <tbody>
						<tr><td>ASCM-2014-001</td><td>DELEGACIÓN TLALPAN</td><td>PROGRAMA SOCIAL NO. 3454</td><td>30</td><td>Ninguna</td></tr>
						<tr><td>ASCM-2014-002</td><td>DELEGACIÓN TLALPAN</td><td>PROGRAMA SOCIAL NO. 3454</td><td>30</td><td>Ninguna</td></tr>
						<tr><td>ASCM-2014-003</td><td>DELEGACIÓN TLALPAN</td><td>PROGRAMA SOCIAL NO. 3454</td><td>30</td><td>Ninguna</td></tr>
						<tr><td>ASCM-2014-004</td><td>DELEGACIÓN TLALPAN</td><td>PROGRAMA SOCIAL NO. 3454</td><td>30</td><td>Ninguna</td></tr>
						<tr><td>ASCM-2014-005</td><td>DELEGACIÓN TLALPAN</td><td>PROGRAMA SOCIAL NO. 3454</td><td>30</td><td>Ninguna</td></tr>								
					  </tbody>
					</table>
				</div>
			</div>		
		  
		  



        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>