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

$salida = "Bienvenidos!";

include('parametros.php'); // sacamos nomenclador


if (isset($_POST['bactualizar'])){
		
	
		$sqltodosn="UPDATE SUMAR.dbo.NOMENCLADOR SET VIGENTE = 'N'";
		$result=sqlsrv_query($conn,$conscompleta);
		
		$idnomenclador = $_POST['selectnomenclador'];
		$salida=$idnomenclador;
		// Prepara la sentencia SQL con marcadores de posici�n
		$sql = "UPDATE SUMAR.dbo.NOMENCLADOR SET VIGENTE = 'S' WHERE ID = ? ";

		// Inicia una sentencia preparada
		$stmt = sqlsrv_prepare($conn, $sql, array(&$idnomenclador));

		// Ejecuta la sentencia
		if (sqlsrv_execute($stmt)) {
			//$nomenclador=$nuevonomenclador;
			$salida = "Nomenclador id:".$idnomenclador." Grabado";
			sqlsrv_free_stmt($stmt);
			
			/* modifica el resto de registros */
			$sql = "UPDATE SUMAR.dbo.NOMENCLADOR SET VIGENTE = ? WHERE id <> ?";
			$params = array("N", $idnomenclador);

			$stmt = sqlsrv_query($conn, $sql, $params);
			/* modifica el resto de registros */
			
		} else {
			// Manejar errores si la ejecuci�n falla
			$salida = "Error al grabar el nomenclador";
		}

		// Cierra la sentencia preparada
		//sqlsrv_free_stmt($stmt);
		

	
	//sqlsrv_close($conn);
	
}	
	
	



Header('Content-Type: text/html; charset=LATIN1');

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
        <td align="center" bgcolor="#FFFFFF" class="encabezadopag">Parametros</td>
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
  <p class="mensajerojo">
 <span name="jugador"> <?php  echo $salida;  ?></span></p>
<table width="400" border="1" align="center">
    
  <tr>
    <td colspan="2">Parametros</td>
  </tr>
 
  <tr>
    <td>Nomenclador</td>
    <td><select name="selectnomenclador" id="selectnomenclador">
      <?php  $consnomenclador="SELECT * FROM SUMAR.dbo.NOMENCLADOR";//
		$result=sqlsrv_query($conn,$consnomenclador);
		while ($res=sqlsrv_fetch_array($result)){ ?>
      <option value="<?=$res['ID']?>" <?php if ($res['VIGENTE']==='S'){ echo " selected "; }?> ><?=$res['NOMBRE']?></option>
      <?php } ?>
      </select></td>
  </tr>
	 <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </table>
  <center>
    <input type="submit" name="bactualizar" id="bactualizar" value="Actualizar">
  </center>
</form>
</body>
</html>
