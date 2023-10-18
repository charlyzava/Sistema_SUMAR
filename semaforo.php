<?php 
include ('\conexion.php');


$anio2=date("Y");


// AGREGAR MES AÑO AL QUE ESTOY COMPARANDO
// REALIZAR EL CALCULO

$mesanterior=date('m')-1;


if (isset($_POST['tmes'])){ $mes=$_POST['tmes']; } else { $mes = $mesanterior; }
if (isset($_POST['tanio'])){ $anio=$_POST['tanio']; } else { $anio=$anio2; }
if (isset($_POST['tarea'])){ $area=trim($_POST['tarea']); } else { $area = ''; }

if ($area<>''){ 
$areacompartir=$area;
$area=" AND AREA = ".$area; }
if ($mes<>''){ 
	if ($mes == '1'){	$mes='01'; }
	if ($mes == '2'){	$mes='02'; }
	if ($mes == '3'){	$mes='03'; }
	if ($mes == '4'){	$mes='04'; }
	if ($mes == '5'){	$mes='05'; }
	if ($mes == '6'){	$mes='06'; }
	if ($mes == '7'){	$mes='07'; }
	if ($mes == '8'){	$mes='08'; }
	if ($mes == '9'){	$mes='09'; }
	if ($mes == '0'){	$mes='12'; $anio--; }
 }
$ordenado="";
if (isset($_POST['cuie'])){
	$ordenado=" ORDER BY TABLA2.CUIE ";
}
if (isset($_POST['nombre'])){
	$ordenado=" ORDER BY NOMBREEFECTOR ";
}
if (isset($_POST['area'])){
	$ordenado=" ORDER BY AREA ";	
}
if (isset ($_POST['anio'])){
$ordenado=" ORDER BY ANNO";	
}
if (isset ($_POST['mes'])){
$ordenado= " ORDER BY MES";	
}
$consultaexportar="SELECT TABLA2.CUIE, TABLA2.NOMBREEFECTOR, TABLA2.AREA, TABLA2.ANNO, TABLA2.MES, TABLA2.TOTAL, TABLA2.FACTURAS, COUNT(FACTURACION.CUIE) AS CANTPRACT
FROM (

select CUIE, NOMBREEFECTOR, AREA, ANNO, MES, SUM(CAST(montoTotalPedido AS numeric)) as TOTAL, count(CUIE) AS FACTURAS
from( 
SELECT distinct EXPEDIENTES.CUIE, EFECTORES.NOMBREEFECTOR, EFECTORES.AREA, RIGHT(periodo,4) as ANNO, LEFT(periodo,2) AS MES, EXPEDIENTES.MONTOTOTALPEDIDO
 FROM    (
           SELECT EXPEDIENTES.CUIE, MAX(RIGHT(periodo,4)*100 + LEFT(periodo,2)) AS AnnoMes
           FROM EXPEDIENTES 
 
           GROUP BY EXPEDIENTES.CUIE
          ) maxtabla INNER JOIN EXPEDIENTES ON EXPEDIENTES.CUIE = maxtabla.CUIE 
          AND AnnoMes = (RIGHT(expedientes.periodo,4)*100 + LEFT(expedientes.periodo,2))
          INNER JOIN   EFECTORES ON EXPEDIENTES.CUIE = EFECTORES.CUIE WHERE EFECTORES.CONVENIO = 'S' $area 
) tabla
group by cuie, nombreefector, area, anno, mes
) TABLA2
LEFT JOIN FACTURACION ON FACTURACION.Cuie = TABLA2.CUIE and FACTURACION.Periodo = TABLA2.MES+'/'+TABLA2.ANNO
GROUP BY TABLA2.CUIE, TABLA2.NOMBREEFECTOR, TABLA2.AREA, TABLA2.ANNO, TABLA2.MES, TABLA2.TOTAL, TABLA2.FACTURAS
UNION
SELECT DISTINCT EFECTORES.CUIE, EFECTORES.NOMBREEFECTOR, EFECTORES.AREA, 'NO', 'NO', '0', '0', '0'  FROM [dbo].[EFECTORES] where CONVENIO = 'S' AND CUIE NOT IN (SELECT CUIE FROM EXPEDIENTES) 
$area  $ordenado";
$result=sqlsrv_query($conn,$consultaexportar);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Semaforo</title>


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
      <td width="34%" align="center" bgcolor="#FFFFFF" class="encabezado"><span class="encabezadopag">SEMAFORO</span></td>
      <td width="33%" align="right"><a href="excel/semaforo_excel.php?area=<?=$areacompartir?>&amp;anio=<?=$anio?>&amp;mes=<?=$mes?>">
        <?php if ((isset($_POST['cuie']))or(isset($_POST['nombre']))or(isset($_POST['area']))or(isset($_POST['anio']))or(isset($_POST['mes']))){?>
        <img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/efectores_excel.php?anio=<?=$anio?>&amp;mes=<?=$mes?>&amp;area=<?=$areacompartir?>">
        <?php } ?>
      </a></td>
    </tr>
  </table>
  <br />
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="700" border="1" align="center">
    <tr>
      <td width="180" class="texto">Seleccionar Per&iacute;odo</td>
      <td width="420" class="texto">
      Mes
		<?php  
		if (isset($_POST['tmes'])){ $emes= $_POST['tmes']; } else { $emes="01"; }
		//if(isset($_POST['tanio'])){ $eanio= $_POST['tanio']; } else { $eanio="2018"; }
		if (isset($_POST['tarea'])){ $area= $_POST['tarea']; } else { $area='7'; }
		?>
      <input name="tmes" type="text" id="tmes" value="<?php echo $emes; ?>" />        
      <label for="tcuie">A&ntilde;o
      <input name="tanio" type="text" id="tanio" value="<?php echo $anio; ?>" />
      </label></td>
      <td width="90" class="texto">Area
      <input name="tarea" type="text" id="tarea" value="<?php echo $area;?>" size="4" /></td>
    </tr>
  </table>
  <br />
  <table width="100%" border="1">
    <tr class="texto">
    <td width="10%" class="texto"><span class="encabezado">
      <input type="submit" name="cuie" id="cuie" value="-&gt;" onclick="return control_nuevos()"/>
      CUIE
      
    </span></td>
    <td width="18%" class="texto"><span class="encabezado">
      <input type="submit" name="nombre" id="nombre" value="-&gt;" onclick="return control_nuevos()"/>
    Nombre Efector</span></td>
    <td width="5%" colspan="2" class="encabezado">Graficos</td>
    <td width="10%" class="texto"><span class="encabezado">
      <input type="submit" name="area" id="area" value="-&gt;" onclick="return control_nuevos()"/>
      Area
      
    </span></td>
    <td width="9%" class="texto"><span class="encabezado">
      <input type="submit" name="anio" id="anio" value="-&gt;" onclick="return control_nuevos()"/>
      A&ntilde;o
      
    </span></td>
    <td width="9%" class="texto"><span class="encabezado">
      <input type="submit" name="mes" id="mes" value="-&gt;" onclick="return control_nuevos()"/>
      Mes
      
    </span></td>
    <td width="10%" class="texto"><span class="encabezado">
      <input type="submit" name="diferencia" id="diferencia" value="-&gt;" onclick="return control_nuevos()"/>
      Diferencia </span></td>
    <td width="9%" class="texto"><span class="encabezado">Monto Total Pedido</span></td>
    <td width="10%" class="encabezado">Facturas</td>
    <td width="10%" class="encabezado">Cant Pract</td>
    </tr>
  <?php
  		$total=0;
		
 if ((isset($_POST["cuie"])) or (isset($_POST["nombre"])) or (isset($_POST["area"])) or (isset($_POST["anio"])) or (isset($_POST["mes"]))){ while($row=sqlsrv_fetch_array($result))
	{		
		$total=$total+1;		
		
		
		
		
		$mesefe=$row['MES'];
		$anioefe=$row['ANNO'];
		$cantanios=0;
		$cantmeses=0;
		//2015-05
		$mesllave=0;
		while ($anioefe<$anio){
			while ($mesefe<12){
				$cantmeses=$cantmeses+1;
				$mesefe=$mesefe+1;
			}
			$anioefe=$anioefe+1;
			$mesefe=0;		
		}
		if ($anio==$anioefe){
						
			while ($mes>$mesefe){
				$mesefe=$mesefe+1;
				$cantmeses=$cantmeses+1;
				
			}
		}
	
	if ($cantmeses < '3'){ $bgcolor='#2CAB6F'; } 
	if ($cantmeses > '4'){ $bgcolor='#E84A42'; }
	if (($cantmeses ==3)or ($cantmeses == '4')){ $bgcolor='#FFFF80'; }
	if ($row['MES']=="NO"){$bgcolor='#8F9ACD'; }
				$periodo=$row["MES"]."/".$row["ANNO"];
  ?>
  <tr bgcolor="<?php echo $bgcolor;?>">
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row["CUIE"]; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo utf8_encode($row["NOMBREEFECTOR"]); ?></td>
    <td width="10" bgcolor="<?php echo $bgcolor;?>" class="texto"><a href="grafico.php?cuie=<?=$row["CUIE"]?>" target="_blanc" onclick="window.open(this.href, 'ventanita', 'width=800, height=600, scrollbars=NO')"><img src="imgs/barras1.gif" alt="Monto Total Pedido y Pagado" width="21" height="18" /></a></td>
    <td width="10" bgcolor="<?php echo $bgcolor;?>" class="texto"><a href="grafico2.php?cuie=<?=$row["CUIE"]?>" target="_blanc" onclick="window.open(this.href, 'ventanita', 'width=800, height=600, scrollbars=NO')"><img src="imgs/barras2.gif" alt="Cantidad de Practicas Pedidas y Pagadas" width="21" height="18" /></a></td>
    <td class="texto"><?php echo $row["AREA"]; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row["ANNO"]; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row["MES"]; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $cantmeses; ?></td> 
    <?php //$result= settype($row["TOTAL"], 'integer') ?> 
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row['TOTAL']; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row["FACTURAS"]; ?></td>
    <td bgcolor="<?php echo $bgcolor;?>" class="texto"><?php echo $row["CANTPRACT"]; ?> <a href="semaforo_detalle.php?periodo=<?=$periodo?>&amp;cuie=<?=$row["CUIE"]?>" target="_blanc" onclick="window.open(this.href, 'ventanita', 'width=800, height=600, scrollbars=NO')"><img src="imgs/lupa.gif" alt="Ver Qu&eacute; tipo de Pr&aacute;cticas son" width="16" height="15" /></a></td>
  </tr>

  <?php } ; }//echo $totalfinal;  ?>
  <tr>
    <td colspan="13" class="texto">Total:
      <?php echo $total ?> </td>
  </tr>
</table>
</form>
<table width="400" border="1">
  <tr>
    <td>Diferencia en Meses</td>
    <td bgcolor="#2CAB6F">0-2</td>
    <td bgcolor="#FFFF80">3-4</td>
    <td bgcolor="#E84A42">5 o m&aacute;s</td>
    <td bgcolor="#8F9ACD">Nunca Presentaron</td>
  </tr>
</table>
<script type="text/javascript">

function control_nuevos()
{
	var mes = document.all.tmes.value;
	var anio = document.all.tanio.value;
	var area = document.all.tarea.value;
	var fecha = new Date();
	var anioactual = fecha.getFullYear()
	
		if (mes>12){
			alert("El mes no debe ser mayor a 12");
			return false;
		}
		if (anio>anioactual){
			alert("El año no debe ser superior al actual ("+anioactual+")");
			return false;
		}
		if (area>16){
			alert("Por el momento existen 16 areas");
			return false;
		}
		if (isNaN(mes)){
			alert("El mes no es un numero, por favor corrija");
			return false;
		}
		if (isNaN(anio)){
			alert("El anio no es un numero, por favor corrija");	
			return false;
			
		}
		if (isNaN(area)){
			alert("El area debe ser un numero, por favor corrija");
			return false;
		}
}

</script>
<p><?PHP   echo $consultaexportar; ?>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>