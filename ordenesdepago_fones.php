<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////
$parametros='';
$consulta='';
$result=sqlsrv_query($conn,"select (IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL) AS IMPORTE_PEDIDO 
,DEBITO,(IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL)-DEBITO as IMPORTE_TOTAL ,
NROORDENPAGO, CUIE, FECHAORDENPAGO, FECHALIQUIDACION, PERIODO, FECHANOTIFICACION, FECHADEBITOBANCARIO, DIASSALACOMUN, DIASUCISINARM, DIASUCICONARM

 from ORDENPAGO_FONES");

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


if($_POST['tperiododesde']<>''){  $paramperiodo="  AND PERIODO IN ($salida)";  } else {  $paramperiodo = "";  }
if ($_POST['tordendesde']<>''){ $paramordenpago="  AND  NROORDENPAGO >= $ordendesde AND NROORDENPAGO <= $ordenhasta";}else{$paramordenpago="";}

if (isset($_POST['scuie'])){
		$scuie=$_POST['scuie'];
		$scuie=explode("-", $scuie );
		$CUIE=$scuie[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND CUIE ='".$CUIE."'";
	} else { 
		$consultacuie=""; 
	}
} 

$parametros=$paramperiodo.$paramordenpago.$consultacuie;
$parametros=ltrim($parametros, " AND");
if($parametros <> ""){    $parametros=" WHERE ".$parametros;	    }
	$consulta="select (IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL) AS IMPORTE_PEDIDO 
,DEBITO,(IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL)-DEBITO as IMPORTE_TOTAL ,
NROORDENPAGO, CUIE, FECHAORDENPAGO, FECHALIQUIDACION, PERIODO, FECHANOTIFICACION, FECHADEBITOBANCARIO, DIASSALACOMUN, DIASUCISINARM, DIASUCICONARM

 from ORDENPAGO_FONES $parametros";
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
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0">
  <tr>
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Home" width="50" height="50" /></a><a name="inicio" id="inicio"></a></td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">Orden de Pago FONES</a></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%" align="right"><a href="excel/beneficiarioconsulta_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
      <?php if (isset($_POST['consultar'])){?>
      </a><a href="excel/beneficiarioconsulta_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/beneficiarioconsultah_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/caps.gif" alt="Generar Archivo de Excel" width="64" height="49" /></a><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
        <?php } ?>
      </a></td>
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
    <td>N&ordm; Orden</td>
    <td>CUIE</td>
    <td>FECHAORDENPAGO</td>
    <td>FECHALIQUIDACION</td>
    <td>PERIODO</td>
    <td>FECHANOTIFICACION</td>
    <td>FECHADEBITOBANCARIO</td>
    <td>DIASSALACOMUN</td>
    <td>DIASUCISINARM</td>
    <td>DIASUCICONARM</td>
    <td>SUBTOTAL</td>
    <td>DEBITO</td>
    <td>TOTAL</td>
    
  </tr>
  <?php 
  
  	//****** TOTALES *********/
		$consulta_t="select (IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL) AS IMPORTE_PEDIDO 
,DEBITO,(IMPORTESALACOMUN*DIASSALACOMUN)+(IMPORTEUCISINARM*DIASUCISINARM)+(IMPORTEUCICONARM*DIASUCICONARM)+(IMPORTEREEMPLAZORENAL*SESIONESREEMPLAZORENAL)-DEBITO as IMPORTE_TOTAL ,
NROORDENPAGO, CUIE, FECHAORDENPAGO, FECHALIQUIDACION, PERIODO, FECHANOTIFICACION, FECHADEBITOBANCARIO, DIASSALACOMUN, DIASUCISINARM, DIASUCICONARM

 from ORDENPAGO_FONES  $parametros";
		
	$result_t=sqlsrv_query($conn,$consulta_t);
  
  
  
  	while($row_t=sqlsrv_fetch_array($result_t))
	{
  ?>
  <tr>
  	<td><?php echo $row_t['NROORDENPAGO'] ?></td>
    <td><?php echo $row_t['CUIE']; ?></td>
    <td><?php if ($row_t["FECHAORDENPAGO"]<>""){ echo $row_t["FECHAORDENPAGO"]->format('d-m-Y');} ?></td>
    <td><?php if ($row_t["FECHALIQUIDACION"]<>""){echo $row_t["FECHALIQUIDACION"]->format('d-m-Y');} ?></td>
    <td><?php echo $row_t['PERIODO'] ;?></td>
    <td><?php if ($row_t["FECHANOTIFICACION"]<>""){echo $row_t["FECHANOTIFICACION"]->format('d-m-Y') ;} ?></td>
    <td><?php if ($row_t["FECHADEBITOBANCARIO"]<>""){echo $row_t["FECHADEBITOBANCARIO"]->format('d-m-Y') ;} ?></td>
    <td><?php echo $row_t['DIASSALACOMUN'] ?></td>
    <td><?php echo $row_t['DIASUCISINARM'] ?></td>
    <td><?php echo $row_t['DIASUCICONARM'] ?></td>
    <td><?php echo $row_t['IMPORTE_PEDIDO'] ?></td>
    <td><?php echo $row_t['DEBITO'] ?></td>
    <td><?php echo $row_t['IMPORTE_TOTAL'] ?></td>
  </tr>
  
  
    
  </tr><?php  }; ?>
</table>
<?php echo $consulta; ?>
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
