
		<link rel="stylesheet" type="text/css" href="Classes/rmenu/css/default.css" />
		<link rel="stylesheet" type="text/css" href="Classes/rmenu/css/component.css" />
        <link href="estilos.css" rel="stylesheet" type="text/css" />
		<script src="Classes/rmenu/js/modernizr.custom.js"></script>
<div class="container demo-1">
 <div id="dl-menu" class="dl-menuwrapper">
        <button class="dl-trigger">Open Menu</button>
        <ul class="dl-menu">
          <li> <a href="index.php">Menu Principal</a> </li>
              <li> <a href="#">Configuracion</a>
            <ul class="dl-submenu">
              <li><a href="actas_parametros.php">Parametros</a></li>
			  <li><a href="actas_caps_personal.php">Personal</a></li>
              <li><a href="actas_caps_personal_nuevo.php">Agregar Personal</a></li>
            </ul>
          </li>
          <li> <a href="actas_alta.php">Actas</a> </li>
          <li> <a href="mensajes.php">Mensajes</a> </li>
          <li> <a href="actas_debitos.php">Debitos/Creditos</a> </li>
          <li> <a href="actas_becados.php">Pago a Becados</a> </li>
          <li> <a href="actas_bienes.php">Bienes Entregados</a> </li>
          <li> <a href="actas_cuenta_escritural.php">Cuenta Escritural</a> </li>
          <li> <a href="actas_listado.php">Listado de Actas</a> </li>
          <li> <a href="actas_directos.php">ACTAS DIRECTOS</a> </li>
          <li> <a href="actas_saldoinicial.php">Saldo Inicial</a> </li>
          <li> <a href="actas_saldodisponible_area.php">Saldo Disponible x Area</a> </li>
          
          <li> <a href="actas_registrofechaop.php">Registro de Fecha de Notificacion/Debito Bancario</a> </li>
          
         
          
          
          <li> <a href="#">Opciones de prueba</a>
            <ul class="dl-submenu">
              <li> <a href="#">Living Room</a>
                <ul class="dl-submenu">
                  <li><a href="#">Sofas &amp; Loveseats</a></li>
                  <li><a href="#">Coffee &amp; Accent Tables</a></li>
                  <li><a href="#">Chairs &amp; Recliners</a></li>
                  <li><a href="#">Bookshelves</a></li>
                </ul>
              </li>
              <li> <a href="#">Bedroom</a>
                <ul class="dl-submenu">
                  <li> <a href="#">Beds</a>
                    <ul class="dl-submenu">
                      <li><a href="#">Upholstered Beds</a></li>
                      <li><a href="#">Divans</a></li>
                      <li><a href="#">Metal Beds</a></li>
                      <li><a href="#">Storage Beds</a></li>
                      <li><a href="#">Wooden Beds</a></li>
                      <li><a href="#">Children's Beds</a></li>
                    </ul>
                  </li>
                  <li><a href="#">Bedroom Sets</a></li>
                  <li><a href="#">Chests &amp; Dressers</a></li>
                </ul>
              </li>
              <li><a href="#">Home Office</a></li>
              <li><a href="#">Dining &amp; Bar</a></li>
              <li><a href="#">Patio</a></li>
            </ul>
          </li>
          <li> <a href="phplogin/logout.php">Cerrar Sesion</a> </li>
        </ul>
      </div>
    </div>
      <script src="Classes/rmenu/js/jquery.dlmenu.js"></script>
  <script>
			$(function() {
				$( '#dl-menu' ).dlmenu();
			});
		</script>