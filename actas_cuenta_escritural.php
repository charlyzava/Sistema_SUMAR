<?php 
//Cambiar a Base ACTAS
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
$usuario=$_SESSION['username'];

include ('conexion.php');

} else {
   echo "Esta pagina es solo para usuarios registrados.<br>";
   echo "<br><a href='./phplogin/login.html'>Login</a>";
exit;
}
Header('Content-Type: text/html; charset=LATIN1');
$responsable="";
$fentrega="";

								// SE CONTABILIZA DESDE ESTA FECHA
									$fechainicio='2021-01-01';

									// ESTE O TAMBIEN ES IMPORTANTE PARA BECADOS
									$anioinicio='2021';


							//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//$fentrega=getdate();
//$fentrega=$fentrega->format($fentrega,'Y-m-d');
$acta="";
$anioentrega="";
$estado="";
$salida='';
$cuie='';
$encontro='0';
$grabado='0';
$delete='si';
	if (isset($_POST['tmes'])){ $mes=$_POST['tmes']; }
	if (isset($_POST['tanio'])){ $anio=$_POST['tanio'];
								$dia=date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));
								$dia=trim($dia);
								$fechalimite=$anio."-".$mes."-".$dia;
								 }
	if (isset($_POST['scuie'])){ $cuie = $_POST['scuie'];
								$cuiet=explode("-", $cuie );
								$cuie=$cuiet[0];
								$cuielargo=$cuiet[1];
	}

if (isset($_POST['buscar'])){
	
$periodos='';
$aniofin=$anio;

//*******************************************
//****** ATENCION AL CAMBIAR AÃ‘O ************
								$anio=2021;
//*******************************************
$meshasta=$mes;
$mes="01";

if ($anio==$aniofin){
	while ($mes<=$meshasta){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$periodos.="'".$periodo."',";
		$mes=$mes+1;
	}
	$periodos=rtrim($periodos,",");	
} //////////////////// OK

if ($anio<$aniofin){
	while ($anio<$aniofin){
		while ($mes<=12){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }

			$periodo=$mes."/".$anio;
			$periodos.="'".$periodo."',";
			$mes=$mes+1;
		}
		
		$anio=$anio+1;
		$mes=1;
}
$mes=1;
while($mes<=$meshasta){
	
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$periodos.="'".$periodo."',";
		$mes=$mes+1;
	}		
}
$periodos=rtrim($periodos,",");	

if(isset($_POST['buscar'])){
	$cont=0;
	

	if (($mes=='')or($anio=='')){
		$salida='Complete Mes y Anio de Entrega';
		} else {
	
	if (isset($fdesde)){
	$fdesde=$_POST['from'];
	$fdesde=explode("/" , $fdesde);
	$fdesde=$fdesde[2]."-".$fdesde[1]."-".$fdesde[0];
	}
		} 

		} // complete los datos de alta y anio entrega



//=================== ORDENES DE PAGO =======================//
	// ********************* TOTAL ******************
	$periodo='';
	//--------------------
	
// BUSQUEDA POR PERIODO
// EN ESTE CASO ANIO INICIO ES 2021 Y ANIO FIN ES EL DEL COMBOBOX
// MES ES 01 Y MES HASTA ES EL DEL COMBO
//-------
	$anio=$_POST['tanio'];
	$mes=$_POST['tmes'];	
	//******************************
$consulta_orden="select SUM(TOTAL) AS TOTAL from dbo.ORDEN_PAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP WHERE E.CUIE = '$cuie' AND  FECHAORDENPAGO >= '$fechainicio' ";
/* ELIMINE PERIODOS
*$consulta_orden="select SUM(TOTAL) AS TOTAL from dbo.ORDEN_PAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP WHERE E.CUIE = '$cuie' AND O.PERIODO IN ($periodos) AND FECHAORDENPAGO >= '$fechainicio' ";
*/
$result=sqlsrv_query($conn,$consulta_orden);
$row=sqlsrv_fetch_array($result);
$ordenes1=$row['TOTAL'];
$ordenes=number_format($ordenes1,2, ',', '.');
//============================= FIN TOTAL =========================================//


//************	ORDENES DE PAGO CAPITADO ***********/*/*/*/*/**//

$consulta_orden_capitado="select SUM(TOTAL_CON_CEB) + SUM(TOTAL_SIN_CEB) AS TOTAL from dbo.ORDENPAGO_CAPITADO WHERE CUIE ='$cuie' and
PERIODO IN ($periodos) ";

$result_capitado=sqlsrv_query($conn,$consulta_orden_capitado);
$row=sqlsrv_fetch_array($result_capitado);
$ordenes_capitado1=$row['TOTAL'];
$ordenes_capitado=number_format($ordenes_capitado1,2, ',', '.');


//*/*/*/*/*/ 	FIN ORDENES DE PAGO Capitado   */*/*/*/*/*/**/*//*


//================================= ORDENES FONES ===============================//

$consulta_orden_fones="select SUM((IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+
(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL)-DEBITO) as TOTAL  

from ORDENPAGO_FONES
WHERE CUIE ='$cuie'  and FECHANOTIFICACION is not NULL
 ";

$result_fones=sqlsrv_query($conn,$consulta_orden_fones);
$row=sqlsrv_fetch_array($result_fones);
$ordenes_fones1=$row['TOTAL'];
$ordenes_fones=number_format($ordenes_fones1,2, ',', '.');






//================================ FIN ORDENES FONES ===========================//


//============================= SALDO INICIAL =====================================//
$cons_saldo="SELECT FONDO_EMERGENCIA, SALDO_INICIAL FROM EFECTORES WHERE CUIE ='$cuie'";
$result=sqlsrv_query($conn,$cons_saldo);
$row=sqlsrv_fetch_array($result);
$saldo_inicial1=$row['SALDO_INICIAL'];
$saldo_inicial="$ ".number_format($saldo_inicial1,2, ',', '.');
$fondo_emergencia1=$row['FONDO_EMERGENCIA'];
$fondo_emergencia="$ ".number_format($fondo_emergencia1,2, ',', '.');
//============================== FIN SALDO INICIAL =================================//
//====================================== BECADOS ==============================================//

//FJSDFJSLDKFJSDLKFJSLDKJ /*/*/*///*/$%/(&(/&&%&/%/&))
//MODIFICAR PARA QUE ENTRE EL 2021 U AÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¹Ãƒâ€¦Ã¢â‚¬Å“OS ANTERIORES

$anios="";

if ($anio >= '2022'){
//ejemplo 2023
		for ($x = 2021; $x < $anio; $x++){
			$anios.="'".$x."',";
			}
			$anios=rtrim($anios,',');


$sql_becados="SELECT SUM(importe) AS pago FROM BECADOS b LEFT JOIN BECADOS_PAGOS p ON b.dni = p.dni 
WHERE (p.anio IN ($anios) AND b.cuie = '$cuie' AND fechapago >= '2021-01-01' ) OR ( p.anio = '$anio' AND p.mes <= '$mes' AND b.cuie = '$cuie' AND fechapago >= '2021-01-01')";

} else {

$sql_becados="SELECT SUM(importe) AS pago FROM BECADOS b LEFT JOIN BECADOS_PAGOS p ON b.dni = p.dni WHERE p.anio = '$anio' AND p.mes <= '$mes' AND b.cuie = '$cuie' AND fechapago >='$fechainicio'";


}
/*"SELECT SUM(importe) AS pago FROM BECADOS b LEFT JOIN BECADOS_PAGOS p ON b.dni = p.dni WHERE p.anio = '$anio' AND p.mes <= '$mes' AND b.cuie = '$cuie' AND fechapago >='$fechainicio'"*/
		$res_becados=sqlsrv_query($conn,$sql_becados);	
		$num=0;		
	$rowb=sqlsrv_fetch_array($res_becados);
	$becados1=$rowb['pago'];
	$becados=number_format($becados1,2, ',', '.');
//====================================== FIN BECADOS ==========================================//
//========================================BIENES ========================================//
$total_bienes="SELECT SUM(importe) AS suma FROM ACTAS.dbo.ACTAS a LEFT JOIN ACTAS.dbo.ACTAS_BIENES b on a.nro_acta = b.nro_acta AND a.anio_acta = b.anio_acta WHERE f_entrega <= '$fechalimite' and f_entrega >= '$fechainicio' and a.cuie='$cuie'";
		$res_bienest=sqlsrv_query($conn,$total_bienes);			
	//	if (isset($res_bienest)){
$row=sqlsrv_fetch_array($res_bienest);

//Bienes Directos
$total_bienes_directos="SELECT sum(CANTIDAD * PRECIOUNITARIO)  importe
 FROM [dbo].[ACTAS_DIRECTOS] a LEFT JOIN ACTAS_DIRECTOS_DETALLE d on a.ID = d.ID_ACTAS_DIRECTOS
where CUIE = '$cuie'";
$res_bienes_directos=sqlsrv_query($conn,$total_bienes_directos);
$row_bienes=sqlsrv_fetch_array($res_bienes_directos);



$bienes1=$row['suma']+$row_bienes['importe'];
$bienes=number_format($bienes1,2, ',', '.');	
//	}
//====================================== FIN BIENES ======================================//
//====================================== INICIO CESION =====================================//
$cons_cesion_hasta="select SUM(importe) AS importe FROM dbo.CESIONES WHERE cuie_hasta = '$cuie'";
$res_cesion_hasta=sqlsrv_query($conn, $cons_cesion_hasta);
$rowc=sqlsrv_fetch_array($res_cesion_hasta);
$cesion_hasta=$rowc['importe'];
$cesion_hastaf=number_format($cesion_hasta,2, ',', '.');

$cons_cesion_desde="select SUM(importe) AS importe FROM dbo.CESIONES WHERE cuie_desde = '$cuie'";
$res_cesion_desde=sqlsrv_query($conn,$cons_cesion_desde);
$rowc=sqlsrv_fetch_array($res_cesion_desde);
$cesion_desde=$rowc['importe'];
$cesion_desdef=number_format($cesion_desde,2, ',', '.');
//====================================== FIN CESION =========================================//

	
$saldo_disponible=$saldo_inicial1+$ordenes1+$ordenes_capitado1+$ordenes_fones1-$becados1-$bienes1+$cesion_hasta-$cesion_desde;

$saldodisponible=number_format($saldo_disponible,2, ',', '.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>


<head>
<meta charset="LATIN1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Alta de Actas ::.</title>
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">

  <script type="text/javascript">
 
	function objetoAjax(){
		var xmlhttp = false;
		try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
 
			try {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				xmlhttp = false; }
		}
 
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		  xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}

function enviarDatos(){
 
 		c = confirm('¿Confirma Cerrar la Factura?');
	if (c) {
 
		//form1: nombre del formulario
		//tacta,tanio: edits con los valores
 		acta = document.form1.tacta.value;
		anio_acta = document.form1.tanio.value;
         //Aqu el resultado
		jugador = document.getElementById('jugador');
 
		//instanciamos el objetoAjax
		ajax = objetoAjax();
 
		//Abrimos una conexi¡metros el todo de envo, y el archivo que realizar las operaciones deseadas
		ajax.open("POST", "actas_cierrafactura.php", true);
 
		//cuando el objeto XMLHttpRequest cambia de estado, la funcin se inicia
		ajax.onreadystatechange = function() {
 
             //Cuando se completa la peticin, mostrar los resultados 
			if (ajax.readyState == 4){
 
				//El ©todo responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
				jugador.value = (ajax.responseText) 
				salida.value = (ajax.responseText)
			}
		} 
 
		//Llamamos al todo setRequestHeader indicando que los datos a enviarse estn codificados como un formulario. 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
		//enviamos las variables a 'consulta.php' 
		//ajax.send("&equipo="+equipo) 
		ajax.send("&acta="+acta+"&anio_entrega="+anio_acta) ;    
		
 
	} 
 
} 

</script>
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( "dateFormat", element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>
  <script>
		//Muestra u oculta BECADOS
$(document).ready(function(){
   $("#mostrar_becados").click(function(){
		  if($("#mostrar_becados").is(':checked')) { ;
         $("#divbecados").css("display", "block");;
      }else{
		 $("#divbecados").css("display","none");
      }
   });



		//Muestra u oculta ENTREGAS

   $("#mostrar_entregas").click(function(){      
		  if($("#mostrar_entregas").is(':checked')) { ;
         $("#diventregas").css("display", "block");
      }else{
         $("#diventregas").css("display", "none");
      }
   });
   
  	 //Muestra u oculta ORDENPAGO
   
      $("#mostrar_ordenpago").click(function(){      
		  if($("#mostrar_ordenpago").is(':checked')) { ;
         $("#divordenpago").css("display", "block");
      }else{
         $("#divordenpago").css("display", "none");
      }
   });
   
		//Muestra u oculta ORDENPAGO CAPITADO


         $("#mostrar_ordenpago_capitado").click(function(){      
		  if($("#mostrar_ordenpago_capitado").is(':checked')) { ;
         $("#divordenpagocapitado").css("display", "block");
      }else{
         $("#divordenpagocapitado").css("display", "none");
      }
   });
   
   
   //Muestra u oculta ORDENPAGO FONES


         $("#mostrar_ordenpago_fones").click(function(){      
		  if($("#mostrar_ordenpago_fones").is(':checked')) { ;
         $("#divordenpagofones").css("display", "block");
      }else{
         $("#divordenpagofones").css("display", "none");
      }
   });
   
   
   
     	 //Muestra u oculta CESION DESDE
   
      $("#mostrar_cesiondesde").click(function(){      
		  if($("#mostrar_cesiondesde").is(':checked')) { ;
         $("#divcesiondesde").css("display", "block");
      }else{
         $("#divcesiondesde").css("display", "none");
      }
   });
   
   
        	 //Muestra u oculta CESION HASTA
   
      $("#mostrar_cesionhasta").click(function(){      
		  if($("#mostrar_cesionhasta").is(':checked')) { ;
         $("#divcesionhasta").css("display", "block");
      }else{
         $("#divcesionhasta").css("display", "none");
      }
   });
   
   
});
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Consulta de Beneficiarios ::.</title>

<style type="text/css">
body {
	background-color: #D6D6D6;
	text-align: center;
}
Codigo: Seleccionar todo 
.input, .button{ 
margin: 1px; 
padding:2px; 
font-weight: bold; 
cursor:pointer; 
background:#91C6F9 repeat; 
border:1px solid #000000; 
font-family: 'Lucida Grande', Tahoma, Arial, Verdana, sans-serif; 

} 
.input:hover, .button:hover{ 

background:#3ED32E; 
color:#000000; 
font-weight: bold; 
cursor:pointer; 
border:1px solid #000000; 
font-family: 'Lucida Grande', Tahoma, Arial, Verdana, sans-serif; 
} 


</style>
		<link rel="shortcut icon" href="sumar.ico"> 
</head>
<body>
<center>
<table width="100%" border="1">
  <tr>
    <td width="33%" height="58" align="left">
     <?php include("actas_menusmall.php"); ?> </td>
    <!--   -->
    <td><!--   -->
    </td>
    <td width="34%" height="58" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Cuenta Escritural</td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%" height="58"><center>
      <a href="excel/cuentaescritural_excel.php?anio=<?=$anio?>&amp;mes=<?=$mes?>&amp;cuie=<?=$cuie?>&amp;cuielargo=<?=$cuielargo?>&amp;periodos=<?=$periodos?>&amp;ordenes=<?=$ordenes?>&amp;ordenescapitado=<?=$ordenes_capitado?>&ordenesfones=<?=$ordenes_fones?>&amp;becados=<?=$becados?>&amp;bienes=<?=$bienes?>&amp;fechainicio=<?=$fechainicio?>">
      <?php if (isset($_POST['buscar'])){?>
      <img src="imgs/xls-icon-small.png" alt="Generar Archivo de Excel" width="40" height="40" /></a><a href="pdf/cuentaescritural_pdf.php?anio=<?=$anio?>&mes=<?=$mes?>&cuie=<?=$cuie?>&cuielargo=<?=$cuielargo?>&periodos=<?=$periodos?>&ordenes=<?=$ordenes?>&ordenescapitado=<?=$ordenes_capitado?>&ordenesfones=<?=$ordenes_fones?>&becados=<?=$becados?>&bienes=<?=$bienes?>&saldodisponible=<?=$saldodisponible?>&saldoinicial=<?=$saldo_inicial?>&cesion_desde=<?=$cesion_desde?>&cesion_hasta<?=$cesion_hasta?>" target="_blank">
      <img src="imgs/pdf-icon-small.png" alt="Generar Archivo de PDF" width="40" height="40" />
      
      <?php } ?>
      </a><a href="phplogin/logout.php"><img src="imgs/cerrar_sesion.png" width="132" height="33"></a>
    </center></td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
<table width="400" border="1" align="center">
    
  <tr>
      <td>Mes / Anio</td>
      <td><label for="tacta"></label>
        <select name="tmes" id="tmes">
          <?php $sel="";
		if (isset($_POST['tmes'])){ $sel=$_POST['tmes']; } ?>
          <option value="01" <?php if ($sel=="01"){ echo "selected=\"selected\""; } else if (date("m")=="01"){echo "selected=\"selected\""; } ?>>Enero</option>
          <option value="02" <?php if ($sel=="02"){ echo "selected=\"selected\""; } else if (date("m")=="02"){echo "selected=\"selected\""; } ?>>Febrero</option>
          <option value="03" <?php if ($sel=="03"){ echo "selected=\"selected\""; } else if (date("m")=="03"){echo "selected=\"selected\""; } ?>>Marzo</option>
          <option value="04" <?php if ($sel=="04"){ echo "selected=\"selected\""; } else if (date("m")=="04"){echo "selected=\"selected\""; } ?>>Abril</option>
          <option value="05" <?php if ($sel=="05"){ echo "selected=\"selected\""; } else if (date("m")=="05"){echo "selected=\"selected\""; } ?>>Mayo</option>
          <option value="06" <?php if ($sel=="06"){ echo "selected=\"selected\""; } else if (date("m")=="06"){echo "selected=\"selected\""; } ?>>Junio</option>
          <option value="07" <?php if ($sel=="07"){ echo "selected=\"selected\""; } else if (date("m")=="07"){echo "selected=\"selected\""; } ?>>Julio</option>
          <option value="08" <?php if ($sel=="08"){ echo "selected=\"selected\""; } else if (date("m")=="08"){echo "selected=\"selected\""; } ?>>Agosto</option>
          <option value="09" <?php if ($sel=="09"){ echo "selected=\"selected\""; } else if (date("m")=="09"){echo "selected=\"selected\""; } ?>>Septiembre</option>
          <option value="10" <?php if ($sel=="10"){ echo "selected=\"selected\""; } else if (date("m")=="10"){echo "selected=\"selected\""; } ?>>Octubre</option>
          <option value="11" <?php if ($sel=="11"){ echo "selected=\"selected\""; } else if (date("m")=="11"){echo "selected=\"selected\""; } ?>>Noviembre</option>
          <option value="12" <?php if ($sel=="12"){ echo "selected=\"selected\""; } else if (date("m")=="12"){echo "selected=\"selected\""; } ?>>Diciembre</option>
        </select>
/
<label for="tanio"></label>
<label for="tanio"></label>
<select name="tanio" id="tanio">
<?php $anio=date("Y");
$aniofin=$anio-3;
for ($i=$anio;$i>=$aniofin;$i--){
 ?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php }; ?>
</select>
<span class="mensajerojo"><?php if ($estado=="cerrada"){ ?><a href="pdf/actas_pdf.php?acta=<?=$acta?>&amp;anioentrega=<?=$anioentrega?>" target="_blank"><img src="imgs/pdf-icon-small.png" width="25" height="25" /></a><?php } ?></span></td>
  </tr>
  <tr>
    <td>Cuie </td>
    <td><?php include('comboselec4.php');  ?></td>
  </tr>
  <tr>
    <td colspan="2"><center><input type="submit" name="buscar" id="buscar" value="Mostrar">
    </center></td>
    </tr>
</table>
<span class="mensajerojo">
<?php 
 echo $salida; 
 if (isset($_POST['buscar'])){
  ?>
</span>
<table width="380" border="1" align="center">
  <tr>
    <td width="200">Saldo Inicial al 1 de Enero 2021</td>
    <td><?php echo $saldo_inicial; ?></td>
  </tr>
  <tr>
    <td>Fondo Emergencia</td>
    <td><?php echo $fondo_emergencia; ?></td>
  </tr>
</table>
<table width="350" border="1" align="center">
  <tr>
    <td width="200"><span class="mensajerojo">
      <input type="checkbox" name="mostrar_ordenpago" value="1" id="mostrar_ordenpago">
    </span>Ordenes de Pago</td>
    <td><?php 
	echo "$ ".$ordenes; ?></td>
  </tr>
</table>
<div id="divordenpago" style="display: none">
<table width="800" border="1" align="center">
  <tr>
    <td width="11%">N&ordm; Orden</td>
    <td width="13%">Expediente</td>
    <td width="14%">N&ordm; Factura</td>
    <td width="15%">Per&iacute;odo</td>
    <td width="16%">Fecha Orden de Pago</td>
    <td width="16%">Fecha Notificacion</td>
    <td width="16%">Fecha D&eacute;bito Bancario</td>
    <td width="20%">Cuenta Corriente Para</td>
    <td width="11%">Importe Pagado</td>
  </tr>
  <?php
$periodo='';
	//--------------------
	
// BUSQUEDA POR PERIODO
// EN ESTE CASO ANIO INICIO ES 2017 Y ANIO FIN ES EL DEL COMBOBOX
// MES ES 01 Y MES HASTA ES EL DEL COMBO

//-------
$salida='';
$aniofin=$anio;
$anio=2017;
$meshasta=$mes;
$mes="01";

if ($anio==$aniofin){
	while ($mes<=$meshasta){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		
		$mes=$mes+1;
		$salida.=",";
	}
	$salida=rtrim($salida,",");	
} //////////////////// OK

if ($anio<$aniofin){
	while ($anio<$aniofin){
		while ($mes<=12){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }

			$periodo=$mes."/".$anio;
			$salida.="'".$periodo."'";
			$mes=$mes+1;
			$salida.=",";			
		}
		
		$anio=$anio+1;
		$mes=1;
}
$mes=1;
while($mes<=$meshasta){
	
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		$mes=$mes+1;
		$salida.=",";	
	}		
}
$salida=rtrim($salida,",");

	$anio=$_POST['tanio'];
	$mes=$_POST['tmes'];	
	//******************************
	$consulta="select * from dbo.ORDEN_PAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP WHERE E.CUIE = '$cuie' AND FECHAORDENPAGO >= '$fechainicio' ORDER BY O.NROORDENPAGO";
$salida=$consulta;
$result=sqlsrv_query($conn,$consulta);
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		$total=$row["TOTAL"];
		$totalfinal=$totalfinal+$total;
		$fecha=$row["FECHAORDENPAGO"];
		$fecha=date("Y-m-d");
		if (isset($scuie[1])){	$nomcuie=$scuie[1]; }
  ?>
  <tr>
    <td align="left" valign="bottom"><a href="pdf/ordenesdepago_pdf.php?idordenpago=<?=$row["NROORDENPAGO"]?>" target="_blank"><img src="imgs/pdf-icon-small.png" alt="exportar a PDF" width="25" height="25" /></a><?php echo $row["NROORDENPAGO"]; ?></td>
    <td><?php echo $row["NROEXPEDIENTE"]."-".$row["ANIOEXP"]; ?></td>
    <td><a href="ordenesdepago_facturacion.php?factura=<?=$row["NROFACTURA"]?>&periodo=<?=$row["PERIODO"];?>&cuie=<?=$CUIE?>&scuie=<?=$nomcuie?>" target="_blank"><img src="imgs/lupa.gif" alt="Ver Orden de Pago" width="16" height="15" /></a><?php echo $row["NROFACTURA"]; ?></td>
    <td><?php echo $row["PERIODO"]; ?></td>
    <td align="right"><?php if (($row["FECHAORDENPAGO"])<>""){ echo $row["FECHAORDENPAGO"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php if (($row["FECHANOTIFICACION"])<>""){ echo $row["FECHANOTIFICACION"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php if (($row["FECHADEBITOBANCARIO"])<>""){ echo $row["FECHADEBITOBANCARIO"]->format('d-m-Y'); } ?></td>
    <td><?php echo $row[11]; ?></td>
    <td><?php echo $row["TOTAL"]; ?></td>
  </tr>
  <?php } ; ?>
  <tr>
    <td colspan="7">&nbsp;</td>
    <td>Total: <?php echo $totalfinal; ?></td>
  </tr>
</table>
</div>
<table width="350" border="1" align="center">
  <tr>
    <td width="200"><span class="mensajerojo">
      <input type="checkbox" name="mostrar_ordenpago_capitado" value="1" id="mostrar_ordenpago_capitado">
    </span>Ordenes de Pago Capitado</td>
    <td><?php 
	echo "$ ".$ordenes_capitado; ?></td>
  </tr>
</table>
<div id="divordenpagocapitado" style="display: none">
<table width="800" border="1" align="center">
  <tr>
    <td >N&ordm; Orden</td>
    <td >CUIE</td>
    <td >Cant Activo</td>
    <td >Cant con CEB</td>
    <td >Cant sin CEB</td>
    <td >Importe con CEB</td>
    <td >Importe sin CEB</td>
    <td >Total con CEB</td>
    <td >Total sin CEB</td>
    <td >Total</td>
    <td >Periodo</td>
  </tr>
  <?php
$periodo='';
	//--------------------
	
// BUSQUEDA POR PERIODO
// EN ESTE CASO ANIO INICIO ES 2017 Y ANIO FIN ES EL DEL COMBOBOX
// MES ES 01 Y MES HASTA ES EL DEL COMBO

//-------
$salida='';
$aniofin=$anio;
$anio=2017;
$meshasta=$mes;
$mes="01";

if ($anio==$aniofin){
	while ($mes<=$meshasta){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		
		$mes=$mes+1;
		$salida.=",";
	}
	$salida=rtrim($salida,",");	
} //////////////////// OK

if ($anio<$aniofin){
	while ($anio<$aniofin){
		while ($mes<=12){
		
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }

			$periodo=$mes."/".$anio;
			$salida.="'".$periodo."'";
			$mes=$mes+1;
			$salida.=",";			
		}
		
		$anio=$anio+1;
		$mes=1;
}
$mes=1;
while($mes<=$meshasta){
	
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		$mes=$mes+1;
		$salida.=",";	
	}		
}
$salida=rtrim($salida,",");

	$anio=$_POST['tanio'];
	$mes=$_POST['tmes'];	
	//******************************
	$consulta_capitado="select *, TOTAL_CON_CEB + TOTAL_SIN_CEB AS TOTAL from dbo.ORDENPAGO_CAPITADO WHERE CUIE ='$cuie' and PERIODO IN ($periodos) ORDER BY NROORDENPAGO";
$salida=$consulta_capitado;
$result=sqlsrv_query($conn,$consulta_capitado);
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		$total=$row["TOTAL_CON_CEB"]+$row["TOTAL_SIN_CEB"];
		$totalfinal=$totalfinal+$total;
		/*$fecha=$row["FECHAORDENPAGO"];
		$fecha=date("Y-m-d");*/
		if (isset($scuie[1])){	$nomcuie=$scuie[1]; }
  ?>
  <tr>
    <td><?php echo $row["NROORDENPAGO"]; ?></td>
    <td><?php echo $row["CUIE"]; ?></td>
    <td><?php echo $row["CANTIDAD_ACTIVO"]; ?></td>
    <td><?php echo $row["CANTIDAD_CON_CEB"]; ?></td>
    <td><?php echo $row["CANTIDAD_SIN_CEB"]; ?></td>
    <td><?php echo $row["IMPORTE_CON_CEB"]; ?></td>
    <td><?php echo $row["IMPORTE_SIN_CEB"]; ?></td>
    <td><?php echo $row["TOTAL_CON_CEB"]; ?></td>
    <td><?php echo $row["TOTAL_SIN_CEB"]; ?></td>
    <td><?php echo $row["TOTAL"]; ?></td>
    <td><?php echo $row["PERIODO"]; ?></td>
  </tr>
  <?php } ; ?>
  <tr>
    <td colspan="7">&nbsp;</td>
    <td>Total: <?php echo $totalfinal; ?></td>
  </tr>
</table>
</div>
<table width="350" border="1" align="center">
  <tr>
    <td width="200"><span class="mensajerojo">
      <input type="checkbox" name="mostrar_ordenpago_fones" value="1" id="mostrar_ordenpago_fones">
    </span>Ordenes de Pago Fones</td>
    <td><?php 
	echo "$ ".$ordenes_fones; ?></td>
  </tr>
</table>
<div id="divordenpagofones" style="display: none">
  <table width="1000" border="1" align="center">
  <tr>
    <td >N&ordm; Orden</td>
    <td >CUIE</td>
    <td >Fecha Orden</td>
    <td >Periodo</td>
    <td >Dias Sala Comun</td>
    <td >Dias Uci Sin ARM</td>
    <td >Dias Uci con ARM</td>
    <td >Sesiones Reemplazo</td>
    <td >Subtotal</td>
    <td >Debitos</td>
    <td >Total</td>
  </tr>
  <?php
$periodo='';
	//--------------------

//-------

	//******************************
	$consulta_fones="select (IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+
(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL)-DEBITO as IMPORTE_TOTAL, *

from ORDENPAGO_FONES where CUIE ='$cuie' and FECHANOTIFICACION is not NULL

";
$salida=$consulta_fones;
$result=sqlsrv_query($conn,$consulta_fones);
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		$total=$row["IMPORTE_TOTAL"];
		$totalfinal=$totalfinal+$total;

		if (isset($scuie[1])){	$nomcuie=$scuie[1]; }
  ?>
  <tr>
  	<td><?php echo $row["NROORDENPAGO"]; ?></td>
  	<td><?php echo $row["CUIE"]; ?></td>
  	<td><?php echo $row["FECHAORDENPAGO"]->format('d-m-Y'); ?></td>
  	<td><?php echo $row["PERIODO"]; ?></td>
  	<td><?php echo $row["DIASSALACOMUN"]; ?></td>
  	<td><?php echo $row["DIASUCISINARM"]; ?></td>
  	<td><?php echo $row["DIASUCICONARM"]; ?></td>
  	<td><?php echo $row["SESIONESREEMPLAZORENAL"]; ?></td>
  	<td><?php echo $row["IMPORTE_TOTAL"]; ?></td>
  	<td><?php echo $row["DEBITO"]; ?></td>
  	<td><?php echo $row["IMPORTE_TOTAL"]-$row["DEBITO"]; ?></td>
  </tr>
  <?php } ; ?>
  <tr>
    <td colspan="7">&nbsp;</td>
    <td>Total: <?php echo $totalfinal; ?></td>
  </tr>
</table>
</div>
<table width="350" border="1" align="center">
  <tr>
    <td width="200"><span class="mensajerojo">
      <input type="checkbox" name="mostrar_becados" value="1" id="mostrar_becados">
    </span>Total Becados</td>
    <td><?php echo "$ ".$becados; ?></td>
  </tr>
</table>

<div id="divbecados" style="display: none;">
  <table width="800" border="1" align="center" >
  <tr>
    <td>dni</td>
    <td>Apellido y Nombre</td>
    <td>Area</td>
    <td>Activo</td>
    <td>Importe</td>
    <td>Fecha Pago</td>
    <td>Expte</td>
  </tr>
  <?php	//style="display: none;"
  		$total_becados=0;


/* AQUI MAGIA */

$anios="";

if ($anio >= '2022'){
//ejemplo 2023
		for ($x = 2021; $x < $anio; $x++){
			$anios.="'".$x."',";
			}
			$anios=rtrim($anios,',');


$sql_becados="SELECT *  FROM BECADOS b LEFT JOIN BECADOS_PAGOS p ON b.dni = p.dni 
WHERE (p.anio IN ($anios) AND b.cuie = '$cuie' AND fechapago >= '2021-01-01' ) OR ( p.anio = '$anio' AND p.mes <= '$mes' AND b.cuie = '$cuie' AND fechapago >= '2021-01-01')";

} else {

$sql_becados="SELECT * FROM BECADOS b LEFT JOIN BECADOS_PAGOS p ON b.dni = p.dni WHERE p.anio = '$anio' AND p.mes <= '$mes' AND b.cuie = '$cuie' AND fechapago >='$fechainicio'";


}
/* FIN MAGIA */

		$res_becados=sqlsrv_query($conn,$sql_becados);	
		$num=0;		
	while($rowb=sqlsrv_fetch_array($res_becados)){
	$total_becados=$total_becados+$rowb['importe'];
  ?>
  <tr>
    <td align="left"><label for="checkbox"></label>
      <label for="rbbien2"></label>
      <?php echo $rowb["dni"]; ?></td>
    <td align="left"><label for="select3"><?php echo $rowb["apenom"]; ?></label></td>
    <td align="right"><?php echo $rowb['area']; ?></td>
    <td><?php echo $rowb["estado"]; ?></td>
    <td><?php echo number_format($rowb["importe"],2, ',', '.')?></td>
    <td><?php echo $rowb["fechapago"]->format('d-m-Y'); ?></td>
    <td><?php echo $rowb["expediente"]; ?></td>
  </tr>
  <?php } ;  ?>
</table>
</div>
  <label for="tinventario">
  </label>
    <label for="tinventario">
  </label>
<table width="350" border="1" align="center">
    <tr>
      <td width="200"><input type="checkbox" name="mostrar_entregas" value="1" id="mostrar_entregas">
        Total Bienes</td>
      <td><?php echo "$ ".$bienes; ?></td>
    </tr>
  </table>

  <div id="diventregas" style="display: none;">
    <table width="800" border="1" align="center">
    <tr>
    <td width="80">fecha entrega</td>
    <td>cod-subcodigo</td>
    <td>Inventario</td>
    <td>Acta/Anio</td>
    <td>Expte/A&ntilde;o</td>
    <td>Descripcion</td>
    <td>Importe</td>
    <td>N&ordm; Serie</td>
  </tr>

<?php	
		$total_entrega=0;
		$sql_bienes="SELECT FECHAORDENCOMPRA f_entrega,

 CAST(CANTIDAD AS VARCHAR) + '- ' +  DETALLE descripcion

, '' codigo, '' subcodigo, '' inventario, '' n_serie, CANTIDAD * PRECIOUNITARIO  importe,
'' nro_expte, '' anio_expte, '' nro_acta, '' anio_acta
 FROM [dbo].[ACTAS_DIRECTOS] a LEFT JOIN ACTAS_DIRECTOS_DETALLE d on a.ID = d.ID_ACTAS_DIRECTOS
where CUIE = '$cuie'
UNION
		
		
		
		SELECT f_entrega, descripcion, codigo, subcodigo, inventario, n_serie, importe, nro_expte, anio_expte, a.nro_acta, a.anio_acta FROM ACTAS.dbo.ACTAS a LEFT JOIN ACTAS.dbo.ACTAS_BIENES b on a.nro_acta = b.nro_acta AND a.anio_acta = b.anio_acta WHERE f_entrega <= '$fechalimite' and f_entrega >= '$fechainicio' and a.cuie='$cuie'";

		$res_bienes=sqlsrv_query($conn,$sql_bienes);			
		if (isset($res_bienes)){
  while($row=sqlsrv_fetch_array($res_bienes))
	{ $total_entrega=$total_entrega+$row['importe'];
  ?>
  <tr>
  <td width="80"><?php echo $row["f_entrega"]->format('d-m-Y'); ?></td>
    <td align="left"><label for="select2"><?php echo $row["codigo"]; ?>-<?php echo $row["subcodigo"]; ?></label></td>
    <td align="left"><?php echo $row["inventario"]; ?></td>
    <td><?php echo $row['nro_acta']; ?>/<?php echo $row['anio_acta']; ?></td>
    <td><?php echo $row['nro_expte']; ?>/<?php echo $row['anio_expte']; ?></td>
    <td><?php //echo $row["FPractica"]->format('d-m-Y'); ?>
    <?php echo $row['descripcion']; ?></td>
    <td align="right"><?php echo number_format($row['importe'],2, ',', '.') ?></td>
    <td><?php echo $row["n_serie"]; ?></td>
  </tr>
  <?php } ; }; ?>
</table>
</div>
<?php
?>
  <label for="tinventario">
  </label>
<table width="350" border="1" align="center">
    <tr>
      <td width="200"><input type="checkbox" name="mostrar_cesiondesde" value="1" id="mostrar_cesiondesde">
        Total Cedido</td>
      <td><?php echo "$ ".$cesion_desdef; ?></td>
    </tr>
  </table>
<?php // style="display: none;" ?>
  <div id="divcesiondesde" style="display: none;">
    <table width="800" border="1" align="center">
    <tr>
    <td>fecha entrega</td>
    <td>Cedido a</td>
    <td>Importe</td>
    </tr>

<?php	
		$total_entrega=0;
		$sql_cesiondesde="select * FROM dbo.CESIONES WHERE cuie_desde = '$cuie'";

		$res_cesiondesde=sqlsrv_query($conn,$sql_cesiondesde);			
		if (isset($res_cesiondesde)){
  while($row=sqlsrv_fetch_array($res_cesiondesde))
	{ $total_entrega=$total_entrega+$row['importe'];
  ?>
  <tr>
  <td><?php echo $row["fecha"]->format('d-m-Y'); ?></td>
    <td align="left"><?php echo $row["cuie_hasta"]; ?></td>
    <td align="right"><?php echo number_format($row['importe'],2, ',', '.') ?></td>
    </tr>
  <?php } ; }; ?>
</table>
</div>








<table width="350" border="1" align="center">
    <tr>
      <td width="200"><input type="checkbox" name="mostrar_cesionhasta" value="1" id="mostrar_cesionhasta">
        Total Recibido por Cesi&oacute;n</td>
      <td><?php echo "$ ".$cesion_hastaf; ?></td>
    </tr>
  </table>
<?php // style="display: none;" ?>
  <div id="divcesionhasta" style="display: none;">
    <table width="800" border="1" align="center">
    <tr>
    <td>fecha entrega</td>
    <td>Recibido por </td>
    <td>Importe</td>
    </tr>

<?php	
		$total_entrega=0;
		$sql_cesionhasta="select * FROM dbo.CESIONES WHERE cuie_hasta = '$cuie'";

		$res_cesionhasta=sqlsrv_query($conn,$sql_cesionhasta);			
		if (isset($res_cesionhasta)){
  while($row=sqlsrv_fetch_array($res_cesionhasta))
	{ $total_entrega=$total_entrega+$row['importe'];
  ?>
  <tr>
  <td><?php echo $row["fecha"]->format('d-m-Y'); ?></td>
    <td align="left"><?php echo $row["cuie_desde"]; ?></td>
    <td align="right"><?php echo number_format($row['importe'],2, ',', '.') ?></td>
    </tr>
  <?php } ; }; ?>
</table>
</div>











  <?php }/*
  if ((isset($saldo_inicial))and(isset($ordenes1))and(isset($becados1))and(isset($bienes1))){
  
  $total=$saldo_inicial1+$ordenes1-$becados1-$bienes1;
	  echo "Total: ".$total; 
	  }
  */ ?>
   
   
   
<table width="350" border="1" align="center">
  <tr>
    <td width="200"> Saldo Disponible   </td>
    <td><?php 
	
	//$saldodisponible=$saldodisponible-$cesion_desde+$cesion_hasta;	
	//$saldo
	
	echo "$ ".$saldodisponible; ?></td>
    

    
  </tr>
</table>
</form>
<p><?php //echo $consulta_orden; ?></p>
<p><?php //echo $sql_becados;
 ?></p>
<p>&nbsp;<?php echo $sql_bienes; ?></p>
<p><?php echo $fechalimite; ?></p>
<p><br>
</p>
</body>
</html>
