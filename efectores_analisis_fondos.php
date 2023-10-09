<?php include ('\conexion.php');
$consultaexportar='';
if (isset($_POST['consultar'])){

ini_set('max_execution_time',3990) ;


$periododesde="";
$periodohasta="";

	$a11=0;
	$a12=0;
	$a13=0;
	$a21=0;
	$a22=0;
	$a23=0;
	$a31=0;
	$a32=0;
	$a41=0;	
	$a42=0;
	$a43=0;
	$a51=0;
	$a52=0;
	$a53=0;
	$a61=0;
	$a62=0;
	$a63=0;
	$a71=0;

	function consulta($codigo,$subcodigo,$finicio,$ffin,$quecuie,$conn){
					  
				  
					  
					  $conscompleta="select SUM(importe) as total from ACTAS A LEFT JOIN ACTAS_BIENES B on A.nro_acta = B.nro_acta and A.anio_acta = B.anio_acta
WHERE A.cuie = '$quecuie' and codigo = '$codigo' and subcodigo = '$subcodigo' and f_entrega BETWEEN '$finicio' and '$ffin'  ";
					  
		$result=sqlsrv_query($conn,$conscompleta);
		$row=sqlsrv_fetch_array($result);	
	return $row['total'];
	
	}
	

$CUIE="";
if(isset($_POST['consultar'])){
	if (isset($_POST['scuie'])){
		$CUIE=$_POST['scuie'];
		$CUIE=explode("-", $CUIE );
		$CUIE=$CUIE[0];
		if (($CUIE<>"Todos")AND($CUIE<>"")){	
		$consultacuie=" AND E.CUIE ='".$CUIE."'";
	} else { 
		$CUIE=""; 
	}
} 
// AQUI SAQUE }

$area=$_POST['tarea'];

	if ($CUIE <> ""){ $CUIE = " CUIE = '$CUIE' AND "; } else { $CUIE =''; }
    $convenio = "CONVENIO = 'S' AND "; 
	if ($area <> ""){ $qarea = "AREA = '$area' AND "; } else { $qarea = ''; }

	$consulta="";
	if (($CUIE<>"") or ($area<>"")){
	
	
	
	$consulta=" WHERE ".$convenio.$qarea.$CUIE;
	$consulta = rtrim($consulta, "AND ");
	}
	

	
$consultaexportar="select * from SUMAR.dbo.EFECTORES $consulta ORDER BY AREA,CUIE";
$res_caps=sqlsrv_query($conn,$consultaexportar);

} else {
	$cuie = '';
	$nomefe='';
	$departamento='';
	$area='';
	$conv='TODOS';
	
}

//############################# PRUEBA DESDE AQUI

								// SE CONTABILIZA DESDE ESTA FECHA
									$fechainicio='2021-01-01';
							//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
							
							
							// FECHA LIMITE
							
							
							$mesprueba=date("m");
							$anioprueba=date("Y");
								$dia=date("d",(mktime(0,0,0,$mesprueba+1,1,$anioprueba)-1));
								$dia=trim($dia);
								$fechalimite=$anioprueba."-".$mesprueba."-".$dia;
							
$periododesde=explode("/",($_POST['tperiododesde']));
$periodohasta=explode("/",($_POST['tperiodohasta']));


//##########################
$ultimo_dia = cal_days_in_month(CAL_GREGORIAN, $periodohasta[0], $periodohasta[1]);
$ultimo_dia = $ultimo_dia."/".$periodohasta[0]."/".$periodohasta[1];
$primer_dia = "01"."/".$periododesde[0]."/".$periododesde[1];

//$comprobante=$_POST['tcomprobante'];

if (isset($periododesde[1])){ $anioinicio=$periododesde[1]; } else { $anioinicio=0;  }
if (isset($periodohasta[1])){ $aniofin=$periodohasta[1];  } else { $aniofin=0; }

$IMPORTE=0;
$mesdesde=$periododesde[0];
$meshasta=$periodohasta[0];
$diciembre=12;

if (isset($anioinicio)){ $anio=$anioinicio; }
$mes=$mesdesde;
$salida = "";
$periodo = "";


//#########################################################################
// BUSQUEDA POR PERIODO
if (($_POST['tperiododesde']<>'')){
if ($anio==$aniofin){
	while ($mes<=$meshasta){
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
				
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		
		$mes=$mes+1;
		$salida.=",";
	}
	$salida=rtrim($salida,",");	
} //////////////////// OK

if ($anio<$aniofin){
	while ($anio<$aniofin){
		while ($mes<=12){
		
			if ($mes=="1"){ $mes="01"; }
			if ($mes=="2"){ $mes="02"; }
			if ($mes=="3"){ $mes="03"; }
			if ($mes=="4"){ $mes="04"; }
			if ($mes=="5"){ $mes="05"; }
			if ($mes=="6"){ $mes="06"; }
			if ($mes=="7"){ $mes="07"; }
			if ($mes=="8"){ $mes="08"; }
			if ($mes=="9"){ $mes="09"; }

			$periodo=$mes."/".$anio;
			$salida.="'".$periodo."'";
			$mes=$mes+1;
			$salida.=",";			
		}
		
		$anio=$anio+1;
		$mes=1;
}
$mes=1;
while($mes<=$meshasta){
	
		if ($mes=="1"){ $mes="01"; }
		if ($mes=="2"){ $mes="02"; }
		if ($mes=="3"){ $mes="03"; }
		if ($mes=="4"){ $mes="04"; }
		if ($mes=="5"){ $mes="05"; }
		if ($mes=="6"){ $mes="06"; }
		if ($mes=="7"){ $mes="07"; }
		if ($mes=="8"){ $mes="08"; }
		if ($mes=="9"){ $mes="09"; }
		
		$periodo=$mes."/".$anio;
		$salida.="'".$periodo."'";
		$mes=$mes+1;
		$salida.=",";	
	}		
}
$salida=rtrim($salida,",");
}// periodo   		


	if($_POST['tperiododesde']<>''){  $paramperiodo=" AND E.PERIODO IN ($salida)";  } else {  $paramperiodo = "";  }

} // CREO $_POST CONSULTAR

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.:: Consulta de Efectores ::.</title>

 <link rel="stylesheet" href="lib/jquery-ui.css">
  <script src="lib/jquery-1.10.2.js"></script>
  <script src="lib/jquery-ui.js"></script>
  <link rel="stylesheet" href="style.css">
  
  <style type="text/css">
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 2px 5px;
  }
  body {
	background-color: #CD9C9C;
}
  </style>
  
  <script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#combobox" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
  });
  </script>

<link href="estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	color: #000000;
}
</style>
</head>

<body>


<script src="Highcharts611/code/highcharts.js"></script>
<script src="Highcharts611/code/modules/data.js"></script>
<script src="Highcharts611/code/modules/exporting.js"></script>
<script src="Highcharts611/code/modules/export-data.js"></script>




<center>
  <table width="100%" border="0">
    <tr>
      <td width="33%" align="left"><a href="index.php"><img src="imgs/home.gif" alt="Home" width="50" height="50" /></a></td>
      <td width="34%" align="center"><table width="500" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="12" height="56" align="center" bgcolor="#FFFFFF"><img src="imgs/tituloizq.gif" alt="" width="13" height="46" /></td>
          <td align="center" bgcolor="#FFFFFF"><a href="beneficiario_ceb_sinpractica.php" class="encabezadopag">An&aacute;lisis Uso de Fondos</a></td>
          <td width="12" align="center" bgcolor="#FFFFFF"><a href="semaforo.php" class="encabezadopag"><img src="imgs/tituloder.gif" alt="" width="12" height="46" /></a></td>
        </tr>
      </table></td>
      <td width="33%" align="right">&nbsp;</td>
    </tr>
  </table>
</center>
<form action="" method="post" name="form1" target="_self" id="form1">
<table width="700" border="1" align="center">
<tr>
  <td width="180">CUIE</td>
  <td colspan="2"><?php include('comboselec3.php'); ?>
</td>
</tr>
<tr>
  <td>&Aacute;rea</td>
  <td colspan="2"><label for="tarea"></label>
    <input name="tarea" type="text" id="tarea" value="<?php if (isset($area)){ echo $area; } ?>" size="10" /></td>
</tr>
<tr>
  <td>Per&iacute;odo</td>
  <td width="420">MM/AAAA
    <input name="tperiododesde" type="text" id="tperiododesde" value="<?php if (isset($_POST['tperiododesde'])){ echo $_POST['tperiododesde']; }?>" size="10" /></td>
  <td width="420">MM/AAAA
    <input name="tperiodohasta" type="text" id="tperiodohasta" value="<?php if (isset($_POST['tperiodohasta'])){ echo $_POST['tperiodohasta']; } ?>" size="10" /></td>
</tr>
</table>
<p>
  <center>
    <?php echo $consultaexportar; ?> <br />
    <input type="submit" name="consultar" id="consultar" value="Consultar" />
    &nbsp;
  </center>
</p>
</form>
<table width="100%" border="1">
  <tr class="encabezado">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3" align="center">1</td>
    <td colspan="3" align="center">2</td>
    <td colspan="2" align="center">3</td>
    <td colspan="3" align="center">4</td>
    <td colspan="3" align="center">5</td>
    <td colspan="3" align="center">6</td>
    <td align="center">7</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="encabezado">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">Incentivo al Personal</td>
    <td colspan="3">Locaci&oacute;n de Obras y/o Servicios</td>
    <td colspan="2">Insumos</td>
    <td colspan="3">Inversiones</td>
    <td colspan="3">Mantenimiento</td>
    <td colspan="3">Capacitaci&oacute;n</td>
    <td>Cesi&oacute;n</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="encabezado">
    <td>Cuie</td>
    <td>AREA</td>
    <td>1 M&eacute;dicos</td>
    <td>2 Enfermeros y Agentes Sanitarios</td>
    <td>3 Administrativos y Otros</td>
    <td>1 M&eacute;dicos</td>
    <td>2 Enfermeros y Agentes Sanitarios</td>
    <td>3 Administrativos y Otros</td>
    <td><a>1 M&eacute;dicos</a></td>
    <td>2 Otros</a></td>
    <td>1 Edilicias</a></td>
    <td>2 Equipamiento M&eacute;dico</a></td>
    <td>3 Equipamiento No M&eacute;dico</a></td>
    <td>1 Edilicio y Otros</a></td>
    <td>2 Equipamiento M&eacute;dico</a></td>
    <td>3 Equipamiento No M&eacute;dico</a></td>
    <td>1 M&eacute;dicos</a></td>
    <td>2 Enfermeros y Agentes Sanitarios</a></td>
    <td>3 Administrativos y Otros</a></td>
    <td>Cesi&oacute;n</a></td>
    <td>Total</td>
  </tr>
  <?php
  		$total=0;
		$totalconvenio=0;
		$totalsinconvenio=0;
		$totalfinal=0;	
		
		
				$ta11=0;
		$ta12=0;
		$ta13=0;
		$ta21=0;
		$ta22=0;
		$ta23=0;
		$ta31=0;
		$ta32=0;
		$ta41=0;
		$ta42=0;
		$ta43=0;
		$ta51=0;
		$ta52=0;
		$ta53=0;
		$ta61=0;
		$ta62=0;
		$ta63=0;
		$ta71=0;
		
		$sumatotal=0;
		
		
 if (isset($res_caps)){ while($row=sqlsrv_fetch_array($res_caps))
	{
		$total=$total+1;
		
		
		
		$quecuie=$row["CUIE"];
	
	$a11=consulta("1","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a12=consulta("1","2",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a13=consulta("1","3",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a21=consulta("2","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a22=consulta("2","3",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a23=consulta("2","4",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a31=consulta("3","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a32=consulta("3","2",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a41=consulta("4","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a42=consulta("4","2",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a43=consulta("4","3",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a51=consulta("5","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a52=consulta("5","2",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a53=consulta("5","3",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a61=consulta("6","1",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a62=consulta("6","2",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a63=consulta("6","3",$primer_dia,$ultimo_dia,$quecuie,$conn);
	$a71=consulta("7","1",$primer_dia,$ultimo_dia,$quecuie,$conn);

//####################################################		
		//############## BECADOS
		 $cons_becados="
 select SUM(E.importe) as IMPORTE  FROM
(SELECT B.cuie, P.*, case when len(mes)<2 then '0'+ LTRIM(RTRIM(CAST(mes as char)))+'/'+ CAST(anio as char) else LTRIM(RTRIM(cast(mes as char)))+'/'+ CAST(anio as char) end as PERIODO
FROM [dbo].[BECADOS] B LEFT JOIN BECADOS_PAGOS P ON B.dni = P.dni
) E
where E.cuie = '$quecuie' $paramperiodo ";
	$res_becados=sqlsrv_query($conn,$cons_becados);
$rowc=sqlsrv_fetch_array($res_becados);
$becados=$rowc["IMPORTE"];
		
			//====================================== INICIO CESION =====================================//
		
		$fecha_cesion = " and fecha <= '$ultimo_dia' and fecha >= '$primer_dia' ";

		

$cons_cesion_desde="select SUM(importe) AS importe FROM dbo.CESIONES WHERE cuie_desde = '$quecuie' $fecha_cesion ";
$res_cesion_desde=sqlsrv_query($conn,$cons_cesion_desde);
$rowc=sqlsrv_fetch_array($res_cesion_desde);
$cesion_desde=$rowc['importe'];
$cesion_desdef=number_format($cesion_desde,2, ',', '.');
//====================================== FIN CESION =========================================//
			
		$a23=$becados;
		$a71=$cesion_desde;
		
		$suma=$a11+$a12+$a13+$a21+$a22+$a23+$a31+$a32+$a41+$a42+$a43+$a51+$a52+$a53+$a61+$a62+$a63+$a71;
		
		$sumatotal=$sumatotal+$suma;
	
		$ta11=$ta11+$a11;
		$ta12=$ta12+$a12;
		$ta13=$ta13+$a13;
		$ta21=$ta21+$a21;
		$ta22=$ta22+$a22;
		$ta23=$ta23+$a23;
		$ta31=$ta31+$a31;
		$ta32=$ta32+$a32;
		$ta41=$ta41+$a41;
		$ta42=$ta42+$a42;
		$ta43=$ta43+$a43;
		$ta51=$ta51+$a51;
		$ta52=$ta52+$a52;
		$ta53=$ta53+$a53;
		$ta61=$ta61+$a61;
		$ta62=$ta62+$a62;
		$ta63=$ta63+$a63;
		$ta71=$ta71+$a71;		
		
  ?>
  <tr>
    <td><?php echo $row["CUIE"]; ?></td>
    <td align="left"><?php // echo $row["NOMBREEFECTOR"]; ?><?php echo $row["AREA"]; ?></td>
    <td><?php if (! is_null($a11)) { echo "$ ".number_format($a11,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a12)) { echo "$ ".number_format($a12,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a13)) { echo "$ ".number_format($a13,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a21)) { echo "$ ".number_format($a21,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a22)) { echo "$ ".number_format($a22,2, ',', '.'); } ?></td>
    <td><?php  if (! is_null($a23)) { echo "$ ".number_format($a23,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a31)) { echo "$ ".number_format($a31,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a32)) { echo "$ ".number_format($a32,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a41)) { echo "$ ".number_format($a41,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a42)) { echo "$ ".number_format($a42,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a43)) { echo "$ ".number_format($a43,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a51)) { echo "$ ".number_format($a51,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a52)) { echo "$ ".number_format($a52,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a53)) { echo "$ ".number_format($a53,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a61)) { echo "$ ".number_format($a61,2, ',', '.'); } ?></td>
    <td><?php if
	 (! is_null($a62)) { echo "$ ".number_format($a62,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a63)) { echo "$ ".number_format($a63,2, ',', '.'); } ?></td>
    <td><?php if (! is_null($a71)) { echo "$ ".number_format($a71,2, ',', '.'); } ?></td>
    <td><?php echo "$ ".number_format($suma,2, ',', '.');  ?></td>
  </tr>
  <?php } ; }//echo $totalfinal;  ?>
  <tr>
    <td>&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td><?php echo  "$ ".number_format($ta11,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta12,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta13,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta21,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta22,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta23,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta31,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta32,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta41,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta42,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta43,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta51,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta52,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta53,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta61,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta62,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta63,2, ',', '.'); ?></td>
    <td><?php echo  "$ ".number_format($ta71,2, ',', '.'); ?></td>
    <td><?php echo "$ ".number_format($sumatotal,2, ',', '.'); ?></td>
  </tr>
  
</table>
<p>&nbsp;<?php  echo $paramperiodo; ?></p>
<p>&nbsp;</p>


<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable">
    <thead>
        <tr>
            <th></th>
            <th>Valores</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Uno uno</th>
            <td><?php echo $ta11; ?></td>
        </tr>
        <tr>
            <th>Uno dos</th>
            <td><?php echo $ta12; ?></td>
        </tr>
        <tr>
            <th> Uno tres</th>
            <td><?php echo $ta13; ?></td>
        </tr>
        <tr>
            <th>Dos uno</th>
            <td><?php echo $ta21; ?></td>
        </tr>
        <tr>
            <th>Dos dos</th>
            <td><?php echo $ta22; ?></td>
        </tr>
        <tr>
            <th>Dos tres</th>
            <td><?php echo $ta23; ?></td>
        </tr>
        <tr>
            <th>Tres uno</th>
            <td><?php echo $ta31; ?></td>
        </tr>
        <tr>
            <th>Tres dos</th>
            <td><?php echo $ta32; ?></td>
        </tr>
        <tr>
            <th>Cuatro uno</th>
            <td><?php echo $ta41; ?></td>
        </tr>
        <tr>
            <th>Cuatro dos</th>
            <td><?php echo $ta42; ?></td>
        </tr>
        <tr>
            <th>Cuatro tres</th>
            <td><?php echo $ta43; ?></td>
        </tr>
        <tr>
            <th>Cinco uno</th>
            <td><?php echo $ta51; ?></td>
        </tr>
        <tr>
            <th>Cinco dos</th>
            <td><?php echo $ta52; ?></td>
        </tr>
        <tr>
            <th>Cinco tres</th>
            <td><?php echo $ta53; ?></td>
        </tr>
        <tr>
            <th>Seis uno</th>
            <td><?php echo $ta61; ?></td>
        </tr>
        <tr>
            <th>Seis dos</th>
            <td><?php echo $ta62; ?></td>
        </tr>
        <tr>
            <th>Seis tres</th>
            <td><?php echo $ta63; ?></td>
        </tr>
        <tr>
            <th>Siete</th>
            <td><?php echo $ta71; ?></td>
        </tr>
    </tbody>
</table>

<?php 
if ($area <> ""){ $area=" Area = ".$area; }
if ($CUIE <> ""){ $cuiecompleto=" ".$_POST['scuie']." "; }
$titulo=$area.$cuiecompleto." periodo desde ".$_POST['tperiododesde']." hasta ".$_POST['tperiodohasta'];

?>

		<script type="text/javascript">

Highcharts.chart('container', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Analisis de Uso de Fondos <?PHP echo $titulo; ?>'
    },
    yAxis: {
        allowDecimals: true,
        title: {
            text: 'Fondos'
        }
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true    
            }
        }
    },



    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.point.y + ' ' + this.point.name.toLowerCase();
        }
    }
});
		</script>




<p>&nbsp;</p>
</body>
</html>