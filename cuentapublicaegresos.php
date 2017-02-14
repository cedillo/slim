<!DOCTYPE html>

<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />  
  	<meta charset="utf-8">
	<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js?ver=3.1.2"></script> 
	<script type="text/javascript" src="js/genericas.js"></script>

  	<style type="text/css">		
		@media screen and (min-width: 768px) {
			#mapa_content {width:100%; height:150px;}
			#canvasJG, #canvasJD, #canvasDIP{height:140px; width:100%;}			
			#modalFlotante .modal-dialog  {width:52%;}
			.filtros{margin: 8px 0px 8px 0px ! important;}
			label {text-align:right;}
			#modalCuentaGeneral {width: 100%;}
		}
	</style>
  
  <script type="text/javascript"> 
	var mapa;
	var nZoom=10;
	var cb = [];

	function limpiarCampos(){
		//document.all.txtTipo.selectedIndex= 0;
		document.all.txtSujeto.selectedIndex = 0;
		document.all.txtTipoingre.selectedIndex = 0;
		document.all.txtFinalidad.selectedIndex = 0;
		document.all.txtFuncion.selectedIndex = 0;
		document.all.txtSubfuncion.selectedIndex =0;
		document.all.txtActividades.selectedIndex = 0;
		document.all.txtCapitulo.selectedIndex = 0;
		document.all.txtPartida.selectedIndex = 0;
	}

	
	function limpiarCamposujeto(){
		
		document.all.txtFinalidad.selectedIndex = 0;
		document.all.txtFuncion.selectedIndex = 0;
		document.all.txtSubfuncion.selectedIndex =0;
		document.all.txtActividades.selectedIndex = 0;
		document.all.txtCapitulo.selectedIndex = 0;
		document.all.txtPartida.selectedIndex = 0;
	}	

	//function limpiartipo(){document.all.txtTipo.selectedIndex= 0;}
	
	function limpiaringre(){document.all.txtTipoingre.selectedIndex= 0;}

	
	function recuperarCuentaingre(lista,tbl){
				
		$.ajax({
			type: 'GET', url: '/'+ lista ,
			success: function(response) {
				$('#modalCuentaGeneral').modal('show');
				var jsonData = JSON.parse(response);
				document.all.tabINGRESOS.style.display='inline';
				document.all.tabCuentaEng.style.display='inline';
				document.all.exporta.style.display='inline';
				document.all.exporta_egresos.style.display='none';
				document.all.TabEGRESOSdeta.style.display='none';
				document.all.Tipotipo.style.display='none';		
				//Vacia la lista
				tbl.innerHTML="";

				//Limpia array
				//lstEmpleados = [];
				//Agregar renglones
				var renglon, columna; 	
				
				for (var i = 0; i < jsonData.datos.length; i++) {

					var dato = jsonData.datos[i];					
						if(dato.sbnombre==null){
							var subfuncion = '-2222';
						}else{
							var subfuncion = dato.sbnombre;
						}
						if(dato.funombre==null){var funcion = '-';}else{var funcion = dato.funombre;}

					renglon=document.createElement("TR");			
					renglon.innerHTML="<td>" + dato.idCuenta + "</td><td>" + dato.nombre + "</td><td>" + funcion + "</td><td>" + subfuncion + "</td><td>" + dato.actividad + "</td><td>" + dato.capitulo + "</td><td>" + dato.partida + "</td><td>" + dato.finalidad + "</td><td>" + dato.original + "</td><td>" + dato.modificado + "</td><td>" + dato.ejercido + "</td><td>" + dato.pagado + "</td><td>" + dato.pendiente + "</td>";

					tbl.appendChild(renglon);					
				}				
			},
			error: function(xhr, textStatus, error){
				alert('function recuperarTabla ->statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
			}			
		});	
	}


	/*function recuperarTablaIngresos(lista, valor,cuenta,tbl){

		var unidad = valor;
		var sector = unidad.substring(2, 0);
		var subsector = unidad.substring(2, 4);
		var uni = unidad.substring(4,6);
		var vista;

		$.ajax({
			type: 'GET', url: '/'+ lista + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ cuenta,
			success: function(response) {
				var jsonData = JSON.parse(response);
				document.all.tabINGRESOS.style.display='inline';
				document.all.exporta.style.display='inline';
				document.all.exporta_egresos.style.display='none';
				document.all.TabEGRESOSdeta.style.display='none';
				document.all.Tipotipo.style.display='none';		
				//Vacia la lista
				tbl.innerHTML="";

				//Limpia array
				//lstEmpleados = [];
				//Agregar renglones
				var renglon, columna; 	
				
				for (var i = 0; i < jsonData.datos.length; i++) {

					var dato = jsonData.datos[i];					
						if(dato.sbnombre==null){var subfuncion = '-555';}else{var subfuncion = dato.sbnombre;}
						if(dato.funombre==null){var funcion = '-';}else{var funcion = dato.funombre;}

					renglon=document.createElement("TR");			
					renglon.innerHTML="<td>" + dato.idCuenta + "</td><td>" + dato.nombre + "</td><td>" + funcion + "</td><td>" + subfuncion + "</td><td>" + dato.actividad + "</td><td>" + dato.capitulo + "</td><td>" + dato.partida + "</td><td>" + dato.finalidad + "</td><td>" + dato.original + "</td><td>" + dato.modificado + "</td><td>" + dato.ejercido + "</td><td>" + dato.pagado + "</td><td>" + dato.pendiente + "</td>";

					tbl.appendChild(renglon);					
				}				
			},
			error: function(xhr, textStatus, error){
				alert('function recuperarTabla ->statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
			}			
		});	
	}*/





	/*function recuperarTablaIngresosDE(lista, valor,cuenta,tbl){
		var unidad = valor;
		var sector = unidad.substring(2, 0);
		var subsector = unidad.substring(2, 4);
		var uni = unidad.substring(4,6);
		$.ajax({
			type: 'GET', url: '/'+ lista + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ cuenta,
			success: function(response) {
				var jsonData = JSON.parse(response);
				document.all.tabINGRESOS.style.display='none';
				document.all.exporta.style.display='none';
				document.all.exporta_egresos.style.display='inline';
				document.all.TabEGRESOSdeta.style.display='inline';
				document.all.Tipotipo.style.display='inline';
				//Vacia la lista
				tbl.innerHTML="";

				//Limpia array
				//lstEmpleados = [];
				//Agregar renglones
				var renglon, columna; 	
				
				for (var i = 0; i < jsonData.datos.length; i++) {
					var dato = jsonData.datos[i];					
						
					renglon=document.createElement("TR");			
					renglon.innerHTML="<td>" + dato.cuenta + "</td><td>" + dato.nombre + "</td><td>" + dato.estimado + "</td><td>" + dato.registrado + "</td><td>" + dato.importe + "</td>";

					tbl.appendChild(renglon);					
				}				
			},
			error: function(xhr, textStatus, error){
				alert('function recuperarTabla ->statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
			}			
		});	
	}


	function recuperarTablaEgresos(lista,cuenta,tbl){
		$.ajax({
			type: 'GET', url: '/'+ lista + '/'+ cuenta,
			success: function(response) {
				var jsonData = JSON.parse(response);
				document.all.TabEGRESOS.style.display='inline';
				document.all.exporta_egresos.style.display='inline';			
				document.all.exporta.style.display='none';
				document.all.TabEGRESOSdeta.style.display='none';
				document.all.tabINGRESOS.style.display='none';
				//Vacia la lista
				tbl.innerHTML="";
				//Limpia array
				//lstEmpleados = [];
				//Agregar renglones
				var renglon, columna;
				
				for (var i = 0; i < jsonData.datos.length; i++) {
					var dato = jsonData.datos[i];	
					var original = '$'+ dato.original;
					var recaudado = '$'+ dato.recaudado;					
					
					renglon=document.createElement("TR");			
					renglon.innerHTML="<td>" + dato.idCuenta + "</td><td>" + dato.origen + "</td><td>" + dato.tipo + "</td><td style='text-align:rigth;'>" + dato.clave + "</td><td>" + dato.nivel + "</td><td>" + dato.nombre + "</td><td>" + original + "</td><td>" + recaudado + "</td>";

					tbl.appendChild(renglon);					
				}				
			},
			error: function(xhr, textStatus, error){
				alert('function recuperarTabla ->statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
			}			
		});	
	}*/



	function recutipo(id){
		if(id==''){
			document.getElementById('tabINGRESOS').style.display='none';
			document.all.txtFinalidad.selectedIndex = 0;
			document.all.txtFuncion.selectedIndex = 0;
			document.all.txtSubfuncion.selectedIndex =0;
			document.all.txtActividades.selectedIndex = 0;
			document.all.txtCapitulo.selectedIndex = 0;
			document.all.txtPartida.selectedIndex = 0;			
		
		}else{
			document.getElementById('tabINGRESOS').style.display='none';
			document.all.txtFinalidad.selectedIndex = 0;
			document.all.txtFuncion.selectedIndex = 0;
			document.all.txtSubfuncion.selectedIndex =0;
			document.all.txtActividades.selectedIndex = 0;
			document.all.txtCapitulo.selectedIndex = 0;
			document.all.txtPartida.selectedIndex = 0;
		}
	}


	function recuperar(id,valor){
		if(id==''){
			document.all.tabINGRESOS.style.display='none';
			document.all.exporta.style.display='none';
			document.all.exporta_egresos.style.display='none';
			document.all.exporta.style.display='none';
		}else{

			recuperarTablaIngresos('tblCuentaByUnidad',id,valor,document.all.tablaCuentaIN);
			limpiarCamposujeto();
		}
	}

	function selectsujetoTipo(valor,cuenta,sujeto){
		if(valor==''){
			document.all.tabINGRESOS.style.display='none';
			document.all.Tipo.style.display='inline';
			document.all.tipoingre.style.display='none';
			document.all.TabEGRESOS.style.display='none';
			document.all.TabEGRESOSdeta.style.display='none';
			document.all.exporta_egresos.style.display='none';
			document.all.exporta.style.display='none';
			document.all.tabINGRESOS.style.display='none';			
		}else{
			if(valor=='GENERAL'){
				var CP = document.getElementById('CuenPu').value;
				recuperarTablaEgresos('tblCuentaByEgresos',CP,document.all.tablaCuentaEG)
			}else{
				document.all.TabEGRESOSdeta.style.display='inline';
				document.all.TabEGRESOS.style.display='none';
				document.all.exporta_egresos.style.display='inline';
				recuperarTablaIngresosDE('tblCuentaByIngresosDE',sujeto,cuenta,document.all.tablaCuentaEGDE);
			}
		}
	}

	function ocultarcajas(funcion,valor){
	//	alert("funcion: " + funcion + " valor: " + valor);
	//	if(funcion=='subfunciones' && valor>0){document.all.txtSubfuncion.selectedIndex=0;document.all.txtActividades.selectedIndex=0;}
	//	if(funcion=='funciones' && valor>0){document.all.txtFuncion.selectedIndex=0;document.all.txtSubfuncion.selectedIndex=0;document.all.txtActividades.selectedIndex=0;}
	}



	function recuperarreporte(lista, valor, cmb){
		var CP = document.getElementById('CuenPu').value;
		var sujeto = document.getElementById('Sujet').value;
		var finalidad = document.getElementById('finalidad').value;
		var funciones = document.getElementById('funcion').value;
		var sector = sujeto.substring(2, 0);
		var subsector = sujeto.substring(2, 4);
		var uni = sujeto.substring(4,6);
		var listacombo;
		//alert(sujeto + "  sector:  " + sector + " subsector:  " + subsector + " unidad: " + uni);
		if(sujeto !== ''){

			if(document.all.txtFinalidad.selectedIndex>0){
				listacombo= '/' + lista + '/' + sector + '/' + subsector + '/' + uni + '/' + CP + '/' + valor;
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			
			}else{
				document.all.txtFuncion.selectedIndex=0;
				document.all.txtSubfuncion.selectedIndex=0;
				document.all.txtActividades.selectedIndex=0;
			}

			if(document.all.txtFuncion.selectedIndex>0){
				listacombo= '/' + lista + '/' + sector + '/' + subsector + '/' + uni + '/' + CP + '/' + valor + '/' + finalidad; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtSubfuncion.selectedIndex=0;
				document.all.txtActividades.selectedIndex=0;	
			}

			if(document.all.txtSubfuncion.selectedIndex>0){
				listacombo= '/' + lista + '/' + sector + '/' + subsector + '/' + uni + '/' + CP + '/' + funciones + '/' + finalidad + '/' + valor; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtActividades.selectedIndex=0;
			}

			if(document.all.txtActividades.selectedIndex<0){
				document.all.txtActividades.selectedIndex=0;	
			}

			if(document.all.txtCapitulo.selectedIndex>0){
				listacombo= '/' + lista + '/' + sector + '/' + subsector + '/' + uni + '/' + CP + '/' + valor; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtPartida.selectedIndex=0;
			}

		}else{
			if(document.all.txtFinalidad.selectedIndex>0){
				listacombo= '/' + lista + '/' + CP + '/' + valor; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtFuncion.selectedIndex=0;
				document.all.txtSubfuncion.selectedIndex=0;
				document.all.txtActividades.selectedIndex=0;
			}

			if(document.all.txtFuncion.selectedIndex>0){
				listacombo= '/' + lista + '/' + CP + '/' + valor + '/' + finalidad; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtSubfuncion.selectedIndex=0;
				document.all.txtActividades.selectedIndex=0;	
			}

			if(document.all.txtSubfuncion.selectedIndex>0){
				listacombo= '/' + lista + '/' + CP + '/' + funciones + '/' + finalidad + '/' + valor; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtActividades.selectedIndex=0;
			}

			if(document.all.txtActividades.selectedIndex<0){
				document.all.txtActividades.selectedIndex=0;	
			}

			if(document.all.txtCapitulo.selectedIndex>0){
				listacombo= '/' + lista + '/' + CP + '/' + valor; 
				//alert("listacombo:  " + listacombo + " valor:  " + valor);
			}else{
				document.all.txtPartida.selectedIndex=0;
			}
		}

		$.ajax({
			type: 'GET', url: listacombo ,
			success: function(response) {
				var jsonData = JSON.parse(response);
				//Vacia la lista
				while (cmb.length>1){
					cmb.remove(cmb.length-1);
				}
				//Agregar elementos
				for (var i = 0; i < jsonData.datos.length; i++) {
					var dato = jsonData.datos[i];
					var option = document.createElement("option");
					option.text = dato.texto;
					option.value = dato.id;
					cmb.add(option, i+1);
				}				
			},
			/*error: function(xhr, textStatus, error){
				alert("ERROR EN: function recuperarListaLigada(lista, valor, cmb)" + " Donde lista=" + lista );
			}*/
		});
	}







	function selectsujeto(valor,sujeto){
		var CP = document.getElementById('CuenPu').value;
		if(valor=='INGRESOS'){
			$.ajax({
				type: 'GET', url: '/valsujeto/' + sujeto + '/' + CP,
				success: function(response) {
					var obj = JSON.parse(response);
					var res = obj.verdadero;
					if(res=='true'){
						limpiaringre();
						document.all.Tipotipo.style.display='inline';
						document.all.TabEGRESOS.style.display='none';
						document.all.tabINGRESOS.style.display='none';
						document.all.exporta.style.display='none';
					}else{
						var CP = document.getElementById('CuenPu').value;
						recuperarTablaEgresos('tblCuentaByEgresos',CP,document.all.tablaCuentaEG);
						document.getElementById('tipoingre').value = 'GENERAL';
					}
				},
				error: function(xhr, textStatus, error){
					alert('calporcentaje: statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
				}			
			});		
		}else{
			if(valor=='EGRESOS'){
				var id = document.getElementById('Sujet').value;
				document.all.tabINGRESOS.style.display='none';
				document.all.sujet.style.display='inline';
				document.all.TabEGRESOS.style.display='none';
				document.all.exporta_egresos.style.display='none';
				recuperar(id,CP);
			}else{
				limpiaringre();
				document.all.tabINGRESOS.style.display='none';
				document.all.Tipotipo.style.display='none';
				document.all.TabEGRESOS.style.display='none';
				document.all.exporta_egresos.style.display='none';
				document.all.exporta.style.display='none';
			}
		}
	}


	function recuperacuenta(id){
		limpiarCampos();
		//limpiartipo();
		//document.all.btnCuentapublica.style.display='inline';
		document.all.btnMostrarconsul.style.display='inline';
		recuperarListaLigada('lstSujeto', id, document.all.txtSujeto);
		document.all.linea.style.display='none';
		document.all.fina.style.display='none';
		document.all.capi.style.display='none';

		document.all.func.style.display='none';
		document.all.subfun.style.display='none';
		
		document.all.acti.style.display='none';
		document.all.part.style.display='none';
		document.all.txtCP.value = id;
		document.all.listacuenta.style.display='none';
		document.all.Cuentavisual.style.display='inline';
		document.all.btnRegresar.style.display='inline';
		document.all.sujet.style.display='inline';
		document.all.exporta_egresos.style.display='none';
		document.all.exporta.style.display='none';
		//document.all.Tipo.style.display='none';
		document.all.Tipotipo.style.display='none';
		document.all.TabEGRESOSdeta.style.display='none';
		document.all.tabINGRESOS.style.display='none';
		document.all.TabEGRESOS.style.display='none';


		document.all.fina.style.display='inline';
		document.all.func.style.display='inline';

		document.all.subfun.style.display='inline';
		document.all.acti.style.display='inline';

		document.all.capi.style.display='inline';
		document.all.part.style.display='inline';
		document.all.linea.style.display='inline';
		document.all.Tipotipo.style.display='none';
		document.all.tabINGRESOS.style.display='none';
		document.all.TabEGRESOSdeta.style.display='none';
		document.all.TabEGRESOS.style.display='none';		




	}

	
	var nUsr='<?php echo $_SESSION["idUsuario"];?>';		
	var nCampana='<?php echo $_SESSION["idCuentaActual"];?>';	
	  
	$(document).ready(function(){
		recuperarLista('lstFinalidadesByCuenta', document.all.txtFinalidad); 
		recuperarLista('capitulo', document.all.txtCapitulo)

		$(".botonExcel").click(function(event) {
			$("#datos_a_enviar").val( $("<table>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
			$("#FormularioExportacion").submit();
		});
		
		$(".botonExcelexport").click(function(event) {
			var btn = document.getElementById('tipoingre').value;
			if(btn=='GENERAL'){
				$("#datos_a_enviar").val( $("<table>").append( $("#Exportar_a_Egresos").eq(0).clone()).html());
				$("#FormularioExportacion").submit();
			}else{
				$("#datos_a_enviar").val( $("<table>").append( $("#Exportar_a_Egresos_Deta").eq(0).clone()).html());
				$("#FormularioExportacion").submit();
			}
		});		
	
		if(nUsr!="" && nCampana!=""){
			cargarMenu( nCampana);			
		}else{
			if(nCampana=="")alert("Debe establecer una CUENTA PÚBLICA como PREDETERMINADA. Por favor consulte con su administrador del sistema.");
		}
		
		$( "#btnConsultar" ).click(function() {
			var cuenta = document.getElementById('cuentaradio').value;
			recuperacuenta(cuenta);
		});

		$( "#btnRegresar" ).click(function() {
			document.all.listacuenta.style.display='inline';
			document.all.Cuentavisual.style.display='none';
			document.all.btnRegresar.style.display='none';
			document.all.btnMostrarconsul.style.display='none';
			document.all.tabINGRESOS.style.display='none';
			document.all.sujet.style.display='none';
			document.all.TabEGRESOS.style.display='none';		
			document.all.exporta_egresos.style.display='none';
			document.all.exporta.style.display='none';	
			document.all.btnCuentapublica.style.display='none';

		});	

		$( "#btnMostrarconsul" ).click(function() {
			var CP = document.getElementById('CuenPu').value;
			var unidad = document.getElementById('Sujet').value;
			var sector = unidad.substring(2, 0);
			var subsector = unidad.substring(2, 4);
			var uni = unidad.substring(4,6);

			var finalidad = document.getElementById('finalidad').value;
			var funciones = document.getElementById('funcion').value;
			var subfunciones = document.getElementById('subfuncion').value;
			var actividad = document.getElementById('actividades').value;
			var capitulo = document.getElementById('capitulo').value;
			var partida = document.getElementById('partida').value;
			var vista;
			//alert(" -- " + finalidad +" -- " + funciones +" -- " + subfunciones +" -- " + actividad +" -- " + capitulo +" -- " + partida);
			


			if(unidad !== ''){
				if(funciones=='' && subfunciones=='' && actividad=='' && capitulo=='' && partida=='' && finalidad==''){
					//alert("unidad:  "  + unidad);
					vista = 'tblCuentaByUnidad' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP;
					tbl = tablaCuentaIN;
					tbl2 = filtro;
					var filt = 'Filtrado por Unidad.';
				}else{
		 			if(finalidad !=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo=='' && partida==''){
		 				//alert("finalidad: " + finalidad);
		 				vista = 'finalidad' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad;
						tbl = tablaCuentaIN;
						tbl2 = filtro;
						var filt = 'Filtrado por Finalidad.';
					}else{
			 			if(finalidad=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
			 				//alert("Capitulo: " + capitulo);
			 				vista = 'capitulo' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + capitulo;
							tbl = tablaCuentaIN;	
							tbl2 = filtro;
							var filt = 'Filtrado por Capítulo.';
			 			}else{
				 			if(finalidad !=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
				 				//alert("finalidad: " + finalidad + " Capitulo: " + capitulo );
				 				vista = 'finacapi' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + capitulo;
								tbl = tablaCuentaIN;
								tbl2 = filtro;
								var filt = 'Filtrado por Finalidad y Capítulo.';
				 			}else{
					 			if(finalidad !=='' && funciones !=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
					 				//alert("finalidad: " + finalidad + "  funciones: " + funciones + " Capitulo: " + capitulo);
					 				vista = 'finfuncap' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + capitulo;
									tbl = tablaCuentaIN;
									tbl2 = filtro;
									var filt = 'Filtrado por Finalidad, Función y Capítulo.';
					 			}else{
						 			if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad=='' && capitulo !=='' && partida==''){
						 				//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones  + " Capitulo: " + capitulo);
						 				vista = 'finfunsubcap' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + capitulo;
										tbl = tablaCuentaIN;
										tbl2 = filtro;
										var filt = 'Filtrado por Finalidad,Función,Subfunción,Capítulo.';
						 			}else{
							 			if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo !=='' && partida==''){
							 				//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + " actividad: " + actividad + " Capitulo: " + capitulo);
							 				vista = 'finfunsubactcap' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad + '/' + capitulo;
											tbl = tablaCuentaIN;
											tbl2 = filtro;
											var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad,Capítulo.';
							 			}else{
							 				if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo !=='' && partida !==''){
							 					//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + " actividad: " + actividad + " Capitulo: " + capitulo + " partida: " + partida);
							 					vista = 'finfunsubactcappar' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad + '/' + capitulo + '/' + partida;
												tbl = tablaCuentaIN;
												tbl2 = filtro;
												var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad,Capítulo,Partida.';
							 				}else{
							 					if(finalidad !=='' && funciones !=='' && subfunciones =='' && actividad =='' && capitulo =='' && partida ==''){
							 						//alert("finalidad: " + finalidad + "  funciones: " + funciones);
							 						vista = 'finfun' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones;
													tbl = tablaCuentaIN;
													tbl2 = filtro;
													var filt = 'Filtrado por Finalidad,Función.';
							 					}else{
							 						if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad =='' && capitulo =='' && partida ==''){
								 						//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones);
								 						vista = 'finfunsub' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones;
														tbl = tablaCuentaIN;
														tbl2 = filtro;
														var filt = 'Filtrado por Finalidad,Función,Subfunción.';
							 						}else{
							 							if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo =='' && partida ==''){
									 						//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + "  actividad: " + actividad);
									 						vista = 'finfunsubact' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad;
															tbl = tablaCuentaIN;
															tbl2 = filtro;
															var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad';
									 					}else{
									 						if(finalidad !=='' && funciones =='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
										 						//alert("finalidad: " + finalidad + "  capitulo: " + capitulo + "  partida: " + partida);
										 						vista = 'fincappar' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + capitulo + '/' + partida;
																tbl = tablaCuentaIN;
																tbl2 = filtro;
																var filt = 'Filtrado por Finalidad,Capítulo,Partida';
										 					}else{
										 						if(finalidad !=='' && funciones !=='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
											 						//alert("finalidad: " + finalidad + "funciones: " + funciones + "  capitulo: " + capitulo + "  partida: " + partida);
											 						vista = 'finfuncappar' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + capitulo + '/' + partida;
																	tbl = tablaCuentaIN;
																	tbl2 = filtro;
																	var filt = 'Filtrado por Finalidad,Función,Capítulo,Partida';
											 					}else{
											 						if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad =='' && capitulo !=='' && partida !==''){
												 						//alert("finalidad: " + finalidad + "funciones: " + funciones + "subfunciones: " + subfunciones + "  capitulo: " + capitulo + "  partida: " + partida);
												 						vista = 'finfunsubcappar' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + capitulo + '/' + partida;
																		tbl = tablaCuentaIN;
																		tbl2 = filtro;
																		var filt = 'Filtrado por Finalidad,Función,Subfunción,Capítulo,Partida';
												 					}else{
												 						if(finalidad =='' && funciones =='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
													 						//alert("capitulo: " + capitulo + "  partida: " + partida);
													 						vista = 'cappar' + '/'+ sector + '/'+ subsector + '/'+ uni + '/'+ CP + '/' + capitulo + '/' + partida;
																			tbl = tablaCuentaIN;
																			tbl2 = filtro;
																			var filt = 'Filtrado por Capítulo,Partida';
													 					}	
												 					}	
											 					}	
										 					}
									 					}	
							 						}
							 					}
							 				}	
							 			}
						 				
						 			}
					 				
					 			}
				 				
				 			}
			 				
			 			}
		 				
		 			}
	 			}
			}else{
				if(funciones=='' && subfunciones=='' && actividad=='' && capitulo=='' && partida=='' && finalidad=='' && unidad==''){
					//alert("unidad:  "  + unidad);
					vista = 'tblaCuentapublica' + '/'+ CP;
					tbl = tablaCuentaIN;
					
	 			}else{
		 			if(finalidad !=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo=='' && partida==''){
		 				//alert("finalidad: " + finalidad);
		 				vista = 'finalidad' + '/'+ CP + '/' + finalidad;
						tbl = tablaCuentaIN;
						tbl2 = filtro;
						var filt = 'Filtrado por Finalidad.';
		 			}else{
			 			if(finalidad=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
			 			//	alert("Capitulo: " + capitulo);
			 				vista = 'capitulo' + '/'+ CP + '/' + capitulo;
							tbl = tablaCuentaIN;
							tbl2 = filtro;
							var filt = 'Filtrado por Capítulo.';	
			 			}else{
				 			if(finalidad !=='' && funciones=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
				 		//		alert("finalidad: " + finalidad + " Capitulo: " + capitulo );
				 				vista = 'finacapi' + '/'+ CP + '/' + finalidad + '/' + capitulo;
								tbl = tablaCuentaIN;
								tbl2 = filtro;
								var filt = 'Filtrado por Finalidad y Capítulo.';	
				 			}else{
					 			if(finalidad !=='' && funciones !=='' && subfunciones=='' && actividad=='' && capitulo !=='' && partida==''){
					 	//			alert("finalidad: " + finalidad + "  funciones: " + funciones + " Capitulo: " + capitulo);
					 				vista = 'finfuncap' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + capitulo;
									tbl = tablaCuentaIN;
									tbl2 = filtro;
									var filt = 'Filtrado por Finalidad, Función y Capítulo.';	
					 			}else{
						 			if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad=='' && capitulo !=='' && partida==''){
						 //				alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones  + " Capitulo: " + capitulo);
						 				vista = 'finfunsubcap' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + capitulo;
										tbl = tablaCuentaIN;
										tbl2 = filtro;
										var filt = 'Filtrado por Finalidad,Función,Subfunción,Capítulo.';
						 			}else{
							 			if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo !=='' && partida==''){
							 				//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + " actividad: " + actividad + " Capitulo: " + capitulo);
							 				vista = 'finfunsubactcap' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad + '/' + capitulo;
											tbl = tablaCuentaIN;
											tbl2 = filtro;
											var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad,Capítulo.';
							 			}else{
							 				if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo !=='' && partida !==''){
							 					//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + " actividad: " + actividad + " Capitulo: " + capitulo + " partida: " + partida);
							 					vista = 'finfunsubactcappar' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad + '/' + capitulo + '/' + partida;
												tbl = tablaCuentaIN;
												tbl2 = filtro;
												var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad,Capítulo,Partida.';
							 				}else{
							 					if(finalidad !=='' && funciones !=='' && subfunciones =='' && actividad =='' && capitulo =='' && partida ==''){
							 						//alert("finalidad: " + finalidad + "  funciones: " + funciones);
							 						vista = 'finfun' + '/'+ CP + '/' + finalidad + '/' + funciones;
													tbl = tablaCuentaIN;
													tbl2 = filtro;
													var filt = 'Filtrado por Finalidad,Función.';
							 					}else{
							 						if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad =='' && capitulo =='' && partida ==''){
								 						//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones);
								 						vista = 'finfunsub' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones;
														tbl = tablaCuentaIN;
														tbl2 = filtro;
														var filt = 'Filtrado por Finalidad,Función,Subfunción.';
							 						}else{
							 							if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad !=='' && capitulo =='' && partida ==''){
									 						//alert("finalidad: " + finalidad + "  funciones: " + funciones + "  subfunciones: " + subfunciones + "  actividad: " + actividad);
									 						vista = 'finfunsubact' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + actividad;
															tbl = tablaCuentaIN;
															tbl2 = filtro;
															var filt = 'Filtrado por Finalidad,Función,Subfunción,Actividad';
									 					}else{
									 						if(finalidad !=='' && funciones =='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
										 						//alert("finalidad: " + finalidad + "  capitulo: " + capitulo + "  partida: " + partida);
										 						vista = 'fincappar' + '/'+ CP + '/' + finalidad + '/' + capitulo + '/' + partida;
																tbl = tablaCuentaIN;
																tbl2 = filtro;
																var filt = 'Filtrado por Finalidad,Capítulo,Partida';
										 					}else{
										 						if(finalidad !=='' && funciones !=='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
											 						//alert("finalidad: " + finalidad + "funciones: " + funciones + "  capitulo: " + capitulo + "  partida: " + partida);
											 						vista = 'finfuncappar' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + capitulo + '/' + partida;
																	tbl = tablaCuentaIN;
																	tbl2 = filtro;
																	var filt = 'Filtrado por Finalidad,Función,Capítulo,Partida';
											 					}else{
											 						if(finalidad !=='' && funciones !=='' && subfunciones !=='' && actividad =='' && capitulo !=='' && partida !==''){
												 						//alert("finalidad: " + finalidad + "funciones: " + funciones + "subfunciones: " + subfunciones + "  capitulo: " + capitulo + "  partida: " + partida);
												 						vista = 'finfunsubcappar' + '/'+ CP + '/' + finalidad + '/' + funciones + '/' + subfunciones + '/' + capitulo + '/' + partida;
																		tbl = tablaCuentaIN;
																		tbl2 = filtro;
																		var filt = 'Filtrado por Finalidad,Función,Subfunción,Capítulo,Partida';
												 					}else{
												 						if(finalidad =='' && funciones =='' && subfunciones =='' && actividad =='' && capitulo !=='' && partida !==''){
													 						//alert("capitulo: " + capitulo + "  partida: " + partida);
													 						vista = 'cappar' + '/'+ CP + '/' + capitulo + '/' + partida;
																			tbl = tablaCuentaIN;
																			tbl2 = filtro;
																			var filt = 'Filtrado por Capítulo,Partida';
													 					}	
												 					}	
											 					}	
										 					}
									 					}	
							 						}
							 					}
							 				}	
							 			}
						 				
						 			}
					 				
					 			}
				 				
				 			}
			 				
			 			}
		 				
		 			}
	 			}
			}

			$.ajax({
				type: 'GET', url: '/'+ vista ,
				success: function(response) {
					var jsonData = JSON.parse(response);
					document.all.tabINGRESOS.style.display='inline';
					document.all.exporta.style.display='inline';
					document.all.exporta_egresos.style.display='none';
					document.all.TabEGRESOSdeta.style.display='none';
					document.all.Tipotipo.style.display='none';	
					var renglon2;
					//var tbl2,reng;
					//tbl2.innerHTML = "";

					//alert(filt);
					tbl2 = filtro;
					document.getElementById('filtro').value = filtro ;

					tbl.innerHTML="";
					tbl2.innerHTML="";
					renglon2=document.createElement("TR");

					renglon2.innerHTML="<td colspan='5'>" + filt +"</td>";
					tbl2.appendChild(renglon2);


					var renglon, columna;
					
					for (var i = 0; i < jsonData.datos.length; i++) {

						var dato = jsonData.datos[i];
							if(dato.sbnombre==null){var subfuncion = '-';}else{var subfuncion = dato.sbnombre;}
							if(dato.funombre==null){var funcion = '-';}else{var funcion = dato.funombre;}
							if(dato.actividad==null){var actividad = '-';}else{var actividad = dato.actividad;}
							if(dato.capitulo==null){var capitulo = '-';}else{var capitulo = dato.capitulo;}
							if(dato.partida==null){var partida = '-';}else{var partida = dato.partida;}
							if(dato.nombre==null){var nombre = '-';}else{var nombre = dato.nombre;}
							if(dato.finalidad==null){var finalidad = '-';}else{var finalidad = dato.finalidad;}
							

						renglon=document.createElement("TR");			
						renglon.innerHTML="<td>" + dato.idCuenta + "</td><td>" + nombre + "</td><td>" + funcion + "</td><td>" + subfuncion + "</td><td>" + actividad + "</td><td>" + capitulo + "</td><td>" + partida + "</td><td>" + finalidad + "</td><td>" + dato.original + "</td><td>" + dato.modificado + "</td><td>" + dato.ejercido + "</td><td>" + dato.pagado + "</td><td>" + dato.pendiente + "</td>";

						tbl.appendChild(renglon);					
					}				
				},
				/*error: function(xhr, textStatus, error){
					alert('function recuperarTabla ->statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
				}*/			
			});	
	
				
		});

		$( "#btnCuentapublica" ).click(function() {

			recuperarCuentaingre('tblaCuentapublica',document.all.tablaCuentaGen);
			
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
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">			
				<div class="col-xs-12">
					<div class="col-xs-3"><a href="/"><img src="img/logo-top.png"></a></div>				
					<div class="col-xs-3">
						<ul class="nav navbar-nav "><li><a href="#"><i class="fa fa-th-list"></i> <?php echo $_SESSION["sCuentaActual"] ?></a></li></ul>
					</div>					
					<div class="col-xs-3"><h2>Cuentas Públicas Egresos</h2></div>									
					
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
	</nav>

	
		<!-- Header starts -->
	<header>
		<div class="container">

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
			<a href=""><i class="fa fa-pencil-square-o"></i> Programas<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="PROGRAMA-UL"></ul>
		  </li>

		  <li class="has_sub"  id="AUDITORIA" style="display:none;">
			<a href=""><i class="fa fa-pencil-square-o"></i> Auditorías<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
			<ul id="AUDITORIA-UL"></ul>
		  </li>
		  
		  <li class="has_sub"  id="OBSERVACIONES" style="display:none;">
			<a href=""><i class="fa fa-pencil-square-o"></i> Acciones<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>
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
				  	<div class="pull-left"><h3 class="pull-left"><i class="fa fa-home"></i> Cuenta Publica</h3></div>
				  	<div class="btn-toolbar pull-right" role="toolbar">
				 	<button type="button" id="btnCuentapublica" 	style="display: none;" 		class="btn btn-default  btn-xs"><i class="fa fa-undo"></i> Cuenta Publica</button>
				 	<button type="button" id="btnRegresar" 	style="display: none;" 		class="btn btn-default  btn-xs"><i class="fa fa-undo"></i> Regresar</button>
				 	<button type="button" id="btnMostrarconsul" style="display: none;" 		class="btn btn-default  btn-xs"><i class="fa fa-undo"></i> Visualizar Consulta </button>
				  	<div class="btn-group" style="display: none;" id="exporta">
					  	<form action="ficheroExcel.php"   method="post" target="_blank" id="FormularioExportacion">
					  		<button type="button" class="btn btn-default  btn-xs botonExcel"><i class="fa fa-file-text-o"></i> Exportar a Excel </button>
					  		<input type="hidden" idname="nombre">
							<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
					  	</form>
				  	</div>

				  	<div class="btn-group" style="display: none;" id="exporta_egresos">
					  	<form action="ficheroExcel.php"   method="post" target="_blank" id="FormularioExportacion">
					  		<button type="button" class="btn btn-default  btn-xs botonExcelexport"><i class="fa fa-file-text-o"></i> Exportar a Excel </button>
					  		<input type="hidden" idname="nombre">
							<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
					  	</form>
				  	</div>
				  </div>  
				  <div class="clearfix"></div>
				</div>             
				<div class="widget-content" id="listacuenta">						
						<div class="table-responsive" style="height: 400px; overflow: auto; overflow-x:hidden;">
							<table class="table table-striped table-bordered table-hover">
							  <thead>
								<tr ><th>Cuenta Publica</th><th>Nombre</th><th>Año</th><th>Fecha Incio</th><th>Fecha Fin</th><th>Observaciones</th><th>Estatus</th></tr>
							  </thead>
							  <tbody >
							  <?php foreach($datos as $key => $valor): ?>
								<tr style="width: 100%; font-size: inherit">
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="10%;"><?php echo $valor['cuenta']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="20%;"><?php echo $valor['nombre']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="5%;"><?php echo $valor['anio']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="15.83%;"><?php echo $valor['inicio']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="15.83%;"><?php echo $valor['fin']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="17%;"><?php echo $valor['obser']; ?></td>
								  <td onclick=<?php echo "javascript:recuperacuenta('" . $valor['cuenta'] . "');"; ?> width="11%;"><?php echo $valor['estatus']; ?></td>
								</tr>
								<?php endforeach; ?>
			                  </tbody>
							</table>
						</div>
				</div>

				<div class="row" id="Cuentavisual" style="display:none; padding:0px; margin:0px;">
						<input id="CuenPu" type="text" name="txtCP" style="display:none;">		
						<div class="table-responsive" style="height: 680px; overflow: auto; overflow-x:hidden;">
							
							<div class="col-xs-12" style="margin: 1% 0 0 0 !important;">
								<div class="form-group">
									
									<div id="sujet">	
										<label class="col-xs-1 control-label">Sujeto</label>
										<div class="col-xs-11">
											<!--<select class="form-control" id="Sujet" name="txtSujeto" onchange="recuperarLista('lstFinalidadesByCuenta', document.all.txtFinalidad); recutipo(this.value); recuperarLista('capitulo', document.all.txtCapitulo);">-->
											<select class="form-control" id="Sujet" name="txtSujeto" onchange="recutipo(this.value);">
												<option value="" Selected>Seleccione un sujeto...</option>
											</select>
										</div>
									</div>
								</div>										
							</div>

							<div class="col-xs-12" style="margin: 1% 0 0 0 !important;">
								<div class="col-xs-6">
									
									<div class="form-group">
										<div id="fina" style="display: none;">
											<label class="col-xs-2 control-label">Finalidad</label>
											<div class="col-xs-10">
												<select class="form-control" id="finalidad" name="txtFinalidad" onchange="recuperarreporte('funciones',this.value,document.all.txtFuncion); ocultarcajas('funciones',this.value);">
													<option value="" Selected>Seleccione...</option>
												
												</select>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div id="func" style="display: none;">
											<label class="col-xs-2 control-label">Función</label>

											<div  class="col-xs-10 filtros">
												<select class="form-control" id="funcion" name="txtFuncion" onchange="recuperarreporte('subfunciones',this.value,document.all.txtSubfuncion); ocultarcajas('subfunciones',this.value);">
												
													<option value="" Selected>Seleccione...</option>

												</select>
											</div>
										</div>
									</div>	

									<div class="form-group">
										<div id="subfun" style="display: none;">
											<label class="col-xs-2 control-label">Subfunción</label>
											<div  class="col-xs-10 filtros">
												<select class="form-control" id="subfuncion" name="txtSubfuncion" onchange="recuperarreporte('actividad',this.value,document.all.txtActividades);">
													<option value="" Selected>Seleccione...</option>

												</select>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div id="acti" style="display: none;">
											<label class="col-xs-2 control-label">Actividades</label>
											<div  class="col-xs-10">
												<select class="form-control" id="actividades" name="txtActividades">
													<option value="" Selected>Seleccione...</option>
												</select>
											</div>
										</div>
									</div>

								</div>

								<div class="col-xs-6" style="margin: 1% 0 0 0 !important;">
									<div id="capi" style="display: none;">
										<label class="col-xs-2 control-label">Capitulo</label>
										<div class="col-xs-10">
											<select class="form-control" id="capitulo" name="txtCapitulo" onchange="recuperarreporte('partida',this.value,document.all.txtPartida);">
												<option value="" Selected>Seleccione...</option>
											</select>
										</div>
									</div>

									<div id="part" style="display: none;">
										<label class="col-xs-2 control-label">Partida</label>
										<div class="col-xs-10 filtros">
											<select class="form-control" id="partida" name="txtPartida">
												<option value="" Selected>Seleccione...</option>
											</select>
										</div>
									</div>									

									<div id="Tipotipo" style="display: none;">
										<label class="col-xs-1 control-label" style="text-align: left; margin: 0px 0px 0px -1% ! important; height: 38px ! important; width: 10% ! important;">Tipo de Ingreso</label>
										<div class="col-xs-2">
											<select class="form-control" id="tipoingre" name="txtTipoingre" onchange="selectsujetoTipo(this.value,CuenPu.value,Sujet.value);">
												<option value="" Selected>Seleccione...</option>

											</select>
										</div>
									</div>	
								</div>
								<div id="linea">
								 <hr style="margin: 16% 0 0 -5px !important; height: 124% ! important; color: rgb(204, 204, 204) ! important; border-top: 6px solid ! important; ">
									
								</div>
							</div>

							<div class="form-group" id="tabINGRESOS" style="display: none;">									
								<div class="col-xs-12">
									<table class="table table-striped table-bordered table-hover table-condensed"  id="Exportar_a_Excel">
										<caption style=" margin: 9px 0 0 !important;">EGRESOS</caption>
										<thead id="filtro" style="font-weight: bold;font-style: italic"></thead>
										<thead>
											<tr style="font-size: xx-small"><th>Cuenta Publica</th><th width="-5%">Sujeto</th><th width="-5%">Función</th><th width="-5%">Subfunción</th><th width="-5%">Actividad</th><th width="-5%">Capitulo</th><th>Partida</th><th>Finalidad</th><th>Original</th><th>Modificado</th><th>Ejercido</th><th>Pagado</th><th>Pendiente</th></tr>
										</thead>                                                            
										<tbody id="tablaCuentaIN" style="width: 100%; font-size: xx-small"></tbody>
									</table>
								</div>									
							</div>

							<div class="form-group" id="TabEGRESOS" style="display: none;">									
								<div class="col-xs-12">
									<table class="table table-striped table-bordered table-hover table-condensed" id="Exportar_a_Egresos">
										<caption style=" margin: 9px 0 0 !important;">INGRESO GENERAL</caption>
										<thead>
										<tr style="font-size: xx-small"><th>Cuenta Publica</th><th>Origen</th><th>Tipo</th><th>Clave</th><th>Nivel</th><th>Rubro</th><th>Original</th><th>Recaudado</th></tr>
										</thead>
										<tbody id="tablaCuentaEG" style="width: 100%; font-size: xx-small">					  
																														
										</tbody>
									</table>
								</div>									
							</div>

							<div class="form-group" id="TabEGRESOSdeta" style="display: none;">									
								<div class="col-xs-12">
									<table class="table table-striped table-bordered table-hover table-condensed" id="Exportar_a_Egresos_Deta">
										<caption style=" margin: 9px 0 0 !important;">INGRESO ESPECIFICO</caption>
										<thead>
										<tr style="font-size: xx-small"><th>Cuenta Publica</th><th>Nombre</th><th>Estimado</th><th>Registrado</th><th>Importe</th></tr>
										</thead>
										<tbody id="tablaCuentaEGDE" style="width: 100%; font-size: xx-small">					  
																														
										</tbody>
									</table>
								</div>									
							</div>


						</div>
				</div>

				<div id="modalCuentaGeneral" class="modal fade" role="dialog" style="z-index: 1600">
					<div class="modal-dialog">
						<input type='HIDDEN' name='txtOpera' value=''>									
						<!-- Modal content-->
						<div class="modal-content" style="width: 500% ! important; margin: 0px 0px 0px -69% ! important;">									
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h3 class="modal-title"><i class="fa fa-home"></i> Capturando...</h3>
							</div>									
							<div class="modal-body">
								<div class="form-group" id="tabCuentaEng" style="display: none;">									
									<div class="col-xs-12">
										<table class="table table-striped table-bordered table-hover table-condensed"  id="Exportar_a_Excel">
											<caption style=" margin: 9px 0 0 !important;">Cuenta publica</caption>
											<thead>
											<tr style="font-size: xx-small"><th width="1%">Cuenta Publica</th><th width="1%">Sujeto</th><th width="1%">Función</th><th width="1%">Subfunción</th><th width="1%">Actividad</th><th width="1%">Capitulo</th><th width="1%">Partida</th><th width="1%">Finalidad</th><th width="1%">Original</th><th width="1%">Modificado</th><th width="1%">Ejercido</th><th width="1%">Pagado</th><th width="1%">Pendiente</th></tr>
											</thead>                                                            
											<tbody id="tablaCuentaGen" style="width: 100%; font-size: xx-small">					  
																															
											</tbody>
										</table>
									</div>									
								</div>
							</div>				
							<div class="modal-footer">
								<button  type="button" class="btn btn-primary active" id="btnGuardarCapturado"><i class="fa fa-floppy-o"></i> Guardar</button>
								<button  type="button" class="btn btn-default" id="btnCancelarCapturado"><i class="fa fa-undo"></i> Cerrar</button>	
							</div>
						</div>
					</div>
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