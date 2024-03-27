<?php include ('conexion.php');
include('funciones.php');
//Sacaremos obtenerNomenclador
//Header('Content-Type: text/html; charset=LATIN1');
////////////
$nrofactura=0;
$nrofactura=$_GET{'factura'};




$res_datos=sqlsrv_query($conn,"SELECT TOP 1 F.Periodo, F.Cuie, E.NOMBREEFECTOR FROM FACTURACION F LEFT JOIN EFECTORES E ON F.Cuie = E.CUIE WHERE F.NumFactura = '$nrofactura' ");
  while($row=sqlsrv_fetch_array($res_datos))
	{
		$periodo=$row["Periodo"];
		$cuie=$row["Cuie"];
		$scuie=$row["NOMBREEFECTOR"];
	}


$nomenclador = obtenerNomenclador($periodo, $conn);
echo $nomenclador;

$result=sqlsrv_query($conn,"SELECT * FROM FACTURACION WHERE NumFactura = '$nrofactura' ");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>.:: Factura ::.</title>
<style type="text/css">
body {
	background-color: #D6D6D6;
}
</style>
</head>

<body>
<center>
</center>
<center>
<table width="500" border="1">
  <tr>
    <td align="center">.:: <?php echo utf8_encode($cuie." - ".$scuie) ?> ::.</td>
  </tr>
  <tr>
    <td align="center">Per&iacute;odo <?php echo $periodo; ?></td>
  </tr>
  <tr>
    <td align="center">Factura <?php echo $nrofactura; ?></td>
  </tr>
</table>
<p><br>
</p>
</center>
<p>
  <input type="checkbox" id="cbregistros"/>
Registros</p>
<div id="divRegistros" style="display: none;">
<table width="100%" border="1">
  <tr>
    <td width="1%">N&ordm;</td>
    <td width="11%">Documento</td>
    <td width="30%">Apellido y Nombre</td>
    <td width="5%">EDAD</td>
    <td width="5%">Categor&iacute;a</td>
    <td width="1%">Proc</td>
    <td width="1%">Aut</td>
    <td width="1%">Rech</td>
    <td width="5%">Cod Pr&aacute;ctica</td>
    <td width="7%">Fecha Pr&aacute;ctica</td>
    <td width="7%">Importe Pr&aacute;ctica</td>
  </tr>
    <?php
$total=0;
$cont=0;

$ap=0;
$au=0;
$ca=0;
$co=0;
$ct=0;
$ds=0;
$ig=0;
$im=0;
$it=0;
$lb=0;
$le=0;
$nt=0;
$pr=0;
$ro=0;
$ta=0;
$tl=0;



  while($row=sqlsrv_fetch_array($result))
	{
		$total=$total+$row["PrecioPractica"];
		$codigo=$row['CodPractica'];
	
		$codcorto=substr($codigo,0,2);
		$codobjeto=substr($codigo,2,4);
		
		switch ($codcorto){
				case 'AP':
					$ap=$ap+1;
					break;
				case 'AU':
					$au=$au+1;
					break;
				case 'CA':
					$ca=$ca+1;
					break;
				case 'CO':
					$co=$co+1;
					break;
				case 'CT':
					$ct=$ct+1;
					break;
				case 'DS':
					$ds=$ds+1;
					break;
				case 'IG':
					$ig=$ig+1;
					break;
				case 'IM':
					$im=$im+1;
					break;
				case 'IT':
					$it=$it+1;
					break;

				case 'LB':
					$lb=$lb+1;
					break;
				case 'LE':
					$le=$le+1;
					break;
				case 'NT':
					$nt=$nt+1;
					break;
				case 'PR':
					$pr=$pr+1;
					break;
				case 'RO':
					$ro=$ro+1;
					break;
				case 'TA':
					$ta=$ta+1;
					break;
				case 'TL':
					$tl=$tl+1;
				
			}
		
		
		
		
		
		$res_codcompleto = sqlsrv_query($conn, "SELECT * From $nomenclador WHERE CodPrestacion = '$codcorto' AND CodObjeto = '$codobjeto' ");
		while ($row2=sqlsrv_fetch_array($res_codcompleto)){
			$nombreprestacion=$row2['NombrePrestacion'];
			$tipopres=$row2['CodPrestacion'];
			
			
			
			
			
		}
		$cont=$cont+1;
  ?>
  <tr>
    <td><?php echo $cont; ?></td>
    <td><?php echo $row["NumDoc"]; ?></td>
    <td><?php echo utf8_encode($row["Apellido"].", ".$row["Nombre"]); ?></td>
    <td><?php echo $row["Edad"]; ?></td>
    <td><?php echo $row["Categoria"]; ?></td>
    <td><?php echo $row["Procesado"]; ?></td>
    <td><?php echo $row["Autorizado"]; ?></td>
    <td><?php echo $row["CodRechazoPractica"]; ?></td>
    <td align="right"><a title="<?php echo $nombreprestacion ?>"><?php echo $row["CodPractica"]; ?></a><a href="prestacionesconsulta.php?codpractica=<?=$row["CodPractica"]?>" target="_blank"><img src="imgs/lupa.gif" alt="Detalle" width="16" height="15" /></a></td>
    <td><?php  echo $row["FPractica"]->format('d-m-Y'); ?></td>
    <td><?php echo $row["PrecioPractica"]; ?></td>
  </tr>
    <?php } ; ?>
  <tr>
    <td colspan="10">&nbsp;</td>
    <td>Total: <?php echo $total; ?></td>
  </tr><?php  ?>
</table>
</div>
<p>&nbsp;</p>
<p>
  <input type="checkbox" id="cbTiposPracticas" />
Totales x Tipo</p>
<div id="divTiposPracticas" style="display: none;">
<table width="450" border="1">
  <tr>
    <td>AP: &nbsp;</td>
    <td>ANATOMIA PATOLOGICA</td>
    <td><?php echo $ap; ?></td>
  </tr>
  <tr>
    <td>AU</td>
    <td>AUDITORIA DE MUERTE</td>
    <td><?php echo $au; ?></td>
  </tr>
  <tr>
    <td>CA</td>
    <td>CAPTACION ACTIVA</td>
    <td><?php echo $ca; ?></td>
  </tr>
  <tr>
    <td>CO</td>
    <td>CONSEJERIA</td>
    <td><?php echo $co; ?></td>
  </tr>
  <tr>
    <td>CT</td>
    <td>CONSULTA</td>
    <td><?php echo $ct; ?></td>
  </tr>
  <tr>
    <td>DS</td>
    <td>DIAGNOSTICO SOCIOEPIDEMIOLOGICO</td>
    <td><?php echo $ds; ?></td>
  </tr>
  <tr>
    <td>IG</td>
    <td>IMAGENOLOGIA</td>
    <td><?php echo $ig; ?></td>
  </tr>
  <tr>
    <td>IM</td>
    <td>INMUNIZACION</td>
    <td><?php echo $im; ?></td>
  </tr>
  <tr>
    <td>IT</td>
    <td>INTERNACION</td>
    <td><?php echo $it; ?></td>
  </tr>
  <tr>
    <td>LB</td>
    <td>LABORATORIO</td>
    <td><?php echo $lb; ?></td>
  </tr>
  <tr>
    <td>LE</td>
    <td>CONTROL PARA ENTREGA DE LECHE</td>
    <td><?php echo $le; ?></td>
  </tr>
  <tr>
    <td>NT</td>
    <td>NOTIFICACION</td>
    <td><?php echo $nt; ?></td>
  </tr>
  <tr>
    <td>PR</td>
    <td>PRACTICA</td>
    <td><?php echo $pr; ?></td>
  </tr>
  <tr>
    <td>RO</td>
    <td>RONDA</td>
    <td><?php echo $ro; ?></td>
  </tr>
  <tr>
    <td>TA</td>
    <td>TALLER</td>
    <td><?php echo $ta; ?></td>
  </tr>
  <tr>
    <td>TL</td>
    <td>TRASLADOS</td>
    <td><?php echo $tl; ?></td>
  </tr>
  <tr>
    <td>Total</td>
    <td>&nbsp;</td>
    <?php $tot=$ap+$au+$ca+$co+$ct+$ds+$ig+$im+$it+$lb+$le+$nt+$pr+$ro+$ta+$tl;    ?>
    <td><?php echo $tot;?>&nbsp;</td>
  </tr>
</table>
<p>
 </table></div>
<p>&nbsp;</p>
<p>
  <input type="checkbox" id="cbTotalesPracticas" />
Totales de Pr&aacute;cticas&nbsp;</p>
<div id="divCuadro3" style="display: none;">
  <table width="750" border="1">
  <tr>
    <td>Practicas</td>
    <td>Descripcion</td>
    <td>Cantidad</td>
  </tr>
  <?php 
//
$res_desm=sqlsrv_query($conn,"SELECT CodPractica, count(CodPractica) as TotalPedido FROM FACTURACION  WHERE NumFactura = '$nrofactura' GROUP BY CodPractica ORDER BY TotalPedido DESC");


while ($rowl=sqlsrv_fetch_array($res_desm)){
	$cont=0;
	$codpractica=$rowl['CodPractica'];
$codcorto=substr($codpractica,0,2);
$objcorto=substr($codpractica,2,4);
$diagcorto=substr($codpractica,6,3);
$cons=sqlsrv_query($conn, "SELECT NombrePrestacion FROM $nomenclador WHERE CodPrestacion = '$codcorto' and CodObjeto='$objcorto' and CodDiagnostico='$diagcorto'");
while ($res=sqlsrv_fetch_array($cons)){
 $descripcion=$res['NombrePrestacion'];
 $cont++;
}
if ($cont==0){
	$cons=sqlsrv_query($conn, "SELECT NombrePrestacion FROM $nomenclador WHERE CodPrestacion = '$codcorto' and CodObjeto='$objcorto'");
while ($res=sqlsrv_fetch_array($cons)){
 $descripcion=$res['NombrePrestacion'];
}
	
		$consdesc=sqlsrv_query($conn,"SELECT DetalleDiagnostico FROM DIAGNOSTICO WHERE CodigoDiagnostico='$diagcorto'");
while($res2=sqlsrv_fetch_array($consdesc)){
		$diagnostico=$res2['DetalleDiagnostico'];
		$descripcion=utf8_encode($descripcion." Diagnostico: ".$diagnostico);
	}
	
	
	
}
  ?>
  <tr>
    <td><?php echo $rowl['CodPractica'];  ?></td>
    <td><?php if (isset($descripcion)){ echo utf8_encode($descripcion); } else { echo "No se encontro descripcion"; } ?></td>
    <td><?php echo $rowl['TotalPedido'];  ?></td>
    <?php $descripcion=''; ?>
  </tr>
  <?php };  ?>
</table>
</div>
<p>&nbsp; </p>
<p>
  <input type="checkbox" id="cbRechazados" />
Rechazados/Aprobados
<?php
  
  
  function devolverValor($factura,$parametro){
	  
	include ('conexion.php');

	  $sql = "SELECT count(Cuie) as total FROM [dbo].[FACTURACION] where NumFactura = '$factura'".$parametro;
	  $stmt = sqlsrv_query($conn, $sql);
	  $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	  return $row['total'];
  }

?>
</p>
<div id="divRechazados" style="display : none;">
  <table width="341" border="1">
    <tr>
      <td width="120">&nbsp;</td>
      <td width="96">Cant</td>
      <td width="103">Porcentaje</td>
    </tr>
    <tr>
      <td>Aprobados</td>
      <td><?php  echo devolverValor($nrofactura," and Autorizado = 'S' and Procesado = 'S' ");?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Duplicados</td>
      <td><?php  echo devolverValor($nrofactura," and Autorizado is NULL and Procesado is NULL ");?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Obra Social</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Fuera de T&eacute;rmino</td>
      <td><?php echo devolverValor($nrofactura," and CodRechazoPractica = '8'");?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Sin Convenio</td>
      <td><?php echo devolverValor($nrofactura," and CodRechazoPractica = '9'");?></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
<p></p>
<p>&nbsp;</p>
<p>&nbsp; </p>
    
<script>
    var cbregistros = document.getElementById('cbregistros');
    var divRegistros = document.getElementById('divRegistros');
	
	var dbTiposPracticas = document.getElementById('cbTiposPracticas');
	var divTiposPracticas = document.getElementById('divTiposPracticas');
	
	var cbTotalesPracticas = document.getElementById('cbTotalesPracticas');
	var divCuadro3 = document.getElementById('divCuadro3');
	
	var cbRechazados = document.getElementById('cbRechazados');
	var divRechazados = document.getElementById('divRechazados');
	
	
	cbRechazados.addEventListener('change', function() {
      if (cbRechazados.checked) {
        divRechazados.style.display = 'block';
      } else {
        divRechazados.style.display = 'none';
      }
    });	
	

	cbTotalesPracticas.addEventListener('change', function() {
      if (cbTotalesPracticas.checked) {
        divCuadro3.style.display = 'block';
      } else {
        divCuadro3.style.display = 'none';
      }
    });
	
	cbTiposPracticas.addEventListener('change', function() {
      if (cbTiposPracticas.checked) {
        divTiposPracticas.style.display = 'block';
      } else {
        divTiposPracticas.style.display = 'none';
      }
    });

    cbregistros.addEventListener('change', function() {
      if (cbregistros.checked) {
        divRegistros.style.display = 'block';
      } else {
        divRegistros.style.display = 'none';
      }
    });
  </script>
</body>
</html>