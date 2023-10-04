<?php include ('\conexion.php');
Header('Content-Type: text/html; charset=LATIN1');
////////////
$parametros='';
$consulta='';
$result=sqlsrv_query($conn,"SELECT O.NROORDENPAGO, O.ANIOEXP,O.NROEXPEDIENTE, O.NROFACTURA, O.PERIODO, O.TOTAL, O.FECHAORDENPAGO, O.NROCTADESDE, O.NROCTAPARA from dbo.ORDEN_PAGO O WHERE ANIOEXP > '2014' ORDER BY O.NROORDENPAGO");

if (isset($_POST['tperiododesde'])){ $pdesde=$_POST['tperiododesde']; };
if (isset($_POST['tperiodohasta'])){ $phasta=$_POST['tperiodohasta']; };
////////////////
if ((isset($_POST['consultar']))or(isset($_POST['imprimir']))){

$periododesde=explode("/",($_POST['tperiododesde']));
$periodohasta=explode("/",($_POST['tperiodohasta']));

if (isset($periododesde[1])){ $anioinicio=$periododesde[1]; } else { $anioinicio=0;  }
if (isset($periodohasta[1])){ $aniofin=$periodohasta[1];  } else { $aniofin=0; }

$IMPORTE=0;
$mesdesde=$periododesde[0];
$meshasta=$periodohasta[0];
$diciembre=12;

if (isset($anioinicio)){ $anio=$anioinicio; }
$mes=$mesdesde;
$salida = "";
$salidaarray="";
$periodo = "";
// BUSQUEDA POR AREA

if (($_POST['tarea'])<>''){
	$tarea=$_POST['tarea'];
	
	$area2=" AND AREA = ".$tarea;	
} else { $area2=''; }
// BUSQUEDA POR PERIODO
if (($_POST['tperiododesde']<>'')){
if ($anio==$aniofin){// un mismo año
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
	$salida1="salida1 : ".$salida;
	echo "<br>";
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
			$salidaarray.=$periodo;
			$mes=$mes+1;
			$salida.=",";			
			$salidaarray.=",";
		
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
		$salidaarray.=$periodo;
		$mes=$mes+1;
		$salida.=",";	
		$salidaarray.=",";
	}		
}
$salida=rtrim($salida,",");
$salidaarray=rtrim($salidaarray,",");
}// periodo   

if($_POST['tperiododesde']<>''){  $paramperiodo=" AND F.Periodo IN ($salida)";  } else {  $paramperiodo = "";  }

if (isset($_POST['scuie'])){
		$cuiecompleto=$_POST['scuie'];
		$scuie=explode("-", $cuiecompleto );
		$CUIE=$scuie[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND F.CUIE ='".$CUIE."'";
	} else { 
		$consultacuie=""; 
	}
} 
if (isset($_POST['cbdiagnostico'])){   $cbdiagnostico=$_POST['cbdiagnostico']; }

if (isset($cbdiagnostico)){ $cbdiagnostico = ",P.CodDiagnostico ";} else { $cbdiagnostico = "";}

$array=explode("," , $salidaarray);
//print_r($array);

foreach($array as $i => $value) {
	unset($array[$i]);
	if ($value=='05/2016'){
		$bandera="si";
		
		
		
} else {
	
$bandera = "no";	
	}
}
//print_r($array);

$autorizadoprocesado=" AND Procesado = 'S' and Autorizado = 'S' ";
$parametros=$paramperiodo.$consultacuie.$autorizadoprocesado.$area2;
$parametros=ltrim($parametros, " AND");
if($parametros <> ""){    $parametros=" WHERE ".$parametros;	    } else 
{ $parametros=" WHERE Procesado = 'S' and Autorizado = 'S' "; }
	$consulta="SELECT Distinct E.AREA, F.IdPrestacion,
	F.CodPractica, P.CodPrestacion, P.CodObjeto, P.CodDiagnostico, P.NombrePrestacion,   count (F.CodPractica) as cantidad, sum(cast (F.TotalPedido as numeric)) AS totalpedido 
	from dbo.FACTURACION F LEFT JOIN dbo.PRESTACIONES2022 P ON F.CodPractica = P.CodCompleto
	LEFT JOIN dbo.EFECTORES E ON F.Cuie = E.CUIE
	 $parametros GROUP BY E.AREA, F.IdPrestacion, F.CodPractica, P.CodPrestacion, P.CodObjeto, P.CodDiagnostico, P.NombrePrestacion
	ORDER BY F.CodPractica";
	
	$result=sqlsrv_query($conn,$consulta);
}

/*

antes las tablas Facturación y Prestación se enlazaban por
F.IdPrestacion = P.IDPrestaciones


*/
//ORDER BY F.CodPractica
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>.::Cantidad de Prestaciones ::.</title>
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
    <td width="20%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a></td>
    <td width="60%" align="center"><table width="600" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">Cant Prestaciones Aprobadas</a></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <?php
	/*if ($_POST['tarea']==''){
		$tarea='';
	}*/
	/*
	&amp;area=<?=$tarea?>
	&amp;area=<?=$tarea?>
	*/
	
	?>
    <td width="20%" align="right"><a href="excel/cantprestacionesaprobadas_excel.php?param=<?=$parametros?>&amp;cuie=<?=$cuiecompleto?>">
      <?php if (isset($_POST['consultar'])){?>
      <img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/cantprestacionesaprobadas_excel.php?param=<?=$parametros?>&amp;cuie=<?=$cuiecompleto?>">
      <?php } ?>
    </a></td>
  </tr>
</table>
<center>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="70%" border="1" align="center">
    <tr>
      <td>AREA</td>
      <td colspan="2"><input type="text" name="tarea" id="tarea" value="<?php if (isset($_POST['tarea'])){ echo $_POST['tarea']; }   ?>"/></td>
    </tr>
    <tr> 
      <td>CUIE</td>
      <td colspan="2">
        <label for="selcuie"></label>
        <?php include('comboselec2.php'); ?>
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
  </table>
<p>  
<center>
  <p>
      <input type="submit" name="consultar" id="consultar" value="Consultar" />
      &nbsp;      <br />
  </p></center>
<p><?php if (isset($consulta)){ echo $consulta; } ?>
</p>
<p>&nbsp;</p>
<table width="100%" border="1" class="texto">
  <tr>
    <td width="13%">N</td>
    <td width="13%">id Prestacion</td>
    <td width="13%">Cod Practica</td>
    <td width="6%">Prestacion</td>
    <td width="5%">Objeto</td>
    <td width="5%">Diagnostico</td>
    <td width="57%">Descripcion</td>
    <td width="14%">Cant Practicas Solicitadas</td>
    <td width="14%">Importe Aprobado</td>
  </tr>
    <?php

$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$totalfinal=0;	
			$cant=0;
			$sum=0;
			$num=0;
  while($row=sqlsrv_fetch_array($result))
	{

$codcompleto=$row['CodPractica'];
$codprestacion=substr($row['CodPractica'],0,2);
$codobjeto=substr($row['CodPractica'],2,4);
$coddiagnostico=substr($row['CodPractica'],6,15);

//$consdescrip="select NombrePrestacion, CodDiagnostico from PRESTACIONES where CodCompleto = '$codcompleto'";
$consdescrip="select NombrePrestacion, CodDiagnostico from PRESTACIONES2022 where CodPrestacion = '$codprestacion' and CodObjeto = '$codobjeto'";

//Hablar con Ariel : si cambiar la busqueda por CodCompleto
$resdesc=sqlsrv_query($conn,$consdescrip);
$rowdes=sqlsrv_fetch_array($resdesc);
$descripcion_prestacion=$rowdes["NombrePrestacion"];

$consdiagnostico="select DetalleDiagnostico from DIAGNOSTICO WHERE CodigoDiagnostico = '$coddiagnostico'";
$resconsdiagnostico=sqlsrv_query($conn,$consdiagnostico);
$rowdiag=sqlsrv_fetch_array($resconsdiagnostico);
$diagnostico=$rowdiag["DetalleDiagnostico"];

$consobjeto="select DetalleObjeto FROM OBJETO WHERE CodigoObjeto='$codobjeto'";
$resconsobjeto=sqlsrv_query($conn,$consobjeto);
$rowobj=sqlsrv_fetch_array($resconsobjeto);
$objeto=$rowobj["DetalleObjeto"];

$consprestacion="select Detalle_Prestac FROM PRESTACION WHERE Cod_Prestac = '$codprestacion'";
$resconsprestacion=sqlsrv_query($conn,$consprestacion);
$rowpre=sqlsrv_fetch_array($resconsprestacion);
$prestacion=$rowpre["Detalle_Prestac"];
//////////////////////////////////
// CONSULTA EXTRAER FECHA DE PRACTICA E IDPRESTACION DE TABLA FACTURACION


$consnomenclador=" SELECT * FROM FACTURACION";



//////////////////////////////////
$cant=$cant+$row['cantidad'];
$sum=$sum+$row['totalpedido'];
$num=$num+1;

		if (isset($scuie[1])){	$nomcuie=$scuie[1]; }
  ?>
  <tr>
    <td><?php echo $num; ?></td>
    <td><?php echo $row["IdPrestacion"]; ?></td>
    <td><a title="<?php echo "Diagnostico: ".$diagnostico; ?>"><?php echo $row["CodPractica"]; ?></a></td>
    <td><a title="<?php echo $prestacion; ?>"><?php echo $codprestacion/*$row["CodPrestacion"]*/; ?></a></td>
    <td><a title="<?php echo $objeto; ?>"><?php echo $codobjeto/*$row["CodObjeto"]*/; ?></a></td>
    <td><a title="<?php echo $diagnostico; ?>"><?php echo $coddiagnostico/*$row["CodDiagnostico"]*/; ?></a></td>
    <td align="left"><?php echo $row["NombrePrestacion"]; ?></td>
    <td><?php echo $row["cantidad"]; ?></td>
    <td><?php echo $row["totalpedido"]; ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="7">&nbsp;</td>
    <td><?php echo $cant; ?></td>
    <td><?php echo $sum; ?></td>
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
<p><?php if (isset($consdescrip)){ echo $consdescrip; } ?>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
