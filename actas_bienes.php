<?php 

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
$usuario=$_SESSION['username'];

include ('conexion.php');

} else {
   echo "Esta pagina es solo para usuarios registrados.<br>";
   echo "<br><a href='./phplogin/login.html'>Login</a>";

exit;
}

Header('Content-Type: text/html; charset=LATIN1');
    $salida="";

$cuie='';
$CUIE="";

		if (isset($_POST['scuie'])){ $cuie = $_POST['scuie'];
								$cuiet=explode("-", $cuie );
								$cuie=$cuiet[0];
								$cuielargo=$cuiet[1];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>


<head>
<meta charset="LATIN1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Bienes Entregados ::.</title>
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <script src="jquery-1.8.3.min.js" type="text/javascript"></script><script type="text/javascript">
 
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
 
 		c = confirm('Â¿Confirma Cerrar la Factura?');
	if (c) {
 
		//form1: nombre del formulario
		//tacta,tanio: edits con los valores
 		acta = document.form1.tacta.value;
		anio_acta = document.form1.tanio.value;
         //AquÃ­ serÃ¡ donde se mostrarÃ¡ el resultado
		jugador = document.getElementById('jugador');
 
		//instanciamos el objetoAjax
		ajax = objetoAjax();
 
		//Abrimos una conexiÃ³n AJAX pasando como parÃ¡metros el mÃ©todo de envÃ­o, y el archivo que realizarÃ¡ las operaciones deseadas
		ajax.open("POST", "actas_cierrafactura.php", true);
 
		//cuando el objeto XMLHttpRequest cambia de estado, la funciÃ³n se inicia
		ajax.onreadystatechange = function() {
 
             //Cuando se completa la peticiÃ³n, mostrarÃ¡ los resultados 
			if (ajax.readyState == 4){
 
				//El mÃ©todo responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
				jugador.value = (ajax.responseText) 
				salida.value = (ajax.responseText)
			}
		} 
 
		//Llamamos al mÃ©todo setRequestHeader indicando que los datos a enviarse estÃ¡n codificados como un formulario. 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
		//enviamos las variables a 'consulta.php' 
		//ajax.send("&equipo="+equipo) 
		ajax.send("&acta="+acta+"&anio_entrega="+anio_acta) ;    
		
 
	} 
 
} 

  </script>
		<link rel="shortcut icon" href="sumar.ico"> 
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
  

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Consulta de Beneficiarios ::.</title>

<style type="text/css">
body {
	background-color: #D6D6D6;
}
CÃƒÆ’fÃƒâ€ 'ÃƒÆ’,Ãƒâ€šÃ‚Â³digo: Seleccionar todo 
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


<?php //include("actas_menusmall_script.php"); ?>



<link href="estilos.css" rel="stylesheet" type="text/css" />
<link href="jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<center>

<table width="100%" border="0">
  <tr>
    <td width="33%" align="left">
    
   <?php include("actas_menusmall.php"); ?> </td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">BIENES ENTREGADOS</td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%"><center>
      <a href="excel/cuentaescritural_excel.php?cuie=<?=$cuie?>">
      <?php if (isset($_POST['filtrar'])){?>
      </a><a href="pdf/bienes_pdf.php?cuie=<?=$cuie?>&cuielargo=<?=$cuielargo?>" target="_blank"> <img src="imgs/pdf-icon-small.png" alt="Generar Archivo de Excel" width="40" height="40" />
      <?php } ?>
      </a><a href="phplogin/logout.php"><img src="imgs/cerrar_sesion.png" width="132" height="33"></a>
    </center></td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <p>&nbsp;</p>
  <table width="400" border="1" align="center">
    <tr>
      <td width="30%">Cuie </td>
      <td width="70%"><?php include('comboselec4.php');  ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p><span name="jugador">
    <input type="submit" name="filtrar" id="filtrar" value="Filtrar">
    <?php  echo $salida;  ?>
  </span>
    <label for="scodigo"></label>
    &nbsp;
    <label for="timporte"></label>
  </p>
<label for="tfechapago"><br>
          </label>
  <table width="1000" border="1" align="center">
    <tr>
      <td>id bien</td>
      <td>Acta/Anio</td>
      <td>Estado</td>
      <td>F Entrega</td>
      <td>cod-subcodigo</td>
      <td>Inventario</td>
      <td>Expte/A&ntilde;o</td>
      <td>Descripcion</td>
      <td>Importe</td>
      <td>N&ordm; Serie</td>
      <td>usuario carga</td>
    </tr>
    <?php			
	if (isset($_POST['filtrar'])){
		$sql_bienes="SELECT * FROM ACTAS.dbo.ACTAS A LEFT JOIN ACTAS.dbo.ACTAS_BIENES B ON A.nro_acta = B.nro_acta and a.anio_acta = b.anio_acta  WHERE cuie='$cuie' order by f_entrega ";
		$res_bienes=sqlsrv_query($conn,$sql_bienes);			
		if (isset($res_bienes)){
		$total=0;	
  while($row=sqlsrv_fetch_array($res_bienes))
	{ 
	$total=$total+$row['importe'];
	
	
	
  ?>
    <tr>
      <td align="left"><input type="radio" name="rbbien" id="radio" value="<?php echo $row["id_bien"]; ?>">
        <label for="rbbien2"></label>
        <?php echo $row["id_bien"]; ?></td>
      <td align="left"><?php echo $row["nro_acta"]; ?>/<?php echo $row["anio_acta"]; ?></td>
      <td align="left"><?php echo $row["estado"]; ?></td>
      <td align="left"><?php echo $row["f_entrega"]->format('d-m-Y'); ?></td>
      <td align="left"><label for="select3"><?php echo $row["codigo"]; ?>-<?php echo $row["subcodigo"]; ?></label></td>
      <td align="left"><?php echo $row["inventario"]; ?></td>
      <td><?php echo $row['nro_expte']; ?>/<?php echo $row['anio_expte']; ?></td>
      <td><?php //echo $row["FPractica"]->format('d-m-Y'); ?>
        <?php echo $row['descripcion']; ?></td>
      <td align="right"><?php echo $row['importe']; ?></td>
      <td><?php echo $row["n_serie"]; ?></td>
      <td><?php echo $row["usuario"]; ?></td>
    </tr>
    <?php } ; }; ?>
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
      <td>Total</td>
      <td align="right"><?php echo $total; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
  </table>
  <?php };
echo $sql_bienes; ?>
<p>&nbsp;</p>
</form>
</body>
</html>
