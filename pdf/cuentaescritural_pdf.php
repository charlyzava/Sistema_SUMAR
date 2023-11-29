<?php include ('../conexion.php');
include('../componentes/convert_num_letras.php');

$i = 4;
$anio=$_GET['anio'];
$mes=$_GET['mes'];

$dia=date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));
$dia=trim($dia);
$fechalimite=$anio."-".$mes."-".$dia;

$cuie=$_GET['cuie'];
$cuielargo=utf8_encode($_GET['cuielargo']);
$periodos=$_GET['periodos'];
$ordenes=$_GET['ordenes'];
$ordenescapitado=$_GET['ordenescapitado'];
$ordenesfones=$_GET['ordenesfones'];
$becados=$_GET['becados'];
$bienes=$_GET['bienes'];
$saldodisponible=$_GET['saldodisponible'];

//$ordenescapitado=number_format($ordenescapitado,2, ',', '.');


// fecha de hoy
$hoy = getdate();
$ya=$hoy['mday']."/".$hoy['mon']."/".$hoy['year'];
//$consulta= mysql_query("SET NAMES utf8");

	$consulta="select SUM(TOTAL) AS TOTAL from dbo.ORDEN_PAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP WHERE E.CUIE = '$cuie' AND O.PERIODO IN ($periodos)";
$salida=$consulta;
$result=sqlsrv_query($conn,$consulta);

 $row=sqlsrv_fetch_array($result);
	$total_ordenpago=$row['TOTAL'];
	$total_entrega=number_format($total_ordenpago,2, ',', '.');

set_time_limit(6000000);
ini_set('memory_limit', '1024M');


require_once('../Classes/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../imgs/membrete.jpg';
        $this->Image($image_file, 10, 10, 195, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Carlos Zavaleta');
$pdf->SetTitle('Orden de Pago');
$pdf->SetSubject('Orden de Pago');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

//===================== ENCABEZADO CUENTA ESCRITURAL ================================

$pdf->SetTextColor(255, 250, 250);
$pdf->SetFillColor(170, 170, 170);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->MultiCell(180, 5, "CUENTA ESCRITURAL", 1, 'C', 1, 0, '' ,'30', true);
//=====================  FIN ENCABEZADO

$pdf->SetTextColor(0, 0, 0);
// set font
$pdf->SetFont('helvetica', '', 9);

$pdf->SetFillColor(255, 255, 255);

$html = <<<EOD
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
A los efectos de brindarle informacion actualizada, sobre el saldo Disponible de la cuenta Escritural del CAPS a su cargo el Area de Auditoria y Supervision del Programa SUMAR Catamarca, implemento un nuevo sistema que incluye la validacion de saldos transferidos y aplicados.
<BR>
<BR>

EOD;
	//<p style="color:#CC0000;"> Por los conceptos que se detallan </p>
$txtcuadro1="Expediente Nº:";	
$y = $pdf->getY();
$left_column="columna izquierda";
$pdf->writeHTMLCell(180, 112, '', 38, $html, 1, 0, 1, true, 'J', true);
$pdf->SetFont('helvetica', '', 12);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//$pdf->MultiCell(17, 5, $fechaordenpago, 1, 'C', 0, 0, 50, 38, true);

$pdf->MultiCell(90, 5, "FECHA: ".$ya, 1, 'C', 1, 0, '55' ,'40', true);
$pdf->MultiCell(175, 5, "CAPS: ".$cuielargo, 1, 'C', 1, 0, '17' ,'46', true);
$pdf->MultiCell(90, 5, "CUIE: ".$cuie, 1, 'C', 1, 0, '55' ,'52', true);
$pdf->MultiCell(90, 5, "HASTA EL PERIODO: ".$mes."/".$anio, 1, 'C', 1, 0, '55' ,'58', true);

$cons_saldo="select FONDO_EMERGENCIA, SALDO_INICIAL from dbo.EFECTORES  WHERE CUIE = '$cuie'";
$res_saldo=sqlsrv_query($conn,$cons_saldo);

 $row=sqlsrv_fetch_array($res_saldo);
	$saldo=$row['SALDO_INICIAL'];
	$saldo_inicial=number_format($saldo,2, ',', '.');
	$fondo=$row['FONDO_EMERGENCIA'];
	$fondo_emergencia=number_format($fondo,2, ',', '.');

$cons_cesion_hasta="select SUM(importe) AS importe FROM dbo.CESIONES WHERE cuie_hasta = '$cuie'";
$res_cesion_hasta=sqlsrv_query($conn, $cons_cesion_hasta);
$rowc=sqlsrv_fetch_array($res_cesion_hasta);
$cesion_hasta1=$rowc['importe'];
$cesion_hasta=number_format($cesion_hasta1,2, ',', '.');


$cons_cesion_desde="select SUM(importe) AS importe FROM dbo.CESIONES WHERE cuie_desde = '$cuie'";
$res_cesion_desde=sqlsrv_query($conn,$cons_cesion_desde);
$rowc=sqlsrv_fetch_array($res_cesion_desde);
$cesion_desde1=$rowc['importe'];
$cesion_desde=number_format($cesion_desde1,2, ',', '.');

	
//
//$pdf->MultiCell(100, 5, "FECHA: ".$ya, 1, 'C', 1, 0, '55' ,'86', true);
//

$pdf->MultiCell(60, 5, "SALDO INICIAL: ", 0, 'L', 1, 0, '55' ,'86', true);
$pdf->MultiCell(90, 5, "$".$saldo_inicial, 1, 'R', 1, 0, '55' ,'86', true);
$pdf->MultiCell(60, 5, "ORDENES: ", 0, 'L', 1, 0, '55' ,'92', true);
$pdf->MultiCell(90, 5, "$".$ordenes, 1, 'R', 1, 0, '55' ,'92', true);

$pdf->MultiCell(60, 5, "ORDENES CAPITADO: ", 0, 'L', 1, 0, '55' ,'98', true);
$pdf->MultiCell(90, 5, "$".$ordenescapitado, 1, 'R', 1, 0, '55' ,'98', true);

$pdf->MultiCell(60, 5, "ORDENES FONES: ", 0, 'L', 1, 0, '55' ,'104', true);
$pdf->MultiCell(90, 5, "$".$ordenesfones, 1, 'R', 1, 0, '55' ,'104', true);

$pdf->MultiCell(60, 5, "BECADOS: ", 0, 'L', 1, 0, '55' ,'110', true);
$pdf->MultiCell(90, 5, "$".$becados, 1, 'R', 1, 0, '55' ,'110', true);
$pdf->MultiCell(60, 5, "BIENES: ", 0, 'L', 1, 0, '55' ,'116', true);
$pdf->MultiCell(90, 5, "$".$bienes, 1, 'R', 1, 0, '55' ,'116', true);

$pdf->MultiCell(60, 5, "MONTO CEDIDO: ", 0, 'L', 1, 0, '55' ,'122', true);
$pdf->MultiCell(90, 5, "$".$cesion_desde, 1, 'R', 1, 0, '55' ,'122', true);
$pdf->MultiCell(60, 5, "MONTO RECIBIDO: ", 0, 'L', 1, 0, '55' ,'128', true);
$pdf->MultiCell(90, 5, "$".$cesion_hasta, 1, 'R', 1, 0, '55' ,'128', true);

$pdf->MultiCell(60, 5, "FONDO_EMERGENCIA: ", 0, 'L', 1, 0, '55' ,'134', true);
$pdf->MultiCell(90, 5, "$".$fondo_emergencia, 1, 'R', 1, 0, '55' ,'134', true);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(60, 5, "SALDO DISPONIBLE: ", 0, 'L', 1, 0, '55' ,'140', true);
$pdf->MultiCell(90, 5, "$".$saldodisponible, 1, 'R', 1, 0, '55' ,'140', true);
$pdf->SetFont('helvetica', '', 12);


//==================== BIENES ENTREGADOS =================================
$pdf->SetFont('helvetica', 'BU', 8);
$pdf->writeHTMLCell(180, 95, '', 148, "Bienes Entregados 2021", 1, 0, 1, true, 'C', true);

//=========== ENCABEZADO ============\\
$altura=152;
$pdf->SetFont('helvetica', 'B', 8);
$pdf->MultiCell(100, 5, "F Entrega", 0, 'L', 1, 0, '17' ,$altura, true);
$pdf->MultiCell(100, 5, "Acta", 0, 'L', 1, 0, '33' ,$altura, true);
$pdf->MultiCell(100, 5, "Cant", 0, 'L', 1, 0, '50' ,$altura, true);
$pdf->MultiCell(100, 5, "Descripcion", 0, 'L', 1, 0, '64' ,$altura, true);
$pdf->MultiCell(20, 5, "Importe", 0, 'R', 1, 0, '130' ,$altura, true);
$pdf->MultiCell(20, 5, "Total", 0, 'R', 1, 0, '170' ,$altura, true);
$pdf->SetFont('helvetica', '', 8);
//========= FIN ENCABEZADO ===========\\

		$sql_bienes="SELECT CANTIDAD as cantidad, CANTIDAD * PRECIOUNITARIO  total,FECHAORDENCOMPRA f_entrega,

  DETALLE descripcion, '' inventario,'' n_serie, PRECIOUNITARIO importe, COMPROBANTE nro_acta, '' anio_acta

 FROM [dbo].[ACTAS_DIRECTOS] a LEFT JOIN ACTAS_DIRECTOS_DETALLE d on a.ID = d.ID_ACTAS_DIRECTOS
where CUIE = '$cuie'

UNION
		
		SELECT count(descripcion) as cantidad, importe * count(descripcion) as total, f_entrega, descripcion,  inventario, n_serie, importe, cast (a.nro_acta as varchar), a.anio_acta   
FROM ACTAS.dbo.ACTAS a LEFT JOIN ACTAS.dbo.ACTAS_BIENES b on a.nro_acta = b.nro_acta AND a.anio_acta = b.anio_acta 
WHERE f_entrega <= '$fechalimite' and f_entrega >= '01/01/2021' and a.cuie='$cuie' group by descripcion, importe, f_entrega, inventario, inventario, n_serie, a.nro_acta, a.anio_acta ORDER BY f_entrega";

		$res_bienes=sqlsrv_query($conn,$sql_bienes);			
		if (isset($res_bienes)){
			$altura=156;
  while($row=sqlsrv_fetch_array($res_bienes))
  
	{ 
	$fecha_entrega=$row['f_entrega'];
	$ffecha_entrega=date_format($fecha_entrega, 'd/m/Y');
	$pdf->MultiCell(100, 5, $ffecha_entrega, 0, 'L', 1, 0, '17' ,$altura, true);
	
	if ($row['anio_acta']==''){
		$expediente=$row['nro_acta'];
	} else {
	$expediente=$row['nro_acta']."/".$row['anio_acta'];
	}
	$pdf->MultiCell(100, 5, strtoupper($expediente), 0, 'L', 1, 0, '33' ,$altura, true);

	$cantidad=$row['cantidad'];
	$pdf->MultiCell(100, 5, $cantidad, 0, 'L', 1, 0, '50' ,$altura, true);	
	
	$descripcion=$row['descripcion'];
	$pdf->MultiCell(100, 5, utf8_encode(mb_strtoupper(substr($descripcion, 0, 37))), 0, 'L', 1, 0, '64' ,$altura, true);
		$importe=$row['importe'];
		$fimporte=number_format($importe,2, ',', '.');

		$total=$row['total'];
		$ftotal=number_format($total,2, ',', '.');

	$pdf->MultiCell(20, 5, "$ ", 0, 'R', 1, 0, '115' ,$altura, true);
	$pdf->MultiCell(140, 4, $fimporte, 1, 'R', 1, 0, '15' ,$altura, true);
	$pdf->MultiCell(20, 5, "$ ", 0, 'R', 1, 0, '155' ,$altura, true);
	$pdf->MultiCell(180, 4, $ftotal, 1, 'R', 1, 0, '15' ,$altura, true);
	
	$altura=$altura+4; //

	if ($altura>=270){
		$pdf->AddPage();
		$altura=30;
	}
	
	}
		}
// multicell  ancho?, altura?, texto, 1 para marco?, 'alineacion', 1, 0, posicion x, posicion y

// ============ FIN BIENES ENTREGADOS ============\\

// ============ SUMA ORDENES DE PAGO X MES ============\\


$altura=$altura+10;

for ($i = 1; $i <> 13; $i++) {
	$altura1=$altura+4;// modifique para la altura
$pos=$pos+15;
	$cons_ordxmes="select SUM(Total) AS TOTAL from dbo.ORDEN_PAGO O INNER JOIN EXPEDIENTES E on O.NROEXPEDIENTE = E.NROEXPEDIENTE AND O.ANIOEXP = E.ANIOEXP
 WHERE E.CUIE = '$cuie' AND  MONTH(FECHAORDENPAGO) = '$mes' AND YEAR(FECHAORDENPAGO) = '$anio'";
$sal_ordxmes=$cons_ordxmes;
$res_ordxmes=sqlsrv_query($conn,$sal_ordxmes);

 $row=sqlsrv_fetch_array($res_ordxmes);
	$total_ordxmes=$row['TOTAL'];

$pdf->MultiCell(15, 4, $anio."/".$mes, 1, 'C', 1, 0, $pos ,$altura, true);
$pdf->MultiCell(15, 4, "$".$total_ordxmes, 1, 'R', 1, 0, $pos ,$altura1, true);
	

$mes--;
if ($mes==0){
	$anio--;
$mes=12;	
}


}


//#####################   PARA FIRMAR  ############################

$altura=$altura+20;

$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(80, 6, " _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" , 0, 'C', 0, 0, '20' ,$altura, true);
$altura=$altura+6;
$pdf->MultiCell(80, 6, " Resp. Area Administracion " , 0, 'C', 0, 0, '22' ,$altura, true);


$altura=$altura-8;

$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(80, 6, " RECIBIDO " , 0, 'C', 0, 0, '120' ,$altura, true);
$altura=$altura+3;
$pdf->MultiCell(80, 6, " DIRECCION DE " , 0, 'C', 0, 0, '120' ,$altura, true);
$altura=$altura+3;
$pdf->MultiCell(80, 6, " ADMINISTRACION " , 0, 'C', 0, 0, '120' , $altura, true);
$altura=$altura+3;
$pdf->MultiCell(80, 6, " MINISTERIO DE SALUD " , 0, 'C', 0, 0, '120' , $altura, true);

//#####################  FIN FIRMAR  ##############################

//=============================================================
$nombre='CE_'.$cuie.'_'.$mes.$anio.'.pdf';
ob_end_clean(); // arrelo error "TCPDF ERROR: Some data has already been output, can't send PDF file"
$pdf->Output($nombre, 'I');

//============================================================+
// END OF FILE
//============================================================+