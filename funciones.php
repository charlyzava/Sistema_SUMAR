<?php

//Debe incluirse luego de conexion.php


// La entrada debe ser algo así: $periodo = "01/2024";
// el resultado:$nomenclador = obtenerNomenclador($periodo, $conn);
//echo "El nomenclador para el período $periodo es: $nomenclador";
function obtenerNomenclador($periodo, $conn) {
    // Crear objeto DateTime con el formato 'd/m/Y'
    $fecha = DateTime::createFromFormat('d/m/Y', '01/' . $periodo);
    
    // Formatear la fecha en el formato 'Y-m-d'
    $fecha_formateada = $fecha->format('Y-m-d');
    
    // Consulta SQL para obtener el nomenclador
    $sql = "SELECT *
            FROM NOMENCLADOR
            WHERE '$fecha_formateada' >= FECHAINICIO
            AND ('$fecha_formateada' <= FECHAFIN OR FECHAFIN IS NULL)";
    
    // Ejecutar la consulta
    $ressql = sqlsrv_query($conn, $sql);
    
    // Verificar si se obtuvieron resultados
    if ($ressql === false) {
        return "Error al ejecutar la consulta: " . sqlsrv_errors()[0]['message'];
    }
    
    // Verificar si se encontró un nomenclador
    if (sqlsrv_has_rows($ressql)) {
        // Obtener el primer resultado
        $row2 = sqlsrv_fetch_array($ressql);
        $nomenclador_result = $row2['NOMBRE'];
        return $nomenclador_result;
    } else {
        return "No se encontró ningún nomenclador para el período $periodo";
    }
}

// SELECCIONAR NOMENCLADOR SEGUN FECHA PRACTICA formato 01/01/2023
function obtenerNomencladorFecha($fecha, $conn) {
    
    // Consulta SQL para obtener el nomenclador
    $sql = "SELECT *
            FROM NOMENCLADOR
            WHERE '$fecha' >= FECHAINICIO
            AND ('$fecha' <= FECHAFIN OR FECHAFIN IS NULL)";
    
    // Ejecutar la consulta
    $ressql = sqlsrv_query($conn, $sql);
    
    // Verificar si se obtuvieron resultados
    if ($ressql === false) {
        return "Error al ejecutar la consulta: " . sqlsrv_errors()[0]['message'];
    }
    
    // Verificar si se encontró un nomenclador
    if (sqlsrv_has_rows($ressql)) {
        // Obtener el primer resultado
        $row2 = sqlsrv_fetch_array($ressql);
        $nomenclador_result = $row2['NOMBRE'];
        return trim($nomenclador_result);
    } else {
        return "No se encontró ningún nomenclador para el período $periodo";
    }
}
// FIN SELECCIONAR NOMENCLADOR SEGUN FECHA
?>