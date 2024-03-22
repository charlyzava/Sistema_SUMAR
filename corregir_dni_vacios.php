<?php include ('\conexion.php');
//Header('Content-Type: text/html; charset=LATIN1');
////////////

/*
Los registros con campo AfiDNI vacíos se corrigieron con la siguiente consulta, la tabla de comparación se grabó en la base inscripción y tenía los campos: clave_beneficiario, numero_doc, (apellido_benef, nombre_benef, cuie_ah aunque no hacen falta, sólo para chequear)

UPDATE Nacer_Nacion.dbo.Smiafiliados--base_de_datos_destino.esquema_destino.tabla_destino
SET afiDNI = numero_doc
FROM Nacer_Nacion.dbo.SMIAfiliados AS tabla_destino
JOIN inscripcion.dbo.smiafiliados_dni_corregir AS tabla_origen
ON tabla_destino.ClaveBeneficiario = clave_beneficiario COLLATE SQL_Latin1_General_CP1_CI_AI

salen de la consulta siguiente

UPDATE Nacer_Nacion.dbo.Smiafiliados_prueba
SET afiDNI = tabla_origen.numero_doc
FROM Nacer_Nacion.dbo.Smiafiliados_prueba AS tabla_destino
JOIN inscripcion.dbo.smiafiliados_dni_corregir AS tabla_origen
ON tabla_destino.ClaveBeneficiario = tabla_origen.clave_beneficiario COLLATE SQL_Latin1_General_CP1_CI_AI;

*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=LATIN1" />
<title>.::Completa A&ntilde;o de Pr&aacute;ctica ::.</title>
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
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Inicio" width="50" height="50" /></a><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>"><span class="encabezado"><a name="inicio" title="inicio" id="un-nombre2"></a></span></a></td>
    <td width="34%" align="center"><img src="imgs/expedientes.gif" width="600" height="50" /></td>
    <td width="33%"><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>">
      <?php if (isset($_POST['consultar'])){?>
    <img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/expedientes_excel.php?consulta=<?=$consultaexportar?>">
    <?php } ?>
    </a></td>
  </tr>
</table>
<center>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <p>
    <center>
      <p>Corregir DNI Vac&iacute;os desde tabla inscripcion.dbo.smiafiliados_dni_corregir</p>
      <p>Cant Registros a actualizar
        <input type="number" name="cantidad" id="cantidad" value="10" max="10"/>
          <!-- se limitó a 10 poque cuando ingresaba 100 se plantaba -->
        <input type="submit" name="consultar" id="consultar" value="Consultar" />
        &nbsp;<br />
      </p>
    </center></p>
</form>
<p>&nbsp;</p>
    <center>
<?php

if ((isset($_POST['consultar']))){
    if (isset($_POST['cantidad'])){
    $cantidad=$_POST['cantidad']; } else {
        $cantidad = 0;
    }
    
	$consulta=" SELECT top $cantidad * FROM Nacer_Nacion.dbo.smiafiliados where afiDNI = '' ";
    echo $consulta;
	$result=sqlsrv_query($conn,$consulta);
	
	if (isset($result)){ while($row=sqlsrv_fetch_array($result))
	{
		
		$clavebeneficiario=$row['ClaveBeneficiario'];
        $apellido=$row["afiApellido"];
        $nombre=$row["afiNombre"];
		$dni=$row['afiDNI'];
		$cuie=$row['CUIEEfectorAsignado'];
		
        echo "<STRONG>".$clavebeneficiario." ".$apellido." ".$nombre." ".$dni." ".$cuie."<br></STRONG>";
		
		
		$cons2= "SELECT * FROM inscripcion.dbo.smiafiliados_dni_corregir where clave_beneficiario = '$clavebeneficiario'";
		$res2=sqlsrv_query($conn,$cons2);
		
		
		if (isset($res2)){	while($row2=sqlsrv_fetch_array($res2))
			{
				$clave_reemplazar=$row2['clave_beneficiario'];
				$dni_reemplazar=$row2['numero_doc'];
				$apellido_reemplazar=$row2['apellido_benef'];
				$nombre_reemplazar=$row2['nombre_benef']; //(int)$row2['dni']
				$cuie_reemplazar=$row2['cuie_ah'];
				
				
				echo "-->".$clave_reemplazar." ".$dni_reemplazar." ".$apellido_reemplazar." ".$nombre_reemplazar." ".$cuie_reemplazar."<br>";
            
            $update_dni="UPDATE NACER_NACION.dbo.SMIAfiliados set afiDNI = '$dni_reemplazar' where ClaveBeneficiario = '$clave_reemplazar'";
            $result_upd=sqlsrv_query($conn,$update_dni);
				echo $update_dni."<br><br>";
				
				
        }
			}
		
		}
		
	}
	
	
	}
	
	



?>
        </center>
</body>
</html>