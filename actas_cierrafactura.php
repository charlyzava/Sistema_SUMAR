<?php
include('conexion.php');

$acta=$_POST['acta']; 
$anioacta=$_POST['anio_acta'];

$nombre_jugador = null;

$sql_cerrarf="UPDATE ACTAS.dbo.ACTAS SET estado='cerrada' WHERE nro_acta='$acta' and anio_acta='$anioacta'";
		$res_cerrarf=sqlsrv_query($conn,$sql_cerrarf);

 if ($res_cerrarf){
echo "Acta Cerrada";
 } else {
echo "Error: no se cerró el Acta";
 }

?>
