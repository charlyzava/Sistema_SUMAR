<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////
/*//Consulta Ariel con todas las columnas - No lo usé al final
/*select T1.NroCuenta, T1.TERCERADMINISTRADOR, T1.TOTALGRAL, T2.TOTALGRAL AS PAGADO, T3.TOTALGRAL AS SALDO 
FROM
(SELECT         SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0) + COALESCE (dbo.ORDENPAGO.CREDITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] as NroCuenta, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '10/02/2023')
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR) T1
FULL OUTER JOIN 
(SELECT         SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0) + COALESCE (dbo.ORDENPAGO.CREDITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] as NroCuenta, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '10/02/2023') and dbo.ORDENPAGO.FECHANOTIFICACION is not null
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR) T2 ON T1.NroCuenta=T2.NroCuenta
FULL OUTER JOIN 
(SELECT         SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0) + COALESCE (dbo.ORDENPAGO.CREDITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] as NroCuenta, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '10/02/2023') and dbo.ORDENPAGO.FECHANOTIFICACION is null
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR) T3 ON T1.NroCuenta=T3.NroCuenta*/

$parametros='';
$consulta='';



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>.:: Cierres Mensuales ::.</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
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
    <td width="34%" align="center"><table width="550" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">Reporte Cierres Mensuales</a></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%">&nbsp;</td>
  </tr>
</table>
<form action="" method="post" name="form1" target="_self" id="form1">

  <table width="700" border="1" align="center">
    <tr>
      <td> Fecha Transferencia Bancaria      </td>
      <td><select name="todosfecha" id="todosfecha">
        <?php 
			
	$restodos=sqlsrv_query($conn,"SELECT DISTINCT(FECHAORDENPAGO)
 FROM [dbo].[ORDENPAGO] O LEFT JOIN EXPEDIENTES E ON O.NROEXPEDIENTE = E.NROEXPEDIENTE   
ORDER BY FECHAORDENPAGO DESC");



  	while($row_todos=sqlsrv_fetch_array($restodos))
	{	
		?>
        <option value="<?php echo $row_todos['FECHAORDENPAGO']->format('d-m-Y'); ?>"><?php echo $row_todos['FECHAORDENPAGO']->format('d-m-Y'); ?></option>
        <?php } ; ?>
      </select></td>
</tr>
</table>
  <p>
    <center>

    <input type="submit" name="consultar" id="consultar" value="Consultar" />
      &nbsp;<br />
      <?php  echo $consulta; ?>
      <?php  if (isset($scuie[0])){ echo "( ".$scuie[0]." )"; }?> 
      <input name="hcuie" type="hidden" id="hcuie" value="<?php if (isset($scuie[0])){ echo "$scuie[0]"; }?>" />
      <input name="consultah" type="hidden" id="consultah" value="<?php echo $consulta; ?>" />
    <?php  if (isset($_POST['consultah'])){ echo $_POST['consultah']; } ?>
	<?php echo $consulta; ?>
</center></p>
</form>
<center>
<table width="800" border="1">
  <tr>
  	<td width="15%">Nro Cuenta Para</td>
    <td width="32%">TERCER ADMINISTRADOR</td>
    <td width="17%">Total General</td>
    <td width="18%">Saldo</td>
    <td width="18%">Pagado</td>
    
  </tr>
  <?php
  
	if (isset($_POST['consultar'])){
		$fechatodos=$_POST['todosfecha'];
	$consulta=" SELECT DISTINCT(TERCERADMINISTRADOR) FROM EFECTORES WHERE TERCERADMINISTRADOR <> '' ORDER BY TERCERADMINISTRADOR ";
	$result=sqlsrv_query($conn,$consulta);	
	}
  

$total=0;
$debito=0;
$subtotal=0;


$sumasubtotal=0;
$sumadebito=0;
$sumacredito=0;

// VER AQUÍ RESULT = QUE ARRIBA
$result=sqlsrv_query($conn,$consulta);
	if (isset($_POST['consultar'])){
			$totalfinal=0;	
  while($row=sqlsrv_fetch_array($result))
	{	
	
	$nulo="0";
	$no_nulo="0";
	$nro_cuenta="";
	$total="0";
	
	
	$tercer_administrador = $row['TERCERADMINISTRADOR'];
	$nro_cuenta = "";
	
	
	$cons_nulo = "SELECT SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] AS NRO_CUENTA, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '$fechatodos') AND dbo.ORDENPAGO.FECHANOTIFICACION IS NULL AND TERCERADMINISTRADOR = '$tercer_administrador'
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR";
	$res_nulo = sqlsrv_query($conn,$cons_nulo);
	while($row_nulo=sqlsrv_fetch_array($res_nulo)){ 
		$nulo = $row_nulo['TOTALGRAL'];
		$nro_cuenta = $row_nulo['NRO_CUENTA']; }
	$cons_no_nulo = "SELECT SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] AS NRO_CUENTA, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '$fechatodos') AND dbo.ORDENPAGO.FECHANOTIFICACION IS NOT NULL AND TERCERADMINISTRADOR = '$tercer_administrador'
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR";
	$res_no_nulo = sqlsrv_query($conn, $cons_no_nulo);
	while($row_no_nulo=sqlsrv_fetch_array($res_no_nulo)){ 
			$no_nulo = $row_no_nulo['TOTALGRAL']; 
			$nro_cuenta = $row_no_nulo['NRO_CUENTA'];}
	
	
	
	$cons_total= "SELECT SUM(dbo.ORDENPAGO.TOTAL - COALESCE (dbo.ORDENPAGO.DEBITO, 0)) AS TOTALGRAL, 
                         dbo.ORDENPAGO.[NROCtaTran Para] AS NRO_CUENTA, dbo.EFECTORES.TERCERADMINISTRADOR
FROM            dbo.EXPEDIENTES INNER JOIN
                         dbo.EFECTORES ON dbo.EXPEDIENTES.CUIE = dbo.EFECTORES.CUIE INNER JOIN
                         dbo.ORDENPAGO ON dbo.EXPEDIENTES.NROEXPEDIENTE = dbo.ORDENPAGO.NROEXPEDIENTE AND 
                         dbo.EXPEDIENTES.ANIOEXP = dbo.ORDENPAGO.ANIOEXP AND dbo.EXPEDIENTES.NROFACTURA = dbo.ORDENPAGO.NROFACTURA AND 
                         dbo.EXPEDIENTES.FECHAFACTURA = dbo.ORDENPAGO.FECHAFACTURA AND dbo.EXPEDIENTES.PERIODO = dbo.ORDENPAGO.PERIODO
WHERE        (dbo.ORDENPAGO.FECHAORDENPAGO = '$fechatodos') AND TERCERADMINISTRADOR = '$tercer_administrador'
GROUP BY dbo.ORDENPAGO.[NROCtaTran Para], dbo.EFECTORES.TERCERADMINISTRADOR";
	$res_total = sqlsrv_query($conn, $cons_total);
	while($row_total=sqlsrv_fetch_array($res_total)){ 
			$total = $row_total['TOTALGRAL']; 
			$nro_cuenta = $row_total['NRO_CUENTA'];
			}
	
	
  ?>
  <tr>
  	<td width="15%" align="left"><?php echo $nro_cuenta; ?></td>
    <td width="32%" align="left"><?php echo utf8_encode($row["TERCERADMINISTRADOR"]); ?></td>
    <td align="right"><?php echo $total; ?></td>
    <td align="right"><?php echo $no_nulo; ?></td>
    <td align="right"><?php echo $nulo; ?></td>
    
  </tr>
  
    <?php } ; ?>
  <tr>
    <td>&nbsp;</td>
    <td width="32%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr><?php } ?>
</table></center>
<p><?php /* echo "consulta" .$consulta."<br>"."<br>".$cons_nulo;*/ ?>&nbsp;
  
  
</p>
<p>&nbsp;</p>
</body>
</html>
