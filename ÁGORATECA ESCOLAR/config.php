<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'u339208770_Waldo026AECTIS');
define('DB_PASS', 'Agora@teca026@bdd');
define('DB_NAME', 'u339208770_Agorateca');

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset UTF-8
$conn->set_charset("utf8mb4");

/*
 * BASE_URL: ruta base relativa al host para cargar recursos (style.css, scripts, imágenes)
 * Ejemplos:
 * - Si el proyecto está en la raíz: BASE_URL => ''  -> href: /style.css
 * - Si el proyecto está en /Agorateca26: BASE_URL => '/Agorateca26' -> href: /Agorateca26/style.css
 *
 * Esto evita problemas cuando se accede desde distintas rutas o subdirectorios.
 */
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($base_path === '') {
    // proyecto en la raíz del dominio
    define('BASE_URL', '');
} else {
    define('BASE_URL', $base_path);
}
?>