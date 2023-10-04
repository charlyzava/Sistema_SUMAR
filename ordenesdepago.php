<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////
$parametros='';
$consulta='';
$result=sqlsrv_query($conn,"SELECT O.NROORDENPAGO, O.ANIOEXP,O.NROEXPEDIENTE, O.NROFACTURA, O.PERIODO, O.TOTAL, O.FECHAORDENPAGO, O.NROCTADESDE, O.NROCTAPARA, O.DEBITO, O.CREDITO from dbo.ORDEN_PAGO O WHERE ANIOEXP > '2014' ORDER BY O.NROORDENPAGO");

if (isset($_POST['tperiododesde'])){ $pdesde=$_POST['tperiododesde']; };


////////////////

if ((isset($_POST['consultar']))or(isset($_POST['imprimir']))){

$periododesde=explode("/",($_POST['tperiododesde']));
$periodohasta=explode("/",($_POST['tperiodohasta']));
$ordendesde=$_POST['tordendesde'];
$ordenhasta=$_POST['tordenhasta'];

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
	
	$joinordenpago=" AND O.ANIOEXP = E.ANIOEXP ";
	
		if ($expaniodesde==$expaniohasta){ // MISMO AÑO				
		$paramexpediente=" AND O.ANIOEXP = $expaniodesde AND O.NROEXPEDIENTE >= $expdesde AND O.NROEXPEDIENTE <= $exphasta";
		} else {   
		
		$calcularanio=$expaniodesde+1;		
			
		if ($calcularanio==$expaniohasta){		// UN AÑO DE DIFERENCIA	
		$paramexpediente=" AND ((O.ANIOEXP = $expaniodesde and O.NROEXPEDIENTE >= $expdesde) or (O.ANIOEXP = $expaniohasta and O.NROEXPEDIENTE <= $exphasta))";		
		} else {								// VARIOS AÑOS DE DIFERENCIA
			
			$flag=0;
		$aniosentre="";
		$flag=$expaniodesde+1;
		while ($flag<>$expaniohasta){			
			$aniosentre.="'".$flag."',";
			$flag=$flag+1;
		}
			$aniosentre=rtrim($aniosentre,",");			 
			 $paramexpediente=" AND ((O.ANIOEXP = $expaniodesde and O.NROEXPEDIENTE >= $expdesde) or (O.ANIOEXP = $expaniohasta and O.NROEXPEDIENTE <= $exphasta) or O.ANIOEXP IN ($aniosentre)) ";
			 			 
		
		
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
if($_POST['tperiododesde']<>''){  $paramperiodo=" AND O.PERIODO IN ($salida)";  } else {  $paramperiodo = "";  }
if ($_POST['tordendesde']<>''){$paramordenpago=" AND O.NROORDENPAGO >= $ordendesde AND O.NROORDENPAGO <= $ordenhasta";}else{$paramordenpago="";}

if (isset($_POST['scuie'])){
		$scuie=$_POST['scuie'];
		$scuie=explode("-", $scuie );
		$CUIE=$scuie[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND E.CUIE ='".$CUIE."'";
	} else { 
		$consultacuie=""; 
	}
} 

$parametros=$paramordenpago.$paramperiodo.$paramexpediente.$consultacuie;
$parametros=ltrim($parametros, " AND");
if($parametros <> ""){    $parametros=" WHERE ".$parametros;	    }
	$consulta="select * from dbo.ORDENPAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP $parametros ORDER BY O.NROORDENPAGO DESC";
	$result=sqlsrv_query($conn,$consulta);
	

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>.:: Ordenes de Pago ::.</title>
<style type="text/css">
body {
	background-color: #D6D6D6;
}
</style>
</head>

<body>
<table width="100%" border="0">
  <tr>
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a></td>
    <td width="34%" align="center"><img src="imgs/bordenes.gif" width="299" height="50" /></td>
    <td width="33%">&nbsp;</td>
  </tr>
</table>
<center>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="70%" border="1" align="center">
    <tr> 
      <td>CUIE</td>
      <td colspan="2">
        <label for="selcuie"></label>
        <?php  include('comboselec2.php'); ?>
        </td>
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
      <td>Orden de Pago</td>
      <td><label for="tordendesde"></label>
      <input name="tordendesde" type="text" id="tordendesde" value="<?php if (isset($_POST['tordendesde'])){ echo $_POST['tordendesde']; } ?>" size="10" /></td>
      <td><label for="tordenhasta"></label>
      <input name="tordenhasta" type="text" id="tordenhasta" value="<?php if (isset($_POST['tordenhasta'])){ echo $_POST['tordenhasta']; }?>" size="10" /></td>
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
<center>
    <p>
      <input type="submit" name="consultar" id="consultar" value="Consultar" />
      &nbsp;<br />
      <?php // echo "paramtetros:".$parametros." consulta:".$consulta; ?>
      <?php if (isset($scuie[1])){ echo "( ".$scuie[1]." )"; }?> </p>
</center></p>
</form>

<table width="100%" border="1">
  <tr>
    <td width="11%">N&ordm; Orden</td>
    <td width="13%">Expediente</td>
    <td width="14%">N&ordm; Factura</td>
    <td width="15%">Per&iacute;odo</td>
    <td width="16%">Fecha Orden de Pago</td>
    <td width="16%">Fecha Notificacion</td>
    <td width="16%">Fecha D&eacute;bito Bancario</td>
    <td width="20%">Cuenta Corriente Para</td>
    <td width="11%">SubTotal</td>
    <td width="11%">D&eacute;bito</td>
    <td width="11%">Cr&eacute;dito</td>
    <td width="11%">Total</td>
  </tr>
  <?php 
  
  	//****** TOTALES *********/
		$consulta_t="select SUM(TOTAL) AS SUBTOTAL, SUM(DEBITO)AS DEBITO, SUM(CREDITO) AS CREDITO, SUM (CREDITO) AS CREDITO, SUM(TOTAL) - SUM(DEBITO) + SUM(CREDITO) AS TOTAL from dbo.ORDENPAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP $parametros ";
	$result_t=sqlsrv_query($conn,$consulta_t);
  
  
  
  	while($row_t=sqlsrv_fetch_array($result_t))
	{
		
		$subtotal_t=$row_t['SUBTOTAL'];
		$debito_t=$row_t['DEBITO'];
		$credito_t=$row_t['CREDITO'];
		$total_t=$subtotal_t-$debito_t+$credito_t;		
		
	}
  
  
  ?>
  <tr>
    <td align="left" valign="bottom">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
      <td><?php echo $subtotal_t; ?></td>
    <td><?php echo $debito_t; ?></td>
    <td><?php echo $credito_t; ?></td>
    <td><?php echo $total_t; ?></td>
  </tr>
  
  
  
  
    <?php

$total=0;
$debito=0;
$subtotal=0;


$sumasubtotal=0;
$sumadebito=0;
$sumacredito=0;


$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		$subtotal=$row["TOTAL"];
		$sumasubtotal+=$subtotal;
		
		$debito=$row["DEBITO"];
		$sumadebito+=$debito;
		
		$credito=$row["CREDITO"];
		$sumacredito+=$credito;

		$total=$sumasubtotal-$sumadebito+$sumacredito;
		
		
		
		
		
		
		

		
		
		
		
		
		
		
		
		$fecha=$row["FECHAORDENPAGO"];
		$fecha=date("Y-m-d");
		
		if (isset($_POST['scuie'])){
			$scuie=$_POST['scuie'];	
		$scuie=explode("-", $scuie );
		
		
		$nomcuie=$scuie[1];
		 }		
  ?>
  
  <tr>
    <td align="left" valign="bottom"><a href="pdf/ordenesdepago_pdf.php?idordenpago=<?=$row["NROORDENPAGO"]?>" target="_blank"><img src="imgs/pdf-icon-small.png" width="25" height="25" /></a><?php echo $row["NROORDENPAGO"]; ?></td>
    <td><?php echo $row["NROEXPEDIENTE"]."-".$row["ANIOEXP"]; ?></td>
    <td><a href="ordenesdepago_facturacion.php?factura=<?=$row["NROFACTURA"]?>&periodo=<?=$row["PERIODO"];?>&cuie=<?=$CUIE?>&scuie=<?=$nomcuie?>" target="_blank"><img src="imgs/lupa.gif" width="16" height="15" /></a><?php echo $row["NROFACTURA"]; ?></td>
    <td><?php echo $row["PERIODO"]; ?></td>
    <td align="right"><?php if (($row["FECHAORDENPAGO"])<>""){ echo $row["FECHAORDENPAGO"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php if (($row["FECHANOTIFICACION"])<>""){ echo $row["FECHANOTIFICACION"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php if (($row["FECHADEBITOBANCARIO"])<>""){ echo $row["FECHADEBITOBANCARIO"]->format('d-m-Y');?><a href="pdf/notif_ordenpago_pdf.php?idordenpago=<?=$row["NROORDENPAGO"]?>" target="_blank"><img src="imgs/pdf-icon-small.png" width="25" height="25" /></a>       <?php } ?></td>
    <td><?php echo $row[11]; ?></td>
    <td><?php echo $row["TOTAL"]; ?></td>
    <td><?php echo $row["DEBITO"]; ?></td>
    <td><?php echo $row["CREDITO"]; ?></td>
	    <td><?php echo $row["TOTAL"]-$row["DEBITO"]+$row["CREDITO"]; ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="8">&nbsp;</td>
    <td><?php echo $sumasubtotal; ?></td>
    <td><?php echo $sumadebito; ?></td>
    <td><?php echo $sumacredito; ?></td>
    <td><?php echo $total; ?></td>
  </tr><?php } ?>
</table>
<form id="form2" name="form2" method="post" action="">
<center>
</center>
</form>
<form action="ordenesdepago_imprimir.php" method="post" name="form3" target="_blank" id="form3">
<center>
  <input type="submit" name="imprimir" id="imprimir" value="Imprimir" />
  <input name="hresult" type="hidden" id="hresult" value="<?=$consulta?>" />
  <input name="hcuie" type="hidden" id="hcuie" value="<?=$CUIE?>" />
  <input name="hpdesde" type="hidden" id="hpdesde" value="<?=$_POST['tperiododesde']?>" />
  <input name="hphasta" type="hidden" id="hphasta" value="<?=$_POST['tperiodohasta']?>" />
  <input name="hodesde" type="hidden" id="hodesde" value="<?=$_POST['tordendesde']?>" />
  <input name="hohasta" type="hidden" id="hohasta" value="<?=$_POST['tordenhasta']?>" />
  <input name="hedesde" type="hidden" id="hedesde" value="<?=$_POST['texpedientedesde']?>" />
  <input name="hehasta" type="hidden" id="hehasta" value="<?=$_POST['texpedientehasta']?>" />
</center>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
