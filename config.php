<?php
/**
 * Archivo de configuración para la conexión a la base de datos
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'inmobiliaria');

// Configuración de paginación
define('REGISTROS_POR_PAGINA', 5);

/**
 * Función para conectar a la base de datos
 * @return mysqli|false Conexión a la base de datos o false en caso de error
 */
function conectarDB() {
    $conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    
    // Establecer charset UTF-8
    mysqli_set_charset($conexion, "utf8");
    
    return $conexion;
}

/**
 * Función para cerrar la conexión
 * @param mysqli $conexion
 */
function cerrarDB($conexion) {
    if ($conexion) {
        mysqli_close($conexion);
    }
}
?>