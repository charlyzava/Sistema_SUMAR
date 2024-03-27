<?php 

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
$usuario=$_SESSION['username'];
$ip = $_SERVER['REMOTE_ADDR'];


date_default_timezone_set("America/Argentina/Catamarca");
	$fechacreacion=date('Y-m-d');
	$horacreacion=date('H:i:s');



include ('conexion.php');

} else {
   echo "Esta pagina es solo para usuarios registrados.<br>";
   echo "<br><a href='./phplogin/login.html'>Login</a>";

exit;
}

Header('Content-Type: text/html; charset=LATIN1');
$responsable="";
$fentrega="";
$acta="";
$anioentrega="";//
$estado="";
$salida='';
$cuie='';
$encontro='0';
$grabado='0';
$delete='si';
	if (isset($_POST['tacta'])){ $acta=$_POST['tacta']; }
	if (isset($_POST['tanio'])){ $anioentrega=$_POST['tanio']; }//
	

	
if (isset($_POST['bmod_sel'])){	

		$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";//
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){

			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];//
			$responsable=$res['responsable'];
			//$usuario=$res['usuario']; QUIERO USAR EL USUARIO ACTUAL
		}// CREO QUE SE PUEDE ELIMINAR 
	$estado='abierta';

	if (isset($_POST['rbbien'])){
		$idbienm=$_POST['rbbien'];
		$impbienm=$_POST["imp".$idbienm];
		$desbienm=$_POST["des".$idbienm];
		$invbienm=$_POST["inv".$idbienm];
		
			$fechacreacion=date('Y-m-d');
			$horacreacion=date('H:i:s');
		
			$mod_seleccionado="UPDATE ACTAS.dbo.ACTAS_BIENES SET descripcion='$desbienm', importe = '$impbienm', inventario = '$invbienm', usuario='$usuario', fecha_creacion ='$fechacreacion', hora_creacion = '$horacreacion', ip='$ip' WHERE id_bien = '$idbienm' ";
			$sql_mod_seleccionado=sqlsrv_query($conn,$mod_seleccionado);
			$salida="Bien Modificado";
	} else {
		$salida="Debe seleccionar el registro para modificar";
	}
}


if (isset($_POST['abrir_factura'])){
	if (($acta=='')or($anioentrega=='')){//
		$salida='No se puede cerrar factura si no tiene Acta y Anio de Entrega';
		} else {
			
			//procedimiento consulta para mostrar
				$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";//
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){		
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];//
			$responsable=$res['responsable'];

		}
	
	$estado='abierta';
// fin procedimiento abrir factura			
			$fechacreacion=date('Y-m-d');
			$horacreacion=date('H:i:s');
			$abrir_factura="UPDATE ACTAS.dbo.ACTAS SET estado='', usuario='$usuario', fecha_creacion ='$fechacreacion', hora_creacion = '$horacreacion', ip='$ip' WHERE nro_acta= $acta and anio_acta = $anioentrega";//
			$sql_abrir_factura=sqlsrv_query($conn,$abrir_factura);
			$salida='Factura abierta';
			// agregue ahora
			$estado="abierta";
		}
	
	
}
if (isset($_POST['bcerrarf'])){
	$cont=0;
	
	if (($acta=='')or($anioentrega=='')){//
		$salida='Complete Acta y Anio de Entrega';
		} else {
	
	if (isset($fdesde)){
	$fdesde=$_POST['from'];
	$fdesde=explode("/" , $fdesde);
	$fdesde=$fdesde[2]."-".$fdesde[1]."-".$fdesde[0];
	}

$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";//
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){		
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];//
			$responsable=$res['responsable'];
			$usuario=$res['usuario'];
			$estado=$res['estado'];
			
		$cont=$cont+1;
		}
		if ($cont>0){
			if ($estado=="cerrada"){
				$estado="cerrada";
				$salida="Factura Cerrada";
			} else {
				$estado="abierta";
				$salida="Factura Abierta";
			}
		} else {
			$estado="inexistente";
			$salida="No existe";
		}
		} // complete los datos de alta y anio entrega
		
	
}
// eliminar bien	
if (isset($_POST['eliminar'])){
	$delete='si';
	$estado="abierta";
	
	$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";//
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$id_acta=$res['id_acta'];			
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];//
			$responsable=$res['responsable'];
			$usuario=$res['usuario'];
		} //CREO QUE NO HACE FALTA
		
	if (isset($_POST['rbbien'])){
	$deletebien=$_POST['rbbien'];	
	$sql_del_bien="DELETE FROM ACTAS.dbo.ACTAS_BIENES WHERE id_bien='$deletebien'";
	$res_del_bien=sqlsrv_query($conn,$sql_del_bien);
	$salida="Se elimino el item ".$deletebien;
	} else {
		$salida="Seleccione un registro a eliminar";
		
	}
}
	
if (isset($_POST['agregar'])){
	
	$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$id_acta=$res['id_acta'];			
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];
			$responsable=$res['responsable'];
		}
	
	
	
	
	$estado='abierta';
	//AGREGAR IDBIEN AUTOINCREMENTAL//////
	$sql_max= "SELECT MAX(id_bien) as maximo FROM ACTAS.dbo.ACTAS_BIENES";
	$res_max=sqlsrv_query($conn,$sql_max);
	$resultado=sqlsrv_fetch_array($res_max);
	$maximo=$resultado['maximo'];
	$idbien=$maximo+1;
	$inventario=$_POST['tinventario'];
	$expte=$_POST['texpte'];
	$anioexpte=$_POST['texpteanio'];
	$cantidad=$_POST['tcantidad']; // SI ES MÃƒÆ’Ã‚ÂS DE UNO DEFINIR SI SE GRABA EL NRO DE SERIE
	$descripcion=$_POST['tdescripcion'];
	$importe=$_POST['timporte'];
	$nserie=$_POST['tnserie'];
	$codigo=$_POST['scodigo'];
	$codigo=explode("-",$codigo);
	



	for ($i=0; $i<$cantidad; $i++){
		$insert_acta="INSERT INTO ACTAS.dbo.ACTAS_BIENES (nro_acta,nro_expte,descripcion,importe,n_serie,usuario,anio_acta,inventario,anio_expte,codigo,subcodigo, fecha_creacion, hora_creacion, ip) VALUES ('$acta','$expte','$descripcion','$importe','$nserie','$usuario','$anioentrega','$inventario','$anioexpte','$codigo[0]','$codigo[1]', '$fechacreacion', '$horacreacion', '$ip')";
	$result=sqlsrv_query($conn,$insert_acta);	
	}
	
}

if (isset($_POST['grabar'])){
	
	$errorgrabar=false;
	$fentrega=$_POST['from'];
	$responsable=$_POST['tresponsable'];

	$CUIE="";

	if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND E.CUIE ='".$CUIE."'";
		$cuie=$CUIE;
		} else { 
			$CUIE=""; 
		}
	} 
	
	
	if (($fentrega=='') or ($responsable=='') or ($CUIE=='')){
		$salida="Debe completar los datos para grabar"; 
		$errorgrabar=true; } 

		// aqui validar CUIE	
	
	$cons_acta="SELECT count(*) as contador FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega'";
	$result=sqlsrv_query($conn,$cons_acta);
	while ($fila=sqlsrv_fetch_array($result)){
		$cont=$fila['contador'];
		}
		if ($cont>=1){ 
			$salida="Error, ya existe el Numero de Acta y el Anio"; 
			$errorgrabar=true;
	} 	

	if ($errorgrabar==false){


	$sql_insert="INSERT INTO ACTAS.dbo.ACTAS (nro_acta,f_entrega,cuie,responsable,anio_acta,usuario, fecha_creacion, hora_creacion, ip) 
	VALUES ('$acta','$fentrega','$CUIE','$responsable','$anioentrega','$usuario','$fechacreacion','$horacreacion', '$ip')";
$result=sqlsrv_query($conn,$sql_insert);
if ($result){
	$salida="Se grabo correctamente";
	$estado="grabado";
	$grabado='si';
} else {
	$salida="No se pudo grabar";
	$estado='inexistente';
	$grabado='no';
}
		}
		
}

//------------ BUSCAR ------------------

if(isset($_POST['buscar'])){
	$cont=0;
	

	if (($acta=='')or($anioentrega=='')){
		$salida='Complete Acta y Anio de Entrega';
		} else {
	
	if (isset($fdesde)){
	$fdesde=$_POST['from'];
	$fdesde=explode("/" , $fdesde);
	$fdesde=$fdesde[0]."-".$fdesde[1]."-".$fdesde[2];
	}

$conscompleta="SELECT * FROM ACTAS.dbo.ACTAS WHERE nro_acta = '$acta' and anio_acta = '$anioentrega' ";
		$result=sqlsrv_query($conn,$conscompleta);
		while ($res=sqlsrv_fetch_array($result)){
			$id_acta=$res['id_acta']; //modi
			$fentrega=$res['f_entrega'];
			$cuie=$res['cuie'];
			$acta=$res['nro_acta'];
			$anioentrega=$res['anio_acta'];
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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html><head>
<meta charset="LATIN1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Alta de Actas ::.</title>
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <script type="text/javascript">
 
	function objetoAjax(){
		var xmlhttp = false;
		try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
 
			try {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				xmlhttp = false; }
		}
 
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		  xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}

function enviarDatos(){
 
 		c = confirm('Ã‚Â¿Confirma Cerrar el Acta?');
	if (c) {
 
		//form1: nombre del formulario
		//tacta,tanio: edits con los valores
 		acta = document.form1.tacta.value;
		anio_acta = document.form1.tanio.value;
         //AquÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­ serÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ donde se mostrarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ el resultado
		jugador = document.getElementById('jugador');
 
		//instanciamos el objetoAjax
		ajax = objetoAjax();
 
		//Abrimos una conexiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n AJAX pasando como parÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡metros el mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo de envÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­o, y el archivo que realizarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ las operaciones deseadas
		ajax.open("POST", "actas_cierrafactura.php", true);
 
		//cuando el objeto XMLHttpRequest cambia de estado, la funciÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n se inicia
		ajax.onreadystatechange = function() {
 
             //Cuando se completa la peticiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n, mostrarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ los resultados 
			if (ajax.readyState == 4){
 
				//El mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
				jugador.value = (ajax.responseText) 
				salida.value = (ajax.responseText)
			}
		} 
 
		//Llamamos al mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo setRequestHeader indicando que los datos a enviarse estÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡n codificados como un formulario. 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
		//enviamos las variables a 'consulta.php' 
		//ajax.send("&equipo="+equipo) 
		ajax.send("&acta="+acta+"&anio_acta="+anio_acta) ;    
		
 
	} 
 
} 
</script>
 <script type="text/javascript">
// --------------- ADVERTENCIA ELIMINAR ACTA --------------------------

function eliminarActa(){
 
 		c = confirm('Â¿Confirma Eliminar el Acta? Se registrara esta accion con su usuario');
	if (c) {
 
		//form1: nombre del formulario
		//tacta,tanio: edits con los valores
 		acta = document.form1.tacta.value;
		anio_acta = document.form1.tanio.value;
         //AquÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­ serÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ donde se mostrarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ el resultado
		jugador = document.getElementById('jugador');
 
		//instanciamos el objetoAjax
		ajax = objetoAjax();
 
		//Abrimos una conexiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n AJAX pasando como parÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡metros el mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo de envÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­o, y el archivo que realizarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ las operaciones deseadas
		ajax.open("POST", "actas_elimina.php", true);
 
		//cuando el objeto XMLHttpRequest cambia de estado, la funciÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n se inicia
		ajax.onreadystatechange = function() {
 
             //Cuando se completa la peticiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³n, mostrarÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ los resultados 
			if (ajax.readyState == 4){
 
				//El mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
				jugador.value = (ajax.responseText) 
				salida.value = (ajax.responseText)
			}
		} 
 
		//Llamamos al mÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©todo setRequestHeader indicando que los datos a enviarse estÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡n codificados como un formulario. 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
		//enviamos las variables a 'consulta.php' 
		//ajax.send("&equipo="+equipo) 
		ajax.send("&acta="+acta+"&anio_acta="+anio_acta) ;    
		
 
	} 
 
} 

</script>
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
CÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢fÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â 'ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢,ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³digo: Seleccionar todo 
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
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Alta de Actas</td>
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
      <td>Acta / Anio</td>
      <td><label for="tacta"></label>
        <input name="tacta" type="number" id="tacta" value="<?php if (isset($_POST['tacta'])){ echo $_POST['tacta']; }?>" size="5" min="0" max="99000">
        /
        <label for="tanio"></label>
        <input name="tanio" type="number" id="tanio" value="<?php if (isset($_POST['tanio'])){ echo $_POST['tanio']; }?>" min="2000" max="2030">
  <input type="submit" name="buscar" id="buscar" value="B">
  <span class="mensajerojo"><?php if ($estado=="cerrada"){ ?><a href="pdf/actas_pdf.php?acta=<?=$acta?>&amp;anioentrega=<?=$anioentrega?>" target="_blank"><img src="imgs/pdf-icon-small.png" width="25" height="25" /></a><?php } ?></span></td>
  </tr>
</table>
<p class="mensajerojo">
 <span id="jugador"> <?php  echo $salida." ";  ?></span></p>
  <center><table width="700" border="1">
<tr>
          <td width="30%" bgcolor="#FFCC66">Cuie </td>
  <td width="70%" bgcolor="#FFCC66"><?php include('comboselec4.php');  ?>
      </td>
      </tr>
        <tr>
          <td width="30%" bgcolor="#FFCC66">Responsable</td>
          <td width="70%" bgcolor="#FFCC66"><label for="tresponsable"></label>
          <input name="tresponsable" type="text" id="tresponsable" size="40"  maxlength="200" <?php if ($estado<>"inexistente"){ echo "disabled=\"disabled\" value=\"".$responsable."\"";} ?>>

      </td>
        </tr>
        <tr>
          <td width="30%" bgcolor="#FFCC66">Fecha de Entrega</td>
			<?php 
			if (($estado=="abierta")or($estado=="cerrada")){  if (isset($fentrega)){ $fentrega=date_format($fentrega, 'Y-m-d'); 
																	

		};  } 
			 ?>
            
            
          <td width="70%" bgcolor="#FFCC66">
          
<input type="date" name="from" id="from" value="<?php echo $fentrega; ?>"<?php if ($estado<>"inexistente"){ echo "disabled=\"disabled\" value=\"".$fentrega."\"";} ?>></td>
<input name="hfechaentrega" type="hidden" id="hfechaentrega" value="<?=$_POST['from']?>"  >
      </td>
        </tr>
</table></center>
  <p>
    <label for="scodigo"></label>
    <input type="submit" name="grabar" id="grabar" value="Grabar" <?php if ($estado<>"inexistente"){ echo "disabled=\"disabled\"";} ?>>
    &nbsp;
    <input type="submit" name="bcerrarf" id="bcerrarf" value="Cerrar Acta" onclick="enviarDatos()" <?php if (($estado=="cerrada")or($estado=="inexistente")){ echo "disabled=\"disabled\"";} ?>>
    <input type="submit" name="abrir_factura" id="abrir_factura" value="Abrir Acta" <?php if (($estado=="abierta")or($estado=="inexistente")){ echo "disabled=\"disabled\"";} ?>>
  <!--  <input type="submit" name="beliminar" id="beliminar" onclick="eliminarActa()" value="Eliminar Acta" <?php // if ($estado=="inexistente"){ echo "disabled=\"disabled\"";} ?>> -->
    <?php if (($estado=="abierta")or($estado=="grabado")){ ?>
    <label for=" &quot;imp&quot;.$num ; ?&gt;"></label>
  </p>
  <table width="1100" border="1" align="center">
    <tr>
      <td>Codigo</td>
            <td>inventario</td>
            <td width="140">Expte/anio</td>
            <td bgcolor="#FF6B46">Cant</td>
            <td>Descripci&oacute;n</td>
            <td>Importe</td>
            <td>N&ordm; Serie</td>
    </tr>
          <tr>
            <td>
            <select name="scodigo" id="combobox">
              <?php $sql_codigo="SELECT * FROM ACTAS_COMPONENTE_GASTO";
	    	$res_codigo=sqlsrv_query($conn,$sql_codigo);
			if (isset($res_codigo)){
				while ($rowc=sqlsrv_fetch_array($res_codigo)){
		?>
              <option value="<?php echo $rowc['codigo']."-".$rowc['sub_codigo'];  ?>"><?php echo $rowc['Nombre']." - ".$rowc['sub_grupo']; ?></option>
              <?php }; } ?>
            </select>
            </td>
            <td><label for="tacta2"></label>
              <input name="tinventario" type="text" id="textfield2" size="4" maxlength="20"></td>
            <td width="140"><label for="texpteanio"></label>
              <input name="texpte" type="text" id="textfield5" value="<?php if(isset($_POST['agregar'])){ echo $_POST['texpte']; }?>" size="3" maxlength="10">
              /
              <label for="tdescripcion"></label>
              <input name="texpteanio" type="text" id="textfield6" value="<?php if(isset($_POST['agregar'])){ echo $_POST['texpteanio']; } ?>" size="2" maxlength="4"></td>
            <td bgcolor="#FF6B46"><label for="tcantidad"></label>
            <input name="tcantidad" type="text" id="tcantidad" value="1" size="4"></td>
            <td><label for=" &quot;imp&quot;.$num ; ?&gt;"></label>
            <input name="tdescripcion" type="text" id="textfield7" value="<?php if(isset($_POST['agregar'])){ echo $_POST['tdescripcion']; } ?>" size="40" maxlength="200"></td>
            <td><label for="textfield10"></label>
              <input name="timporte" type="number" id="textfield10" value="0<?php //if (isset($_POST['agregar'])){$_POST['timporte']; } else { "0"; } ?>" min="0" step=".01"></td>
            <td><label for="textfield11"></label>
              <input name="tnserie" type="text" id="textfield11" value="<?php if (isset($_POST['agregar'])){ echo $_POST['tnserie']; }?>" size="10" maxlength="50"></td>
          </tr>
  </table>
        <p>
          <input type="submit" name="agregar" id="agregar" value="Agregar">
          <label for="tinventario">
            <?php }
if ($estado<>"inexistente"){ ?>
            <br>
          </label>
        </p>
  <table width="800" border="1" align="center">
    <tr>
    <td>id bien</td>
    <td>cod-subcodigo</td>
    <td>Inventario</td>
    <td>Expte/A&ntilde;o</td>
    <td>Descripcion</td>
    <td>Importe</td>
    <td>N&ordm; Serie</td>
  </tr>

<?php			

		$sql_bienes="SELECT * FROM ACTAS.dbo.ACTAS_BIENES WHERE  nro_acta = '$acta' AND anio_acta = '$anioentrega'";
		$res_bienes=sqlsrv_query($conn,$sql_bienes);			
		if (isset($res_bienes)){
  while($row=sqlsrv_fetch_array($res_bienes))
	{ 
  ?>
  <tr>
    <td align="left"><input type="radio" name="rbbien" id="rbbien" value="<?php echo $row["id_bien"]; ?>">
      <label for="rbbien"></label>      <?php echo $row["id_bien"]; ?></td>
    <td align="left"><label for="select2"><?php echo $row["codigo"]; ?>-<?php echo $row["subcodigo"]; ?></label></td>
    <td align="left"><input name="<?php echo "inv".$row["id_bien"] ; ?>" type="text" id="<?php echo "inv".$row["id_bien"] ; ?>" value="<?php echo $row['inventario']; ?>" size="25" maxlength="20"/></td>
    <td><?php echo $row['nro_expte']; ?>/<?php echo $row['anio_expte']; ?></td>
    <td><input name="<?php echo "des".$row["id_bien"] ; ?>" type="text" id="<?php echo "des".$row["id_bien"] ; ?>" value="<?php echo $row['descripcion']; ?>" size="25" maxlength="200"/></td>
    <td align="right"><input name="<?php echo "imp".$row["id_bien"] ; ?>" type="number" id="<?php echo "imp".$row["id_bien"] ; ?>" value="<?php echo $row['importe']; ?>" size="14" min="0" step=".01"/></td>
    <td><?php echo $row["n_serie"]; ?></td>
  </tr>
  <?php } ; }; ?>
</table>
  <?php } ; 
  if ($estado=="abierta"){ ?>
  <input type="submit" name="eliminar" id="eliminar" value="Eliminar">
  <input type="submit" name="bmod_sel" id="bmod_sel" value="Modificar Seleccionado">
  <?php } ?>
</form>
<p>
  <?php echo $salida."<br>"; ?>
</p>
</body>
</html>
