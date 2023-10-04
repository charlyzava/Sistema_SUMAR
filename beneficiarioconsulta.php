<?php include ('\conexion.php');
Header('Content-Type: text/html; charset=LATIN1');
$cuie='';

set_time_limit(600000000);
ini_set('memory_limit', '1024M');

if(isset($_POST['consultar'])){
	$apellido=$_POST['tapellido'];
	$nombre=$_POST['tnombre'];
	$activo=$_POST['tactivo'];
	$dni=$_POST['tdni'];
	$fn=$_POST['tfn'];
	$sexo=strtoupper($_POST['tsexo']);
	$grupo=$_POST['tgrupo'];
	$departamento=$_POST['tdepartamento'];
	$localidad=$_POST['tlocalidad'];
	$clavebeneficiario=$_POST['tclavebeneficiario'];
	$area=$_POST['tarea'];
	$indigena=$_POST['tindigena'];
	$ceb=$_POST['tceb'];
	$embarazoactual=$_POST['tembarazoactual'];
	$codbaja=$_POST['tcodbaja'];

	if ($clavebeneficiario <> ""){ $clavebeneficiario = "ClaveBeneficiario = '$clavebeneficiario' AND "; }
	if ($apellido <> ""){ $apellido = "afiApellido LIKE '%$apellido%' AND "; }
	if ($nombre <> ""){ $nombre = "afiNombre LIKE '%$nombre%' AND "; }
	if ($dni <> ""){ $dni = "afiDNI = '$dni' AND "; }	
	if ($fn <> ""){ $fn = "afiFechaNac = '$fn' AND ";}
	if ($sexo <> ""){ $sexo = "afiSexo = '$sexo' AND ";}
	if ($grupo <> ""){ $grupo = "GrupoPoblacional = '$grupo' AND ";}
	if ($activo <> ""){ $activo = "Activo = '$activo' AND ";}
	if ($departamento <> ""){ $departamento = "afiDomDepartamento LIKE '%$departamento%' AND "; }	
	if ($localidad <> ""){ $localidad = "afiDomLocalidad LIKE '%$localidad%' AND "; }
	if ($area <> ""){ $area = "AREA = '$area' AND "; }
	if ($indigena <> ""){ $indigena = "afiDeclaraIndigena = '$indigena' AND ";}
	if ($ceb <> ""){ $ceb= "CEB = '$ceb' AND "; }
	if ($embarazoactual <> ""){ $embarazoactual = " EmbarazoActual = '$embarazoactual' AND "; }
	if ($codbaja <> ""){ $codbaja = " MotivoBaja = '$codbaja' AND "; }
	
	$orden=$_POST['orden'];
	
	switch ($orden) {
    case "apenom":
        $orden = "afiApellido, afiNombre";
        break;
    case "dnibenef":
        $orden = "afiDNI";
        break;
    case "apenommadre":
        $orden = "maApellido, maNombre";
        break;
    case "dnimadre":
        $orden = "maNroDocumento";
        break;
}
		
if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")and($CUIE<>"")){	
		$cuie=" e.CUIE ='".$CUIE."'";
	} else { 
		$CUIE=""; 
	}
} 
		
	$parametros=" WHERE ".$clavebeneficiario.$apellido.$nombre.$dni.$fn.$sexo.$grupo.$activo.$departamento.$localidad.$area.$indigena.$ceb.$embarazoactual.$cuie.$codbaja;
	if ($parametros == " WHERE "){ $parametros=""; }
	$parametros = rtrim($parametros, "AND ");
	if (isset($_POST['orden'])){	$orden = " ORDER BY $orden "; 	} else { $orden = "ORDER BY maApellido, maNombre"; }
	$consultaexportar="select * from NACER_NACION.dbo.SMIAfiliados s left join SUMAR.dbo.EFECTORES e on s.CUIELugarAtencionHabitual = e.CUIE COLLATE Modern_Spanish_ci_as $parametros $orden";
	$result=sqlsrv_query($conn,$consultaexportar);
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Beneficiarios Consulta ::.</title>


<link href="estilos.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">
  <link rel="stylesheet" href="./lib/style.css">
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
    <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Home" width="50" height="50" /></a><a name="inicio" id="inicio"></a></td>
    <td width="34%" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
        <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">Consulta Beneficiario</a></td>
        <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
      </tr>
    </table></td>
    <td width="33%" align="right"><a href="excel/beneficiarioconsulta_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
      <?php if (isset($_POST['consultar'])){?>
    </a><a href="excel/beneficiarioconsulta_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/excel.gif" alt="Generar Archivo de Excel" width="49" height="49" /></a><a href="excel/beneficiarioconsultah_excel.php?parametros=<?=$parametros?>&amp;orden=<?=$orden?>"><img src="imgs/caps.gif" alt="Generar Archivo de Excel" width="64" height="49" /></a><a href="excel/resumenmensual_excel.php?mes=<?=$mes?>&amp;anio=<?=$anio?>">
    <?php } ?>
    </a></td>
  </tr>
</table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
<center>
  </center>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="40%" border="1" align="center">
      <tr>
        <td colspan="2" class="encabezado">Buscar por...<a name="inicio" title="inicio" id="un-nombre2"></a></td>
      </tr>
      <tr>
        <td colspan="2">Clave de Beneficiario
          <label for="textfield2"></label>
          <input type="text" name="tclavebeneficiario" id="textfield2" /></td>
      </tr>
      <tr>
        <td colspan="2"><label for="tapellido"></label>
          Apellido
          <input type="text" name="tapellido" id="tapellido" />
          Nombre
          <input type="text" name="tnombre" id="tnombre" /></td>
      </tr>
      <tr>
        <td colspan="2">DNI
          <input type="text" name="tdni" id="tdni" /></td>
      </tr>
      <tr>
        <td colspan="2">FN(dd-mm.aaaa)
        <input type="text" name="tfn" id="tfn" /></td>
      </tr>
      <tr>
        <td colspan="2">Sexo
        <input type="text" name="tsexo" id="tsexo" /></td>
      </tr>
      <tr>
        <td colspan="2">Grupo
          <select name="tgrupo" id="tgrupo">
          <option value=""></option>
			<option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
        </select></td>
      </tr>
      <tr>
        <td bgcolor="#FCC5AD">CUIE </td>
        <td bgcolor="#FCC5AD"><?php include('comboselec4.php');  ?></td>
    </tr>
      <tr>
        <td colspan="2">Activo
          <input type="text" name="tactivo" id="tactivo" />
          (S o N)</td>
      </tr>
      <tr>
        <td colspan="2">CEB
          <label for="tceb"></label>
          <input type="text" name="tceb" id="tceb" />
          (S o N)</td>
      </tr>
      <tr>
        <td colspan="2">Embarazo Actual
          <label for="tembarazoactual"></label>
          <input type="text" name="tembarazoactual" id="tembarazoactual" />
          (S o N)</td>
      </tr>
      <tr>
        <td colspan="2">Departamento
          <label for="tdepartamento"></label>
          <input type="text" name="tdepartamento" id="tdepartamento" /></td>
      </tr>
      <tr>
        <td colspan="2">Localidad
          <label for="tlocalidad"></label>
          <input type="text" name="tlocalidad" id="tlocalidad" /></td>
      </tr>
      <tr>
        <td colspan="2">Area
          <label for="tarea"></label>
          <input type="text" name="tarea" id="tarea" /></td>
      </tr>
      <tr>
        <td colspan="2">Codigo Baja
        <input type="text" name="tcodbaja" id="tcodbaja" /> 
        (Usar con Activo N)</td>
      </tr>
      <tr>
        <td colspan="2">Indigena
          <label for="tindigena"></label>
          <input type="text" name="tindigena" id="tindigena" />
          (S o N)</td>
      </tr>
  </table>
    <p>Ordenado Por
      <label for="orden"></label>
      <select name="orden" id="orden">
        <option value="apenom" selected="selected">Beneficiario</option>
        <option value="dnibenef">DNi Beneficiario</option>
        <option value="apenommadre">Madre</option>
        <option value="dnimadre">DNI Madre</option>
      </select>
  </p>
    <center>
      <p>
        <input type="submit" name="consultar" id="consultar" value="Consultar" />
        &nbsp;&nbsp;<a href="#fin">Ir abajo</a><br/>
        <?php if (isset($consultaexportar)){ //echo $consultaexportar; 
		
		$consultaexportar="select * from NACER_NACION.dbo.SMIAfiliados s left join SUMAR.dbo.EFECTORES e on s.CUIELugarAtencionHabitual = e.CUIE COLLATE Modern_Spanish_ci_as $parametros $orden";
	$result=sqlsrv_query($conn,$consultaexportar);
		
		
		} ?>
        &nbsp;
        <?php if (isset($parametros)){ echo $parametros; }?>
      </p>
    </center>
</form>
  <table width="100%" border="1">
    <tr>
      <td>Clave Beneficiario</td>
      <td>Apellido, Nombre</td>
      <td>DNI</td>
      <td>Fecha Nacimiento</td>
      <td>DNI, Apellido, Nombre Madre</td>
      <td>CUIE</td>
      <td>Activo</td>
      <td>CEB</td>
      <td>AREA</td>
      <td>Indig</td>
      <td>Leng</td>
      <td>Tribu</td>
      <td>OS</td>
      <td>F Inscr</td>
      <td>Latitud</td>
      <td>Longitud</td>
    </tr>
    <?php
		$totalfinal=0;	
		$totalactivos=0;
		$totalnoactivos=0;
		$cobertura=0;
		$originariosceb=0;
		$originariossinceb=0;
		$originariosactivos=0;
		
		$nooriginariosceb=0;
		$nooriginariossinceb=0;
		$nooriginariosactivos=0;
		
		$sincobertura=0;
		if (isset($result)){
  while($row=sqlsrv_fetch_array($result))
	{ $totalfinal=$totalfinal+1;
	
	if ($row['Activo']=='S'){
		$totalactivos=$totalactivos+1;
	} else {
		$totalnoactivos=$totalnoactivos+1;	
	}
	
	
	
	
	
	if ($row["afiDeclaraIndigena"]=='S'){
		if (($row['CEB']=='S')and($row['Activo']=='S')){
			$originariosceb++;}
		if (($row['CEB']=='N')and($row['Activo']=='S')){
			$originariossinceb++;
		}
		if ($row['Activo']=='S'){
			$originariosactivos++;	
		}
	} else { // NO ORIGINARIOS
		if (($row['CEB']=='S')and($row['Activo']=='S')){
			$nooriginariosceb++;}
		if (($row['CEB']=='N')and($row['Activo']=='S')){
			$nooriginariossinceb++;
		}
		if ($row['Activo']=='S'){
			$nooriginariosactivos++;	
		}
	}
	if (($row['Activo']=='S')and($row['CEB']=='S')){
	$cobertura=$cobertura+1;	
	}
	if (($row['Activo']=='S')and($row['CEB']=='N')){
		$sincobertura++;
	}
	
	$sincobertura=$totalactivos-$cobertura;
	
	$dni=$row['afiDNI'];
	$os="no";
	$query2=" select * FROM NACER_NACION.dbo.SMIDatosSeguridadSocial WHERE NroDocumento = '$dni'";
	
	//echo $query2;
	
	$res2=sqlsrv_query($conn,$query2);
	if (isset($res2)){
		
	while ($row2=sqlsrv_fetch_array($res2)) {
		$os="SI";
	}
	}
  ?>
    <tr>
      <td><?php echo $row["ClaveBeneficiario"]; ?></td>
      <td align="left"><?php echo $row["afiApellido"]; ?>, <?php echo $row["afiNombre"]; ?></td>
      <td><?php echo $row['afiDNI']; ?></td>
      <td><?php echo $row["afiFechaNac"]->format('d-m-Y'); ?></td>
      <td align="left"><?php echo $row['maNroDocumento']." ".$row['maApellido']." ".$row['maNombre']; ?></td>
      <td><a title="<?php echo $row["NOMBREEFECTOR"]; ?>"><?php echo $row["CUIELugarAtencionHabitual"]; ?></a></td>
      <td><a title="<?php echo $row["MensajeBaja"]; ?> "><?php echo $row["Activo"]." ".$row["MotivoBaja"]; ?></a></td>
      <td<?php if ($row["CEB"]=='N'){ ?> bgcolor="#FFFFCC" <?php }; ?>><?php echo $row["CEB"]; ?></td>
      <td><?php echo $row["AREA"]; ?></td>
      <td><?php echo $row["afiDeclaraIndigena"]; ?></td>
      <td><?php echo $row["afiId_Lengua"]; ?></td>
      <td><?php echo $row["afiId_Tribu"]; ?></td>
      <td<?php if ($os=='SI'){ ?> bgcolor="#FF6699" <?php }; ?>><?php echo $os; ?></td>
      <td><?php echo $row["FechaInscripcion"]->format('d-m-Y'); ?></td>
      <td><?php echo $row["UbicacionLatitud"]; ?></td>
      <td><?php echo $row["UbicacionLongitud"]; ?></td>
    </tr>
    <?php } ; }?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php echo $totalactivos; ?>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php //echo $originarios; ?>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <center>
    <table border="1">
      <col width="86" span="6" />
      <tr>
        <td colspan="4">TOTAL GENERAL INSCRIPTOS<a name="fin" title="fin" id="un-nombre"></a></td>
      </tr>
      <tr>
        <td colspan="4" style="text-align: center"><?php echo $totalfinal ?></td>
      </tr>
      <tr>
        <td colspan="2">BENEFICIARIO SUMAR    (ACTIVO)</td>
        <td width="179">BENEFICIARIO NO ACTIVO</td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center"><?php echo $totalactivos ?></td>
        <td><span style="text-align: center"><?php echo $totalnoactivos ?></span></td>
      </tr>
      <tr>
        <td width="145">ACTIVO CON CEB</td>
        <td width="138">ACTIVO SIN CEB</td>
      </tr>
      <tr>
        <td style="text-align: center"><?php echo $cobertura; ?></td>
        <td style="text-align: center"><?php echo $sincobertura ?></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center"><table border="1">
          <col width="86" span="6" />
          <tr>
            <td colspan="2">POB. ORIGINARIA (activos)</td>
            <td colspan="2">POB. NO ORIGINARIA (activos)</td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center"><?php echo $originariosactivos; ?></td>
            <td colspan="2"><?php echo $nooriginariosactivos; ?></td>
          </tr>
          <tr>
            <td>PO CON CEB</td>
            <td>PO SIN CEB</td>
            <td>CON CEB</td>
            <td>SIN CEB</td>
          </tr>
          <tr>
            <td style="text-align: center"><?php echo $originariosceb; ?></td>
            <td style="text-align: center"><?php echo $originariossinceb; ?></td>
            <td><?php echo $nooriginariosceb; ?></td>
            <td><?php echo $nooriginariossinceb; ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </center>
  <p><a href="#inicio">Ir arriba</a></p>
  <p>
    <?php if (isset($query_os)){echo $query_os; } ?>
    &nbsp;</p>
<p></body>
</html>
<?php sqlsrv_close( $conn ); ?>