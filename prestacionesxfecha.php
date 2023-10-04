<?php include ('\conexion.php');
Header('Content-Type: text/html; charset=utf-8');

$pagado=0;
$solicitado=0;

if(isset($_POST['consultar'])){
	/*
$fecha = date('Y-m-j');
$cincoanios = strtotime ( '-6 year' , strtotime ( $fecha ) ) ;
$cincoanios = date ( 'Y-j-m' , $cincoanios );

$nueveanios = strtotime ( '-9 year' , strtotime ( $fecha ) );
$nueveanios = date ( 'Y-j-m' , $nueveanios );

$diecinueveanios = strtotime ( '-20 year' , strtotime ( $fecha ) );
$diecinueveanios = date ( 'Y-j-m' , $diecinueveanios );

$sesentaycuatroanios = strtotime ( '-65 year' , strtotime ( $fecha ) );
$sesentaycuatroanios = date ( 'Y-j-m' , $sesentaycuatroanios );*/
	
	
		$CUIE="";
	if (isset($_POST['tarea'])){ $area=$_POST['tarea']; }
if(isset($_POST['consultar'])){
	if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND E.CUIE ='".$CUIE."'";
	} else { 
		$CUIE=""; 
	}
} 
}

	if ($CUIE <> ""){ $CUIE = " AND (f.Cuie = '$CUIE') "; } else { $CUIE =''; }
	

	$codigo=$_POST['tcodigo'];
	$cod='';
	if ($codigo<>''){
	$cod=" AND CodPractica LIKE '%$codigo%' ";	
		
	}
		
	$fdesde=$_POST['fdesde'];
	$fdesde=explode("-" , $fdesde);
	$fdesde=trim($fdesde[2])."/".trim($fdesde[1])."/".trim($fdesde[0]);
	
	$fhasta=$_POST['fhasta'];
	$fhasta=explode("-" , $fhasta);
	$fhasta=trim($fhasta[2])."/".trim($fhasta[1])."/".trim($fhasta[0]);
	$grup="";
	$grupo=strtolower($_POST['tgrupo']);
	
	if ($grupo<>""){
		$grup=" AND Categoria = '$grupo' ";

	} else {
		 $grup=" ";
		
	}
	$pagado=$_POST['tpagado'];
	$pag='';
	if ($pagado=="S"){ $pag = " AND Procesado = 'S' AND Autorizado = 'S'"; }
	if ($pagado=="N"){ $pag = " AND (Procesado = 'N' OR Autorizado = 'N' OR Procesado is null OR Autorizado is null)"; }

$conscompleta="SELECT * FROM SUMAR.dbo.FACTURACION f WHERE (FPractica BETWEEN '$fdesde' AND '$fhasta')".$cod.$pag.$grup.$CUIE;
		$result=sqlsrv_query($conn,$conscompleta);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>


<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Prestaciones x Fecha ::.</title>

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
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Home" width="50" height="50" /></a></td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Prestaciones  por Fecha</td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%">&nbsp;</td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="90%" border="1" align="center">
    <tr>
      <td colspan="6" class="encabezado">Buscar por...</td>
    </tr>
<tr>
      <td>Codigo
        <input name="tcodigo" type="text" id="tcodigo" value="<?php if (isset($_POST['tcodigo'])){ echo $_POST['tcodigo']; }?>" size="8" /></td>
      <td>desde
      <input type="date" name="fdesde" id="fdesde" value="<?php if (isset($_POST['fdesde'])){ echo $_POST['fdesde']; } ?>" ></td>
      <td>Hasta
      <input type="date" name="fhasta" id="fhasta" value="<?php if (isset($_POST['fhasta'])){ echo $_POST['fhasta']; } ?>" ></td>
      <td>Grupo
        <select name="tgrupo" id="tgrupo">
        <option value="">Todos</option>
        <option value="A">0-5</option>
        <option value="B">6-9</option>
        <option value="C">10-19</option>
        <option value="D">20-64 Mujeres</option>
        <option value="E">20-64 Hombres</option>
</select></td>
      <td>Pagado      
        <select name="tpagado" id="tpagado">
          <?php if (isset($_POST['cactivo'])){ $conv=$_POST['cactivo']; } else { $conv='TODOS'; } ?>
          <option value="S"<?php if ($conv=="S"){ echo "selected=\"selected\""  ; }; ?>>SI</option>
          <option value="N" <?php if ($conv=="N"){ echo "selected=\"selected\""  ; }; ?>>NO</option>
          <option value="TODOS"<?php if ($conv=="TODOS"){ echo "selected=\"selected\""  ; }; ?>>TODOS</option>
      </select></td>
      
    </tr>
    <tr>
      <td colspan="5">Efector      <?php include('comboselec3.php'); ?></td>
      
    </tr>
  </table>

      <?php if (isset($conscompleta)){ echo $conscompleta; } ?>
      <input type="submit" name="consultar" id="consultar" value="Consultar" />
        &nbsp;</p>
  </center></p>
</form>

<table width="100%" border="1">
  <tr>
    <td>CUIE</td>
    <td>Apellido, Nombre</td>
    <td>DNI</td>
    <td>Sexo</td>
    <td>Practica</td>
    <td>Fecha de Practica</td>
    <td>Precio</td>
    <td>Nro Factura</td>
    <td>Procesado</td>
    <td>Autorizado</td>
    <td>Cod Rechazo</td>
    <td>Grupo</td>
  </tr>

<?php			
		if (isset($_POST['consultar'])){			
		if (isset($result)){
		$totalfinal=0;	
		$totalactivos=0;						
		$totalautorizado=0;
		$pagado=0;
		$solicitado=0;
  while($row=sqlsrv_fetch_array($result))
	{ $totalfinal=$totalfinal+1;
	$solicitado=$solicitado+$row["PrecioPractica"];
	
	if (($row["Autorizado"]=='S')and($row["Procesado"]=='S')){ $totalautorizado=$totalautorizado+1;
	$pagado=$pagado+$row["PrecioPractica"];
		}
  ?>
  <tr>
    <td align="left"><?php echo $row["Cuie"]; ?></td>
    <td align="left"><?php echo utf8_encode($row["Apellido"]); ?>, <?php echo utf8_encode($row["Nombre"]); ?></td>
    <td><?php echo $row['NumDoc']; ?></td>
    <td><?php echo $row['Sexo']; ?></td>
    <td><?php echo $row["CodPractica"]; ?></td>
    <td><?php echo $row["FPractica"]->format('d-m-Y'); ?></td>
    <td align="right"><?php echo $row['PrecioPractica']; ?></td>
    <td><?php echo $row["NumFactura"]; ?></td>
    <td><?php echo $row["Procesado"]; ?></td>
    <td><?php echo $row["Autorizado"]; ?></td>
    <td><?php echo $row["CodRechazoPractica"]; ?></td>
    <td><?php echo $row["Categoria"]; ?></td>
  </tr>
  <?php } ; }; }?>
</table>
<table width="900" border="1">
  <tr>
    <td>Total Solicitado: <?php echo $solicitado; ?>/ Total Pagado: <?php echo $pagado; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>
  <?php if (isset($_POST['consultar'])){ echo "TOTAL: ".$totalfinal."<br>"."TOTAL AUTORIZADOS(Procesados y Autorizados): ".$totalautorizado." fechas: ".$fhasta." - ".$fdesde; }; ?>
  &nbsp;</p>
</body>
</html>
