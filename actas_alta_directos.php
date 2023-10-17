<?php 

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
$usuario=$_SESSION['username'];

include ('conexion.php');


$tablaprincipal="ACTAS_DIRECTOS_new";
$tablasecundaria="ACTAS_DIRECTOS_DETALLE_new";

	if (isset($_POST['scuie'])){
		$cuie=$_POST['scuie'];
		$cuie=explode("-", $cuie );
		$cuiecorto=$cuie[0];
	} 

} else {
   echo "Esta pagina es solo para usuarios registrados.<br>";
   echo "<br><a href='./phplogin/login.html'>Login</a>";

exit;
}

Header('Content-Type: text/html; charset=LATIN1');
$responsable="";
$fentrega="";
$acta="";
$anioentrega="";
$estado="";
$salida='';
$cuie='';
$encontro='0';
$grabado='0';
$delete='si';
	
if (isset($_POST['bmod_sel'])){	

		$conscompleta="SELECT * FROM ACTAS WHERE nro_acta = '$acta' and anio_entrega = '$anioentrega' ";
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_entrega'];
			$responsable=$res['responsable'];
			$usuario=$res['usuario'];
		}


	$estado='abierta';



	if (isset($_POST['rbbien'])){
		$idbienm=$_POST['rbbien'];
		$impbienm=$_POST["imp".$idbienm];
		$desbienm=$_POST["des".$idbienm];
		$invbienm=$_POST["inv".$idbienm];
		
		// agregue ahora
			//$delete='si';
			//$estado='modificada';
			$mod_seleccionado="UPDATE ACTAS_BIENES SET descripcion='$desbienm', importe = '$impbienm', inventario = '$invbienm' WHERE ID = '$idbienm' ";
			$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);
			$salida="Bien Modificado";
	} else {
		$salida="Debe seleccionar el registro para modificar";
	}
}

// eliminar bien	
if (isset($_POST['eliminar'])){
	$delete='si';
	$estado="abierta";
	
	/*$conscompleta="SELECT * FROM ACTAS_DIRECTOS WHERE nro_acta = '$acta' and anio_entrega = '$anioentrega' ";
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_entrega'];
			$responsable=$res['responsable'];
			$usuario=$res['usuario'];
		}*/
		
	if (isset($_POST['rbbien'])){
	$deletebien=$_POST['rbbien'];	
	$sql_del_bien="DELETE FROM $tablasecundaria WHERE ID='$deletebien'";
	$res_del_bien=sqlsrv_query($conn,$sql_del_bien);
	$salida="Se elimino el item ".$deletebien." ".$sql_del_bien;
	} else {
		$salida="Seleccione un registro a eliminar";
		
	}
}
	
if (isset($_POST['bagregar'])){
	
	//AGREGAR IDBIEN AUTOINCREMENTAL//////
	$sql_max= "SELECT MAX(ID) as maximo FROM $tablasecundaria";
	$res_max=sqlsrv_query($conn,$sql_max);
	$resultado=sqlsrv_fetch_array($res_max);
	$maximo=$resultado['maximo'];
	$idbien=$maximo+1;

	$compro=$_POST['tcomprobante'];
	
	if ($compro==''){
		$comprobante=$_POST['scomprobante']; } else { $comprobante=$_POST['tcomprobante']; }
	
	$cantidad=$_POST['tcantidad'];
	$detalle=strtoupper($_POST['tdetalle']);
	$preciounitario=$_POST['tpreciounitario'];

	$insert_acta="INSERT INTO $tablasecundaria (ID,COMPROBANTE,CUIE,CANTIDAD,DETALLE,PRECIOUNITARIO,usuario) VALUES ('$idbien','$comprobante', '$cuiecorto','$cantidad','$detalle','$preciounitario','$usuario')";
	$result=sqlsrv_query($conn,$insert_acta);
	$salida=$insert_acta;
	if ($result){
		$result="Se grabo el item correctamente ".$result;

	} else {
		$result="Ocurri� un error".$result;
	}
	
}

if (isset($_POST['bgrabar'])){
	
	$comprobantenuevo=strtoupper($_POST['tcomprobante']);
	$fordencompra=$_POST['tfechaordencompra'];
	$faprobacion=$_POST['tfechaaprobacion'];
	
	if (($comprobantenuevo=='')or($fordencompra=='') or ($faprobacion=='')){
		$salida="Debe completar los datos para grabar"; } else {	
	
	$cons_acta="SELECT count(*) as contador FROM $tablaprincipal WHERE COMPROBANTE = '$comprobantenuevo' ";
	$result=sqlsrv_query($conn,$cons_acta);
	while ($fila=sqlsrv_fetch_array($result)){
		$cont=$fila['contador'];
		}
		if ($cont>=1){ $salida="Error, ya existe el COMPROBANTE"; } else {
	
	$fecha="";
	$hora="";
	$ip="";
	
	$sql_insert="INSERT INTO $tablaprincipal (COMPROBANTE,FECHAORDENCOMPRA,FECHAAPROBACION,usuario) 
									VALUES ('$comprobantenuevo','$fordencompra','$faprobacion','$usuario')";
$result=sqlsrv_query($conn,$sql_insert);
if ($result){
	$salida="Se grabo correctamente";
	$estado="grabado";
	$grabado='si';
} else {
	$salida="No se pudo grabar".$sql_insert;
	$estado='inexistente';
	$grabado='no';
}
		}
		
		}
		
		
		
}


//------------ BUSCAR ------------------

if(isset($_POST['buscar'])){
	$cont=0;
	

	if (($acta=='')or($anioentrega=='')){
		$salida='Complete Acta y Anio de Entrega';
		} else {


			if (!is_numeric($acta)){

				$salida='S�lo se permiten n�meros';

			} else {


	
	if (isset($fdesde)){
	$fdesde=$_POST['from'];
	$fdesde=explode("/" , $fdesde);
	$fdesde=$fdesde[2]."-".$fdesde[1]."-".$fdesde[0];
	}

$conscompleta="SELECT * FROM ACTAS WHERE nro_acta = '$acta' and anio_entrega = '$anioentrega' ";
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_entrega'];
			$responsable=$res['responsable'];
			$usuario=$res['usuario'];
			$estado=$res['estado'];
			
		$cont=$cont+1;
		}
		if ($cont>0){
			if ($estado=="cerrada"){
				
				$estado="cerrada";
				$salida="Acta Cerrada";
			} else {
				$estado="abierta";
				$salida="Acta Abierta";
			}
		} else {
			$estado="inexistente";
			$salida="No existe";
		}
		} // complete los datos de alta y anio entrega
	}// is not numeric
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>
<head>
<meta charset="LATIN1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Alta de Actas Directos ::.</title>
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
 

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Administraci&oacute;n de Altas ::.</title>

<style type="text/css">
select#combobox {
    max-width: 180px;
    min-width: 180px;
    width: 180px !important;
}
body {
	background-color: #D6D6D6;
}
CÃƒfÃ†'Ãƒ,Ã‚Â³digo: Seleccionar todo 
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
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Actas Directos</td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%" height="58"><center>
      <a href="phplogin/logout.php"><img src="imgs/cerrar_sesion.png" width="132" height="33"></a>
    </center></td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
<table width="400" border="1" align="center">
    
  <tr>
    <td colspan="2">Comprobante</td>
    </tr>
  <tr>
    <td rowspan="4">Agregar</td>
    <td>Comprobante
      <input type="text" name="tcomprobante" id="tcomprobante"></td>
  </tr>
  <tr>
    <td>Fecha Orden Compra
      <input type="date" name="tfechaordencompra" id="tfechaordencompra"></td>
  </tr>
  <tr>
    <td>Fecha aprobaci&oacute;n
      <input type="date" name="tfechaaprobacion" id="tfechaaprobacion"></td>
  </tr>
  <tr>
    <td><input type="submit" name="bgrabar" id="bgrabar" value="Grabar"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">o</td>
    </tr>
  <tr>
    <td>Seleccionar</td>
    
      <td><select name="scomprobante" id="scomprobante">

<?php 
 $rescuie=sqlsrv_query($conn,"select COMPROBANTE from $tablaprincipal");
while($row=sqlsrv_fetch_array($rescuie)){
?>      
        <option value="<?php echo $row["COMPROBANTE"]; ?>" <?php if (isset($_POST['scomprobante'])){ if (($_POST['scomprobante'])==($row["COMPROBANTE"])){  echo 'selected="selected"';     }  } ?> ><?php echo $row["COMPROBANTE"]; ?></option>
<?php } ?>
      </select>
      <input type="submit" name="bfiltrar" id="bfiltrar" value="Filtrar"></td>
  </tr>
</table>
<p class="mensajerojo">
 <span name="jugador"> <?php  echo $salida;  ?></span></p>
  <center></center>
  <p>
    <label for="scodigo"></label>
    &nbsp;
    <?php if ((isset($_POST['bgrabar'])) or (isset($_POST['bfiltrar'])) or (isset($_POST['bagregar'])) ){ ?>
    <label for=" &quot;imp&quot;.$num ; ?&gt;"></label>
  </p>
  <table width="1100" border="1" align="center">
    <tr>
      <td>Cuie (filtrar por los directos)</td>
            <td>Cantidad</td>
            <td>Detalle</td>
            <td>Precio Unitario</td>
            <td rowspan="2" align="center" valign="middle"><input type="submit" name="bagregar" id="bagregar" value="Agregar"></td>
    </tr>
          <tr>
            <td><?php include('comboselec_municipales.php');  ?></td>
            <td><label for="tacta2"></label>
              <input name="tcantidad" type="number" id="tcantidad" size="4" maxlength="10"></td>
            <td><label for=" &quot;imp&quot;.$num ; ?&gt;"></label>
            <input name="tdetalle" type="text" id="tdetalle" value="<?php if(isset($_POST['bagregar'])){ echo $_POST['tdetalle']; } ?>" size="40" maxlength="200"></td>
            <td><label for="textfield10"></label>
              <input name="tpreciounitario" type="text" id="tpreciounitario" value="<?php if (isset($_POST['bagregar'])){$_POST['tpreciounitario']; }?>" size="6" maxlength="15"></td>
          </tr>
  </table>
        <p>
          <label for="tcantidad">
            <?php }
 ?>
            <br>
          </label>
  </p>
  <table width="1000" border="1" align="center">
    <tr>
    <td>id bien</td>
    <td>Cuie</td>
    <td width="20">Cantidad</td>
    <td>Detalle</td>
    <td>Precio Unitario</td>
    <td>Total</td>
  </tr>

<?php			
		if (isset($_POST['scomprobante'])){ $comprobante=$_POST['scomprobante'];
		$sql_bienes="SELECT A.*, E.NOMBREEFECTOR FROM $tablasecundaria A LEFT JOIN EFECTORES E ON A.CUIE = E.CUIE WHERE COMPROBANTE = '$comprobante'";
		$res_bienes=sqlsrv_query($conn,$sql_bienes);			
		if (isset($res_bienes)){
  while($row=sqlsrv_fetch_array($res_bienes))
	{ 
  ?>
  <tr>
    <td align="left"><input type="radio" name="rbbien" id="rbbien" value="<?php echo $row["ID"]; ?>">
      <label for="rbbien"></label>      <?php echo $row["ID"]; ?></td>
    <td align="left"><input name="tcuie" type="text" id="tcuie" value="<?php echo $row['CUIE']."-".$row['NOMBREEFECTOR']; ?>" size="80"></td>
    <td width="20" align="left"><input name="<?php echo "inv".$row["ID"] ; ?>" type="text" id="<?php echo "inv".$row["ID"] ; ?>" value="<?php echo $row['CANTIDAD']; ?>" size="5"/></td>
    <td><input name="<?php echo "des".$row["ID"] ; ?>" type="text" id="<?php echo "des".$row["ID"] ; ?>" value="<?php echo $row['DETALLE']; ?>" size="25"/></td>
    <td align="right"><input name="<?php echo "imp".$row["ID"] ; ?>" type="text" id="<?php echo "imp".$row["ID"] ; ?>" value="<?php echo $row['PRECIOUNITARIO']; ?>" size="14"/></td>
    <td><?php echo $row["CANTIDAD"]*$row["PRECIOUNITARIO"]; ?></td>
  </tr>
  <?php } ; }; ?>
</table>
  <?php  }
  ?>
  <input type="submit" name="eliminar" id="eliminar" value="Eliminar">
  <input type="submit" name="bmod_sel" id="bmod_sel" value="Modificar Seleccinado">
</form>
<p>
  <?php echo $salida; ?>
</p>
</body>
</html>
