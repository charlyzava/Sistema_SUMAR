<?php
include('conexion.php');

$acta=$_POST['acta']; 
$anioacta=$_POST['anio_acta'];
if (($acta <>'')&($anioacta<>'')){ // ----------- COMPRUEBO QUE SE INGRESARON DATOS
$cont=0;
/*$sql_verif="SELECT * FROM ACTAS_BIENES WHERE nro_acta='$acta' and anio_entrega='$anioacta'"; // ----------------  COMPRUEBO QUE EXISTE EL ACTA
$res_verif=sqlserv_query($conn,$sql_verif);
while ($row2=sqlsrv_fetch_array($res_verif)) {
		$cont++;
		$row2[''];
	}
if ($cont>0){*/

$nombre_jugador = null;


$sql="DELETE ACTAS.dbo.ACTAS WHERE nro_acta='$acta' and anio_acta='$anioacta'";
		$res=sqlsrv_query($conn,$sql);

$sql_bienes="DELETE ACTAS.dbo.ACTAS_BIENES WHERE nro_acta='$acta' and anio_acta='$anioacta'";
		$res=sqlsrv_query($conn,$sql_bienes);

 if ($res){
echo "Acta Eliminada";
 } else {
echo "Error: no se eliminó el Acta";
 }
}
//}
?>