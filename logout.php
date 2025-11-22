<?php
session_start();
require_once 'config.php';

// Si existe una cookie 'remember_me', invalidar el token en la BD
if (isset($_COOKIE['remember_me']) && isset($_SESSION['user_id'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ? AND remember_token = ?");
    $stmt->bind_param("is", $_SESSION['user_id'], $token);
    $stmt->execute();
    $stmt->close();
}

// Destruir la cookie en el navegador
setcookie('remember_me', '', time() - 3600, "/");

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al login
header("Location: login.php");
exit();
?>