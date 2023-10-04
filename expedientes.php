<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////
$parametros='';
$consulta='';
$result=sqlsrv_query($conn,"select E.Cuie, O.NROORDENPAGO, O.ANIOEXP,O.NROEXPEDIENTE, O.NROFACTURA, O.PERIODO, O.TOTAL, O.FECHAORDENPAGO, O.NROCTADESDE, O.NROCTAPARA from dbo.ORDEN_PAGO WHERE ANIOEXP > '2017' ORDER BY O.NROORDENPAGO");

if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		if (isset($CUIE[1])){ $nomcuie=$CUIE[1]; }
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND E.CUIE ='".$CUIE."'";
	} else { 
		$consultacuie=""; 
	}
} 

if (isset($_POST['tperiododesde'])){ $pdesde=$_POST['tperiododesde']; };


////////////////

if ((isset($_POST['consultar']))or(isset($_POST['imprimir']))){

$periododesde=explode("/",($_POST['tperiododesde']));
$periodohasta=explode("/",($_POST['tperiodohasta']));
$comprobante=$_POST['tcomprobante'];

if (isset($periododesde[1])){ $anioinicio=$periododesde[1]; } else { $anioinicio=0;  }
if (isset($periodohasta[1])){ $aniofin=$periodohasta[1];  } else { $aniofin=0; }

$IMPORTE=0;
$mesdesde=$periododesde[0];
$meshasta=$periodohasta[0];
$diciembre=12;

if (isset($anioinicio)){ $anio=$anioinicio; }
$mes=$mesdesde;
$salida = "";
$periodo = "";

// BUSQUEDA EXPEDIENTE/AÑO

if ($_POST['texpedientedesde']<>''){
	
	$expedientedesde=explode("/",($_POST['texpedientedesde']));
	$expedientehasta=explode("/",($_POST['texpedientehasta']));
	
	$expaniodesde=$expedientedesde[1];
	$expaniohasta=$expedientehasta[1];
	$expdesde=$expedientedesde[0];
	$exphasta=$expedientehasta[0];
	
	$joinordenpago=" AND E.ANIOEXP = E.ANIOEXP ";
	
		if ($expaniodesde==$expaniohasta){ // MISMO AÑO				
		$paramexpediente=" AND E.ANIOEXP = $expaniodesde AND E.NROEXPEDIENTE >= $expdesde AND E.NROEXPEDIENTE <= $exphasta";
		} else {   
		
		$calcularanio=$expaniodesde+1;		
			
		if ($calcularanio==$expaniohasta){		// UN AÑO DE DIFERENCIA	
		$paramexpediente=" AND ((E.ANIOEXP = $expaniodesde and E.NROEXPEDIENTE >= $expdesde) or (E.ANIOEXP = $expaniohasta and E.NROEXPEDIENTE <= $exphasta))";		
		} else {								// VARIOS AÑOS DE DIFERENCIA
			
			$flag=0;
		$aniosentre="";
		$flag=$expaniodesde+1;
		while ($flag<>$expaniohasta){			
			$aniosentre.="'".$flag."',";
			$flag=$flag+1;
		}
			$aniosentre=rtrim($aniosentre,",");			 
			 $paramexpediente=" AND ((E.ANIOEXP = $expaniodesde and E.NROEXPEDIENTE >= $expdesde) or (E.ANIOEXP = $expaniohasta and E.NROEXPEDIENTE <= $exphasta) or E.ANIOEXP IN ($aniosentre)) ";
			 			 
		
		
		// 										FIN VARIOS AÑOS DE DIFERENCIA
		}// FIN UN AÑO DE DIFERENCIA 		
} 		// FIN MISMO AÑO
		
}

// BUSQUEDA POR PERIODO
if (($_POST['tperiododesde']<>'')){
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
}// periodo   

if($_POST['texpedientedesde']==''){	 $paramexpediente="";   }
if($_POST['tperiododesde']<>''){  $paramperiodo=" AND E.PERIODO IN ($salida)";  } else {  $paramperiodo = "";  }
if ($_POST['tcomprobante']<>''){$paramordenpago=" AND E.NROFACTURA LIKE '%$comprobante%'";}else{$paramordenpago="";}
	
$parametros=$paramordenpago.$paramperiodo.$paramexpediente.$consultacuie;
$parametros=ltrim($parametros, " AND");
if($parametros <> ""){    $parametros=" WHERE ".$parametros;	    }
	$consulta="select E.CUIE, E.NROEXPEDIENTE, E.ANIOEXP, E.FECHAINGRESO, E.NROFACTURA, E.FECHAFACTURA, E.PERIODO, E.MONTOTOTALPEDIDO, O.NROORDENPAGO, O.FECHAORDENPAGO, O.TOTAL, O.FECHADEBITOBANCARIO, O.FECHANOTIFICACION, O.DEBITO, O.CREDITO, O.MOTIVODEBITO from EXPEDIENTES E LEFT JOIN ORDENPAGO O ON E.NROEXPEDIENTE = O.NROEXPEDIENTE AND E.ANIOEXP = O.ANIOEXP $parametros ORDER BY E.ANIOEXP,E.NROEXPEDIENTE";
	$result=sqlsrv_query($conn,$consulta);
}
$consultaexportar="select E.CUIE, E.NROEXPEDIENTE, E.ANIOEXP, E.FECHAINGRESO, E.NROFACTURA, E.FECHAFACTURA, E.PERIODO, E.MONTOTOTALPEDIDO, O.NROORDENPAGO, O.FECHAORDENPAGO, O.TOTAL, O.FECHADEBITOBANCARIO, O.FECHANOTIFICACION from EXPEDIENTES E LEFT JOIN ORDENPAGO O ON E.NROEXPEDIENTE = O.NROEXPEDIENTE AND E.ANIOEXP = O.ANIOEXP $parametros ORDER BY E.ANIOEXP,E.NROEXPEDIENTE";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>.:: Expedientes Consulta ::.</title>
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
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>"><span class="encabezado"><a name="inicio" title="inicio" id="un-nombre2"></a></span></a></td>
    <td width="34%" align="center"><img src="imgs/expedientes.gif" width="600" height="50" /></td>
    <td width="33%"><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>">
      <?php if (isset($_POST['consultar'])){?>
    <img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>">
    <?php } ?>
    </a></td>
  </tr>
</table>
<center>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="70%" border="1" align="center">
    <tr>
      <td align="left">CUIE</td>
      <td colspan="2" align="left"><label for="selcuie"><?php // include ('comboselec2.php'); ?></label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Desde</td>
      <td>Hasta</td>
    </tr>
    <tr>
      <td>Per&iacute;odo</td>
      <td>MM/AAAA
        <input name="tperiododesde" type="text" id="tperiododesde" value="<?php if (isset($_POST['tperiododesde'])){ echo $_POST['tperiododesde']; }?>" size="10" /></td>
      <td>MM/AAAA
        <input name="tperiodohasta" type="text" id="tperiodohasta" value="<?php if (isset($_POST['tperiodohasta'])){ echo $_POST['tperiodohasta']; } ?>" size="10" /></td>
    </tr>
    <tr>
      <td>N&ordm; Comprobante / Factura</td>
      <td colspan="2"><label for="tcomprobante"></label>
      <input name="tcomprobante" type="text" id="tcomprobante" value="<?php if (isset($_POST['tcomprobante'])){ echo $_POST['tcomprobante']; } ?>" size="10" />        <label for="tcomprobantehasta"></label></td>
    </tr>
    <tr>
      <td>Expediente/A&Ntilde;O</td>
      <td><label for="texpedientedesde"></label>
      <input name="texpedientedesde" type="text" id="texpedientedesde" value="<?php if (isset($_POST['texpedientedesde'])){ echo $_POST['texpedientedesde']; } ?>" />
      <label for="taniodesde"></label></td>
      <td><label for="texpedientehasta"></label>
      <input name="texpedientehasta" type="text" id="texpedientehasta" value="<?php if (isset($_POST['texpedientehasta'])){ echo $_POST['texpedientehasta'] ; } ?>" />
      <label for="taniohasta"></label></td>
    </tr>
  </table>
<p>
    <center><input type="submit" name="consultar" id="consultar" value="Consultar" />
  &nbsp;<a href="#fin">Ir abajo</a><br />
    <?php //  echo $consulta; ?></center></p>
</form>

<table width="100%" border="1">
  <tr>
    <td>CUIE</td>
    <td width="13%">Expediente</td>
    <td>N&ordm; Factura</td>
    <td width="14%">Fecha Ingreso</td>
    <td>Fecha Factura</td>
    <td>Per&iacute;odo</td>
    <td>Importe Facturado</td>
    <td>Importe Aprobado</td>
    <td>Debito</td>
    <td>Credito</td>
    <td>Total a Pagar</td>
    <td>N&ordm; Orden de Pago</td>
    <td width="11%">Fecha de Notificacion</td>
    <td width="11%">Fecha Orden de Pago</td>
  </tr>
    <?php
	$totalpagado=0;
		$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		$cuiecons=$row['CUIE'];
		$conscuie=sqlsrv_query($conn,"SELECT NOMBREEFECTOR FROM EFECTORES WHERE CUIE='$cuiecons'");
		$rowcuie=sqlsrv_fetch_array($conscuie);
		$nombreefector=$rowcuie['NOMBREEFECTOR'];

		$total=$row["MONTOTOTALPEDIDO"];
		$totalfinal=$totalfinal+$total;
		$totalpag=$row["TOTAL"];
		$totalpagado=$totalpagado+$totalpag;
		$fechaingreso=$row["FECHAINGRESO"];
		$fechafactura=$row["FECHAFACTURA"];
		$fecha=date("Y-m-d");
  ?>
  <tr>
    <td><a title="<?php echo $nombreefector; ?>"><?php echo $row["CUIE"]; ?></a></td>
    <td><a href="pdf/liquidacion_pdf.php?nroexpediente=<?=$row["NROEXPEDIENTE"]?>&anio=<?=$row["ANIOEXP"]?>" target="_blank"><img src="imgs/pdf-icon-small.png" alt="Liquidacion" width="25" height="25" /></a><?php echo $row["NROEXPEDIENTE"]."-".$row["ANIOEXP"]; ?></td>
    <td><?php echo $row["NROFACTURA"]; ?><a href="expedientes_facturacion.php?factura=<?=$row["NROFACTURA"]?>" target="_blank"><img src="imgs/lupa.gif" alt="Detalle" width="16" height="15" /></a></td>
    <td><?php echo $row["FECHAINGRESO"]->format('d-m-Y'); ?></td>
    <td><?php if (($row["FECHAFACTURA"])<>""){echo $row["FECHAFACTURA"]->format('d-m-Y'); } ?></td>
    <td><?php echo $row["PERIODO"]; ?></td>
    <td align="right"><?php echo $row["MONTOTOTALPEDIDO"]; ?></td>
    <td align="right"><?php echo $row["TOTAL"]; ?></td>
    <td align="right"><?php echo $row["DEBITO"]; ?></td>
    <td align="right"><?php echo $row["CREDITO"]; ?></td>
    <td align="right"><?php echo $row["TOTAL"]-$row["DEBITO"]; ?></td>
    <td align="right"><?php echo $row["NROORDENPAGO"]; ?></td>
    <td align="right"><?php if (($row["FECHANOTIFICACION"])<>""){echo $row["FECHANOTIFICACION"]->format('d-m-Y');} ?></td>
    <td align="right"><?php if (($row["FECHAORDENPAGO"])<>""){echo $row["FECHAORDENPAGO"]->format('d-m-Y');} ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="6"><a name="fin" title="fin" id="un-nombre"></a></td>
    <td align="right">Total: <?php echo $totalfinal; ?></td>
    <td align="right">&nbsp; Total: <?php echo $totalpagado; ?></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
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
<form action="expedientes_imprimir.php" method="post" name="form3" target="_blank" id="form3">
<center>
  <input type="submit" name="imprimir" id="imprimir" value="Imprimir" />
  <input name="hresult" type="hidden" id="hresult" value="<?=$consulta?>" />
  <input name="hcuie" type="hidden" id="hcuie" value="<?=$CUIE?>" />
  <input name="hpdesde" type="hidden" id="hpdesde" value="<?=$_POST['tperiododesde']?>" />
  <input name="hphasta" type="hidden" id="hphasta" value="<?=$_POST['tperiodohasta']?>" />
  <input name="hcomprobante" type="hidden" id="hcomprobante" value="<?=$_POST['tcomprobante']?>" />
  <input name="hedesde" type="hidden" id="hedesde" value="<?=$_POST['texpedientedesde']?>" />
  <input name="hehasta" type="hidden" id="hehasta" value="<?=$_POST['texpedientehasta']?>" />
  <a href="#inicio">Ir arriba</a>
</center>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>