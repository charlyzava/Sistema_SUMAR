<?php 
//Cambiar a Base ACTAS
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!doctype html>


<head>
<meta charset="LATIN1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>.:: Cuenta Escritural ::.</title>
  <link rel="stylesheet" href="./lib/1.12.1/jquery-ui.css">

  <script type="text/javascript">
 
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
 
 		c = confirm('Ã,Â¿Confirma Cerrar la Factura?');
	if (c) {
 
		//form1: nombre del formulario
		//tacta,tanio: edits con los valores
 		acta = document.form1.tacta.value;
		anio_acta = document.form1.tanio.value;
         //Aqu el resultado
		jugador = document.getElementById('jugador');
 
		//instanciamos el objetoAjax
		ajax = objetoAjax();
 
		//Abrimos una conexiÃ,Â¡metros el todo de envo, y el archivo que realizar las operaciones deseadas
		ajax.open("POST", "actas_cierrafactura.php", true);
 
		//cuando el objeto XMLHttpRequest cambia de estado, la funcin se inicia
		ajax.onreadystatechange = function() {
 
             //Cuando se completa la peticin, mostrar los resultados 
			if (ajax.readyState == 4){
 
				//El Ã,Â©todo responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
				jugador.value = (ajax.responseText) 
				salida.value = (ajax.responseText)
			}
		} 
 
		//Llamamos al todo setRequestHeader indicando que los datos a enviarse estn codificados como un formulario. 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
		//enviamos las variables a 'consulta.php' 
		//ajax.send("&equipo="+equipo) 
		ajax.send("&acta="+acta+"&anio_entrega="+anio_acta) ;    
		
 
	} 
 
} 

</script>
  <script src="./lib/jquery-1.12.4.js"></script>
  <script src="./lib/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( "dateFormat", element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>
  <script>
		//Muestra u oculta BECADOS
$(document).ready(function(){
   $("#mostrar_becados").click(function(){
		  if($("#mostrar_becados").is(':checked')) { ;
         $("#divbecados").css("display", "block");;
      }else{
		 $("#divbecados").css("display","none");
      }
   });



		//Muestra u oculta ENTREGAS

   $("#mostrar_entregas").click(function(){      
		  if($("#mostrar_entregas").is(':checked')) { ;
         $("#diventregas").css("display", "block");
      }else{
         $("#diventregas").css("display", "none");
      }
   });
   
  	 //Muestra u oculta ORDENPAGO
   
      $("#mostrar_ordenpago").click(function(){      
		  if($("#mostrar_ordenpago").is(':checked')) { ;
         $("#divordenpago").css("display", "block");
      }else{
         $("#divordenpago").css("display", "none");
      }
   });
   
		//Muestra u oculta ORDENPAGO CAPITADO


         $("#mostrar_ordenpago_capitado").click(function(){      
		  if($("#mostrar_ordenpago_capitado").is(':checked')) { ;
         $("#divordenpagocapitado").css("display", "block");
      }else{
         $("#divordenpagocapitado").css("display", "none");
      }
   });
   
   
   //Muestra u oculta ORDENPAGO FONES


         $("#mostrar_ordenpago_fones").click(function(){      
		  if($("#mostrar_ordenpago_fones").is(':checked')) { ;
         $("#divordenpagofones").css("display", "block");
      }else{
         $("#divordenpagofones").css("display", "none");
      }
   });
   
   
   
     	 //Muestra u oculta CESION DESDE
   
      $("#mostrar_cesiondesde").click(function(){      
		  if($("#mostrar_cesiondesde").is(':checked')) { ;
         $("#divcesiondesde").css("display", "block");
      }else{
         $("#divcesiondesde").css("display", "none");
      }
   });
   
   
        	 //Muestra u oculta CESION HASTA
   
      $("#mostrar_cesionhasta").click(function(){      
		  if($("#mostrar_cesionhasta").is(':checked')) { ;
         $("#divcesionhasta").css("display", "block");
      }else{
         $("#divcesionhasta").css("display", "none");
      }
   });
   
   
});
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Consulta de Beneficiarios ::.</title>

<style type="text/css">
body {
	background-color: #D6D6D6;
	text-align: center;
}
Codigo: Seleccionar todo 
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
<?php include('partials_actas_cuenta_escritural.php'); ?>
</body>
</html>
