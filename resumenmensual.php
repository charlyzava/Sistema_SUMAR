<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////

if (isset($_POST['smes'])){
$mes=$_POST['smes'];
}$consulta='';
if (isset($_POST['tanio'])){
$anio=$_POST['tanio'];
}if (isset($_POST['consultar'])){
$consulta="SELECT    EF.NOMBREEFECTOR ,E.CUIE, E.NROEXPEDIENTE, E.ANIOEXP,E.NROFACTURA,
	  E.MONTOTOTALPEDIDO, E.FECHAINGRESO,E.PERIODO, 
	  ULTDIAMES = CASE LEFT(E.PERIODO,2) 
			WHEN '01' THEN '31' 
			WHEN '02' THEN '28'
			WHEN '03' THEN '31'
			WHEN '04' THEN '30'
			WHEN '05' THEN '31'
			WHEN '06' THEN '30'
			WHEN '07' THEN '31'
			WHEN '08' THEN '31'
			WHEN '09' THEN '30'
			WHEN '10' THEN '31'
			WHEN '11' THEN '30'
			WHEN '12' THEN '31'
			END +'/'+ LEFT(E.PERIODO,2)+'/'+ RIGHT(E.PERIODO,4),
	  E.FECHAFACTURA,  L.FECHALIQUIDACION, 
                       O.TOTAL,O.FECHANOTIFICACION, EF.TIPOEFECTOR 
FROM EXPEDIENTES E LEFT JOIN ORDENPAGO O ON E.ANIOEXP=O.ANIOEXP AND E.NROEXPEDIENTE=O.NROEXPEDIENTE 
                 LEFT JOIN LIQUIDACION L ON  E.NROFACTURA=L.NROFACTURA AND E.ANIOEXP=L.ANOEXP AND E.NROEXPEDIENTE=L.NROEXPEDIENTE
                 LEFT JOIN EFECTORES EF ON E.CUIE = EF.CUIE
WHERE    MONTH(E.FECHAINGRESO) = '$mes'
AND YEAR(E.FECHAINGRESO) = '$anio'
ORDER BY E.ANIOEXP,E.NROEXPEDIENTE";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>Resumen Mensual</title>
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
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a></td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Resumen Mensual</td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%"><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&anio=<?=$anio?>">
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
      <td width="142">Mes
        <label for="tanio"></label>
        <label for="smes"></label>
        <select name="smes" id="smes">
          <option value="1">Enero</option>
          <option value="2">Febrero</option>
          <option value="3">Marzo</option>
          <option value="4">Abril</option>
          <option value="5">Mayo</option>
          <option value="6">Junio</option>
          <option value="7">Julio</option>
          <option value="8">Agosto</option>
          <option value="9">Septiembre</option>
          <option value="10">Octubre</option>
          <option value="11">Noviembre</option>
          <option value="12">Diciembre</option>
        </select></td>
      <td width="192">A&ntilde;o
        <label for="textfield2"></label>
      <input name="tanio" type="text" id="textfield2" size="5" /></td>
    </tr>
  </table>
  <p>
    <center><input type="submit" name="consultar" id="consultar" value="Consultar" />
      &nbsp;<br />
  <?php //  echo $consulta; ?></center></p>
</form>
<table width="100%" border="1">
  <tr>
    <td>CUIE</td>
    <td>Nombre Efector</td>
    <td width="13%">Expediente</td>
    <td>N&ordm; Factura</td>
    <td width="14%">Monto Total Pedido</td>
    <td>Fecha Ingreso</td>
    <td>Per&iacute;odo</td>
    <td>Fecha Factura</td>
    <td>Fecha Liquidacion</td>
    <td>Fecha Notificacion</td>
    <td width="11%">Total</td>
  </tr>
    <?php
	
	$totalpagado=0;
		$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{

		
  ?>
  <tr>
    <td><?php echo $row["CUIE"]; ?></a></td>
    <td align="left"><?php echo utf8_encode($row["NOMBREEFECTOR"]); ?></a></td>
    <td><?php echo $row["NROEXPEDIENTE"]."-".$row["ANIOEXP"]; ?></td>
    <td><?php echo $row["NROFACTURA"]; ?></td>
    <td><?php echo $row["MONTOTOTALPEDIDO"]; ?></td>
    <td><?php echo $row["FECHAINGRESO"]->format('d-m-Y'); ?></td>
    <td><?php echo $row["PERIODO"]; ?></td>
    <td align="right"><?php echo $row["FECHAFACTURA"]->format('d-m-Y'); ?></td>
    <td align="right"><?php if (($row["FECHALIQUIDACION"])<>""){echo $row["FECHALIQUIDACION"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php if (($row["FECHANOTIFICACION"])<>""){echo $row["FECHANOTIFICACION"]->format('d-m-Y'); } ?></td>
    <td align="right"><?php echo $row["TOTAL"]; ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="6">&nbsp;</td>
    <td align="right">Total: </td>
    <td align="right">&nbsp; Total: </td>
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