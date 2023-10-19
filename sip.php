<?php include ('\conexion.php');
Header('Content-Type: text/html; charset=LATIN1');
$cuie='';

set_time_limit(600000000);
ini_set('memory_limit', '1024M');

$opciones="";
if(isset($_POST['consultar'])){
	$fpdesde=$_POST['fpdesde'];
	$fphasta=$_POST['fphasta'];

	
	if (($fpdesde <> "") and ($fphasta <> "")){ $parametros = " WHERE VAR_0284 >= '$fpdesde' AND VAR_0284 <= '$fphasta' ";}
}

if (isset($_POST['bcompletarcuie'])){
//reemplazar id por CUIE	
/*
04900797 --> Maternidad
9100039 --> Santa María
2100025 --> Andalgalá
3500019 --> Belén
10500148 --> Tinogasta

*/

$mod_seleccionado="UPDATE inscripcion.dbo.nivel_N1 SET VAR_0018 = 'K90844' WHERE VAR_0018 LIKE '%4900797%' ";
$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);
			
$mod_seleccionado="UPDATE inscripcion.dbo.nivel_N1 SET VAR_0018 = 'K04890' WHERE VAR_0018 LIKE '%9100039%' ";
$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);		

$mod_seleccionado="UPDATE inscripcion.dbo.nivel_N1 SET VAR_0018 = 'K04797' WHERE VAR_0018 LIKE '%2100025%' ";
$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);

$mod_seleccionado="UPDATE inscripcion.dbo.nivel_N1 SET VAR_0018 = 'K04848' WHERE VAR_0018 LIKE '%3500019%' ";
$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);

$mod_seleccionado="UPDATE inscripcion.dbo.nivel_N1 SET VAR_0018 = 'K04820' WHERE VAR_0018 LIKE '%10500148%' ";
$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);


}
if (isset($_POST['beliminarnonumero'])){

}
	
	/*$orden=$_POST['orden'];
	
	switch ($orden) {
    case "apenom":
        $orden = "afiApellido, afiNombre";
        break;
    case "dnibenef":
        $orden = "afiDNI";
        break;
    case "apenommadre":
        $orden = "maApellido, maNombre";
        break;
    case "dnimadre":
        $orden = "maNroDocumento";
        break;
}*/

		/*
if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")and($CUIE<>"")){	
		$cuie=" e.CUIE ='".$CUIE."'";
	} else { 
		$CUIE=""; 
	}
} 
*/		
	//$parametros=" WHERE ".$clavebeneficiario.$apellido.$nombre.$dni.$fn.$sexo.$grupo.$activo.$departamento.$localidad.$area.$indigena.$ceb.$embarazoactual.$cuie.$codbaja;
	//if ($parametros == " WHERE "){ $parametros=""; }
	//$parametros = rtrim($parametros, "AND ");
	/*if (isset($_POST['orden'])){	$orden = " ORDER BY $orden "; 	} else { $orden = "ORDER BY maApellido, maNombre"; }
	$consultaexportar="select * from NACER_NACION.dbo.SMIAfiliados s left join SUMAR.dbo.EFECTORES e on s.CUIELugarAtencionHabitual = e.CUIE COLLATE Modern_Spanish_ci_as $parametros $orden";
	$result=sqlsrv_query($conn,$consultaexportar);
	
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Beneficiarios Consulta ::.</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <link rel="stylesheet" href="./lib/style.css">
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
<style type="text/css">
body {
	background-color: #D6D6D6;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>

<table width="100%" border="0">
  <tr>
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Home" width="50" height="50" /></a><a name="inicio" id="inicio"></a></td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">SIP - Parto</a></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%" align="right"><a href="excel/beneficiarioconsulta_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
      <?php  /* if (isset($_POST['consultar'])){?>
    </a><a href="excel/beneficiarioconsulta_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/beneficiarioconsultah_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/caps.gif" alt="Generar Archivo de Excel" width="64" height="49" /></a><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
    <?php } */?>
    </a></td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
<center>
  </center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="40%" border="1" align="center">
      <tr>
        <td class="encabezado">Buscar por...<a name="inicio" title="inicio" id="un-nombre2"></a></td>
      </tr>
      <tr>
        <td>Fecha de Parto</td>
      </tr>
      <tr>
        <td>Desde(dd-mm.aaaa)
          <input type="text" name="fpdesde" id="fpdesde" /> 
          Hasta 
          <input type="text" name="fphasta" id="fphasta" /></td>
      </tr>
  </table>
    <p>Ordenado Por
      <label for="orden"></label>
      <select name="orden" id="orden">
        <option value="apenom" selected="selected">Beneficiario</option>
        <option value="dnibenef">DNi Beneficiario</option>
        <option value="apenommadre">Madre</option>
        <option value="dnimadre">DNI Madre</option>
      </select>
  </p>
    <center>
      <p>
        <input type="submit" name="consultar" id="consultar" value="Consultar" />
        &nbsp;&nbsp;<a href="#fin">Ir abajo</a>
        <input type="submit" name="bcompletarcuie" id="bcompletarcuie" value="Reemplazar id Centro de Salud por CUIE" />
        <input type="submit" name="beliminarnonumero" id="beliminarnonumero" value="Eliminar registro con documento &lt;&gt; numero" />
        <br/>
        <?php if (isset($_POST['consultar'])){ //echo $consultaexportar; 
		
		$consultaexportar="SELECT DISTINCT
VAR_0019	'NumeroDoc',
VAR_0002	Apellido,
VAR_0001	Nombre,
VAR_0006	'Fecha de nacimiento madre',
VAR_0284	'Fecha de nacimiento',
VAR_0198	'Edad gestacional al parto'
 FROM [inscripcion].[dbo].[nivel_N1] ".$parametros;
	$result=sqlsrv_query($conn,$consultaexportar);
		 ?>
        &nbsp;
        <?php //echo $parametros; ?>
      </p>
    </center>
</form>
  <table width="100%" border="1">
    <tr>
      <td>CUIE</td>
      <td>Clave Beneficiario</td>
      <td>TipDoc</td>
      <td>NroDoc</td>
      <td>Apellido</td>
      <td>Nombre</td>
      <td>Fecha Nacimiento</td>
      <td>Sexo</td>
      <td>Parto/Serologia/Control</td>
      <td>Fecha de Parto</td>
      <td>Edad Gestacional</td>
      <td>Presion Arterial</td>
      <td>Fuente</td>
    </tr>
    <?php

		if (isset($result)){
  while($row=sqlsrv_fetch_array($result))
	{ 
	
  ?>
    <tr>
      <td>K90844</td>
      <td>&nbsp;</td>
      <td align="left">DNI</td>
      <td align="left"><?php echo $row['NumeroDoc']; ?></td>
      <td align="left"><?php echo $row["Apellido"]; ?></td>
      <td align="left"><?php echo $row["Nombre"]; ?></td>
      <td><?php if (($row["Fecha de nacimiento madre"])<> ""){  echo $row["Fecha de nacimiento madre"]->format('d-m-Y'); } ?></td>
      <td>F</td>
      <td>P</td>
      <td align="left"><?php echo $row["Fecha de nacimiento"]->format('d-m-Y'); ?></td>
      <td><?php echo $row["Edad gestacional al parto"]; ?></td>
      <td>&nbsp;</td>
      <td>4</td>
    </tr>
    <?php } ; }?>
  </table>
  <center>
    <p><?php 
if (isset($consultaexportar)){ echo $consultaexportar; }
}; ?></p>
  </center>
  <p><a href="#inicio">Ir arriba</a></p>
  <p>
    <?php if (isset($query_os)){echo $query_os; } ?>
    &nbsp;</p>
<p></body>
</html>
<?php sqlsrv_close( $conn ); ?>