<?php include ('\conexion.php'); ?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>.:: SUMAR CATAMARCA ::.</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

<link rel="icon" type="image/vnd.microsoft.icon" href="sumar.ico">

<!-- jQuery -->
<script type="text/javascript" src="smartmenu/libs/jquery/jquery.js"></script>

<!-- SmartMenus jQuery plugin -->
<script type="text/javascript" src="smartmenu/jquery.smartmenus.js"></script>

<!-- SmartMenus jQuery init -->
<script type="text/javascript">
	$(function() {
		$('#main-menu').smartmenus({
			mainMenuSubOffsetX: 1,
			mainMenuSubOffsetY: -8,
			subMenusSubOffsetX: 1,
			subMenusSubOffsetY: -8
		});
	});
</script>




<!-- SmartMenus core CSS (required) -->
<link href="smartmenu/css/sm-core-css.css" rel="stylesheet" type="text/css" />

<!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
<link href="smartmenu/css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style type="text/css">
	@media (min-width: 768px) {
		#main-menu {
			float: left;
			width: 12em;
		}
	}
</style>


<!-- YOU DO NOT NEED THIS - demo page content styles -->
<link href="smartmenu/libs/demo-assets/demo.css" rel="stylesheet" type="text/css" />




</head>

<body>




<nav id="main-nav" role="navigation">
  <p>
    <!-- Sample menu definition -->
  <img src="imgs/cussumar.jpg" width="750" height="186"></p>
  <ul id="main-menu" class="sm sm-vertical sm-blue">
    <li><a href="#">Administracion</a>
      <ul>
        <li><a href="ordenesdepago.php">Ordenes de Pago</a></li>
        <li><a href="ordenesdepago_capitado.php">Ordenes de Pago Capitado</a></li>
                <li><a href="ordenesdepago_fones.php">Ordenes de Pago Fones</a></li>
        <li><a href="expedientes.php">Expedientes</a></li>
                <li><a href="resumenmensual.php">Resumen Mensual</a></li>
        <li><a href="semaforo.php">Semaforo</a></li>
        <li><a href="#">Cantidad de Prestaciones</a>
        			<ul>
        				<li><a href="cantprestaciones.php">Solicitadas</a></li>
						<li><a href="cantprestacionesaprobadas.php">Aprobadas</a></li>
      				</ul>
        
        </li><strong></strong>        
		<li><a href="./phplogin/login.html">Actas</a></li>
                <li><a href="#">Auditoria</a>
        			<ul>
        				<li><a href="auditoria_muestra_facturacion.php">Muestra de Facturacion</a></li>
      				</ul>
        
        </li>
         <li><a href="mesaentrada.php">Mesa de Entrada</a></li>
         <li><a href="#">Reportes</a>
        			<ul>
        				<li><a href="reporte_ordenesdepago.php">Notificaciones Ordenes de Pago</a></li>
                        <li><a href="reporte_cierresmensuales.php">Cierres Mensuales</a></li>
      				</ul>
        
        </li>
      </ul>
    </li>
    
    <li><a href="#">Beneficiarios</a>
          <ul>
        <li><a href="beneficiarioconsulta.php">Todos</a></li>
        <li><a href="beneficiariodniajeno.php">DNI Ajenos</a></li>     
         <li><a href="beneficiario_ceb_totales.php">Sin CEB Totales</a></li    
        ><li><a href="beneficiario_ceb_conpractica.php">Por perder CEB Con Practicas</a></li>
        <li><a href="beneficiario_ceb_sinpractica.php">Por perder CEB Sin Practicas</a></li>
        <li><a href="beneficiario_sincobertura.php">Sin Cobertura(CEB)</a></li>    </ul></li>
    <li><a href="#">Estadisticas</a>
      <ul>
       <li><a href="#">Informe Bimestral de Prestaciones</a>
        			<ul>
				      	<li><a href="prestaciones_realizadas.php">Cantidad de Practicas Realizadas</a></li>
				      	<li><a href="prestaciones_aprobadas.php">Cantidad de Practicas Aprobadas</a></li>
      				</ul>
        
        </li><strong></strong>      
		<li><a href="efectores_estadisticas.php">Totales de Beneficiarios x Efector</a></li>
      <li><a href="totales_por_cuie_edad.php">Totales CEB Por Area, Cuie y Edad</a></li>
        
        <li><a href="prestacionesxfecha.php">Prestaciones por rango de Fechas</a></li>
        <li><a href="prestacionesxfecha_totales.php">Prestaciones por rango de Fechas - Totales</a></li>
      </ul>
    </li>
       <li><a href="#">Consultas</a>
          <ul>
                 <li><a href="#">Beneficiarios</a>
      				<ul>
        				<li><a href="beneficiarioconsulta.php">Todos</a></li>
						<li><a href="beneficiario_practicas.php">Practicas</a></li>
						<li><a href="beneficiariodniajeno.php">DNI Ajenos</a></li> 
						<li><a href="beneficiario_Historico_CEB.php">Historico CEB</a></li>  
                        <li><a href="beneficiario_otrasprovincias.php">Otras Provincias</a></li>                       
      				</ul>
    			</li>
                
                   <li><a href="#">Efectores</a>
      				<ul>
				 <li><a href="efectores.php">Consulta</a></li>
                  <li><a href="efectores_odontologia.php">Prestaciones Odontológicas</a></li>
                   <li><a href="actas_cuenta_escritural_libre.php">Cuenta Escritural</a></li> 
                     <li><a href="efectores_saldo_x_periodo.php">Saldo por Periodo</a></li>
                     <li><a href="efectores_analisis_fondos.php">Analisis de Fondos</a></li>
                     <li><a href="efectores_bienes.php">Bienes Entregados</a></li>
                  
                  
      				</ul>
    			</li>
              
                               <li><a href="#">Sip</a>
      				<ul>
        				<li><a href="sip.php">1 - Partos</a></li>
						<li><a href="sip2.php">Control</a></li>
						<li><a href="sip3.php">Serologia</a></li>
                        <li><a href="sip4.php">Cumplen requisitos</a></li>
                        <li><a href="sip_exportar.php">SIP - Exportar</a> /li> 
      				</ul>
    			</li> 
       
       
                <li><a href="obrasocial.php">Obra Social</a></li>           
				<li><a href="#">Nomenclador</a>
      				<ul>
        				<li><a href="nomenclador.php">Nomenclador</a></li>
						<li><a href="nomenclador_diagnostico.php">Diagnostico</a></li>
      				</ul>
                   
    			</li>             
      </ul></li>
      <li><a href="#">Trazadoras</a>
          <ul>
        <li><a href="trazadoras/trazadora2.php">Trazadora 2</a></li>
        <li><a href="trazadoras/trazadora3.php">Trazadora 3</a></li>
      </ul></li>
   <li><a href="#">Enlaces</a>
          <ul>
        <li><a href="http://192.168.1.240/inscripcion/index.php">Inscripcion de Beneficiarios</a></li>
        <li><a href="http://200.69.210.3/constancia/">Constancia de Inscripcion</a></li>
                <li><a href="vacunacion.php">Vacunacion</a></li>
                

      </ul>
    <li><a href="SumarResp/login.php">#Analisis de Centro#</a>
    <li><a href="#">Sumar Web</a>
    </li>
   
    
  </ul>
</nav>
</div>

</script>




<!-- =============================================================================== -->
<!-- ================= YOU DO NOT NEED ANYTHING AFTER THIS COMMENT ================= -->
<!-- =============================================================================== -->
<div>
  <p>&nbsp;</p>
  <table width="60%" border="1" align="center">
    <tr>
      <td colspan="3" class="sm sm-vertical sm-blue">MENSAJES</td>
    </tr>
    <tr>
      <td>FECHA</td>
      <td>MENSAJE</td>
      <td>DESTINATARIOS</td>
    </tr>
    <?php
    	$query_mensajes=" select * FROM SUMAR.dbo.MENSAJES";
	
	$res=sqlsrv_query($conn,$query_mensajes);
	if (isset($res)){
		
	while ($row=sqlsrv_fetch_array($res)) {
    ?>
    <tr>
      <td>&nbsp;<?php echo $row['fecha_mensaje']->format('d-m-Y'); ?></td>
      <td><?php echo $row['mensaje']; ?></td>
      <td><?php echo $row['destinatarios']; ?>&nbsp;</td>
    </tr>
    <?php }; }?>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>