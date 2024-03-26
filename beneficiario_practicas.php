<?php include ('\conexion.php');
include('\funciones.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////  

$titulo="PRACTICAS POR BENEFICIARIO v2.0";

$consulta='';
$cuie='';

if (isset($_POST['consultar'])){
	
	
if (isset($_POST['tdni'])){
$dni=$_POST['tdni'];
}
if (isset($_POST['tfdesde'])){
$fdesde=$_POST['tfdesde'];
if ($fdesde<>''){
$fdesde=explode("/",$fdesde);
$fdesde=$fdesde[2]."-".$fdesde[1]."-".$fdesde[0];}
}
if (isset($_POST['tfhasta'])){
$fhasta=$_POST['tfhasta'];
if ($fhasta<>''){
$fhasta=explode("/",$fhasta);
$fhasta=$fhasta[2]."-".$fhasta[1]."-".$fhasta[0];
}
if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")and($CUIE<>"")){	
		$cuie=$CUIE;
	} else { 
		$CUIE=""; 
	}
} 
if ($dni <> ""){ $dni = "NumDoc = '$dni' AND ";}
if ($fdesde <> ""){ $fdesde = "Fpractica >= '$fdesde' AND ";}
if ($fhasta <> ""){ $fhasta = "Fpractica <= '$fhasta' AND ";}
if ($cuie <> ""){ $cuie = "F.Cuie = '$cuie' AND ";}


$parametros=" WHERE ".$dni.$fdesde.$fhasta.$cuie;
	if ($parametros == " WHERE "){ $parametros=""; 
	$salida="Debe ingresar algun dato";
	
	} else {
		
		
	$parametros = rtrim($parametros, "AND ");
	$consulta="SELECT F.*, E.NOMBREEFECTOR FROM FACTURACION F LEFT JOIN EFECTORES E ON F.Cuie=E.CUIE $parametros ORDER BY FPractica Desc";
	$salida=$consulta;	
	}
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>.:: <?php ECHO $titulo?> ::.</title>
  <link href="estilos.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <link rel="stylesheet" href="./lib/style.css">
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
	  $( "#datepicker" ).datepicker({dateFormat: "dd/mm/yy"});
  } );
  $( function() {
	  $( "#datepicker2" ).datepicker({dateFormat: "dd/mm/yy"});
  } );
  </script>
<style type="text/css">
body {
	background-color: #D6D6D6;
	text-align: right;
}
</style>
</head>

<body>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a></td>
    <td width="70%" align="center"><table width="700" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag"><?php echo $titulo; ?></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="15%"><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&anio=<?=$anio?>">
      <?php if (isset($_POST['consultar'])){?>
    <img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&anio=<?=$anio?>">
    <?php } ?>
    </a></td>
  </tr>
</table>
<center>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="350" border="1" align="center">
    <tr>
      <td>Dni Beneficiario</td>
      <td><input name="tdni" type="text" id="textfield2" size="8" /></td>
    </tr>
    <tr>
      <td>Fecha Desde</td>
      <td><label for="tfdesde"></label>
      <input name="tfdesde" type="text" id="datepicker" size="8" /></td>
    </tr>
    <tr>
      <td>Fecha Hasta</td>
      <td><input name="tfhasta" type="text" id="datepicker2" size="8" /></td>
    </tr>
    <tr>
      <td width="142">CUIE</td>
      <td width="192"><label for="tfhasta"></label>        <label for="textfield2">
        <?php include('comboselec4.php');  ?>
      </label></td>
    </tr>
  </table>
  <p>
    <center><input type="submit" name="consultar" id="consultar" value="Consultar" />
      &nbsp;<br />
  <?php if (isset($salida)){ echo $salida;} ?></center></p>
</form>
<table width="100%" border="1">
  <tr>
    <td width="42" align="left">CUIE</td>
    <td width="82" align="left">Dni</td>
    <td width="150" align="left">Apellido</td>
    <td width="118" align="left">Nombre</td>
    <td width="112" align="left">Fec Nacimiento</td>
    <td width="90" align="left">Per&iacute;odo</td>
    <td width="111" align="left"><p>Fecha Practica</p></td>
    <td width="71" align="left">Codigo Practica</td>
    <td width="68" align="left">Prestacion</td>
    <td width="37" align="left">Objeto</td>
    <td width="78" align="left">Diagnostico</td>
    <td width="43" align="left">Factura</td>
    <td width="70" align="left"><p>Procesado</p></td>
    <td width="66" align="left">Autorizado</td>
    <td width="29" align="left">Peso</td>
    <td width="27" align="left">Talla</td>
    <td width="17" align="left">T/A</td>
    <td width="21" align="left">IMC</td>
    <td width="55" align="left">Perim Cef</td>
    <td width="61" align="left">Percentilo</td>
  </tr>
    <?php
	
	$totalpagado=0;
		$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$total=0;	
  while($row=sqlsrv_fetch_array($result))
	{

$total++;
	$fpractica=$row['FPractica']->format('d/m/Y');	
      
  ?>
  <tr>
    <td align="left"><a title="<?php echo $nombreefector; ?>"><?php echo $row["Cuie"]; ?>-</a><a title="<?php echo $nombreefector; ?>"><?php echo utf8_encode($row["NOMBREEFECTOR"]); ?></a></td>
    <td width="82"><?php echo $row["NumDoc"]; ?></td>
    <td><?php echo $row["Apellido"]; ?></td>
    <td><?php echo $row["Nombre"]; ?></td>
    <td width="112"><?php echo $row["FNac"]->format('d-m-Y'); ?></td>
    <td><?php echo $row["Periodo"]; ?></td>
    <td width="111" align="right"><?php echo $row["FPractica"]->format('d-m-Y'); ?></td>
    <?php
      $nomencladorSelected=obtenerNomencladorFecha($fpractica,$conn);
      //echo $fpractica."- ".$nomencladorSelected." -<br>";
	$codpractica=$row['CodPractica'];
	$sql_detalle="SELECT NombrePrestacion FROM $nomencladorSelected WHERE CodCompleto='$codpractica'";
      //echo $sql_detalle;
	$res_detalle=sqlsrv_query($conn,$sql_detalle);
	$fila=sqlsrv_fetch_array($res_detalle);
	$detalle=$fila['NombrePrestacion'];
	
	$codigosolo=substr($row["CodPractica"],0,2);
	$sql_prestacion="SELECT * FROM PRESTACION where Cod_Prestac = '$codigosolo'";
	$res_prestacion=sqlsrv_query($conn,$sql_prestacion);
	$prest=sqlsrv_fetch_array($res_prestacion);
	
	$objeto=substr($row["CodPractica"],2,4);
	$sql_objeto="SELECT * FROM OBJETO WHERE CodigoObjeto = '$objeto'";
	$res_objeto=sqlsrv_query($conn,$sql_objeto);
	$fobj=sqlsrv_fetch_array($res_objeto);
	
	
	$diagnostico=trim(substr($row["CodPractica"],6,5));
	$sql_diagnostico="SELECT * FROM DIAGNOSTICO WHERE CodigoDiagnostico = '$diagnostico'";
	$res_diagnostico=sqlsrv_query($conn,$sql_diagnostico);
	$diag=sqlsrv_fetch_array($res_diagnostico);
	
	
	?>
    <td align="right"><a title="<?php echo "Nombre Prestacion: ".$detalle; ?>"><?php echo $row["CodPractica"]; ?></a></td>
    <td align="right"><a title="<?php echo $prest[2]; ?>"><?php echo substr($row["CodPractica"],0,2); ?></a></td>
    <td align="right"><a title="<?php echo "Objeto: ".$fobj["DetalleObjeto"]; ?>"><?php echo substr($row["CodPractica"],2,4); ?></a></td>
    <td align="right"><a title="<?php echo "Diagnostico: ".$diag[2]; ?>"><?php echo substr($row["CodPractica"],6,5); ?></a></td>
    <td align="right"><?php echo $row["NumFactura"]; ?></td>
    <td align="right"><?php echo $row["Procesado"]; ?></td>
    <td align="right"><?php echo $row["Autorizado"]; ?></td>
    <td align="right"><?php echo $row["Peso"]; ?></td>
    <td align="right"><?php echo $row["Talla"]; ?></td>
    <td align="right"><?php echo $row["TA"]; ?></td>
    <td align="right"><?php echo $row["IMC"]; ?></td>
    <td align="right"><?php echo $row["PerimCefal"]; ?></td>
    <td align="right"><?php echo $row["PerIMCEdad"]; ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="6">&nbsp;</td>
    <td align="right">Total: <?php echo $total; ?></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>
</table>
<form id="form2" name="form2" method="post" action="">
<center>
</center>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>